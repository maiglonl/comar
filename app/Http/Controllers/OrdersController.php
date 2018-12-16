<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\OrderCheckoutRequest;
use App\Repositories\OrderRepository;
use App\Repositories\CardRepository;
use App\Repositories\TaskRepository;
use App\Repositories\ItemRepository;
use App\Repositories\ProductRepository;
use App\PagSeguro\PagSeguro;
use App\Helpers\PermHelper;
use \Correios;

class OrdersController extends Controller{
	protected $repository;

	public function __construct( 
		OrderRepository $repository, 
		ItemRepository $itemRepository, 
		ProductRepository $productRepository,
		CardRepository $cardRepository,
		TaskRepository $taskRepository
	){
		$this->repository = $repository;
		$this->itemRepository = $itemRepository;
		$this->productRepository = $productRepository;
		$this->cardRepository = $cardRepository;
		$this->taskRepository = $taskRepository;

		$this->names = [
			'plural' => 'orders',
			'singular' => 'order',
			'pt_plural' => 'pedidos',
			'pt_singular' => 'pedido',
			'pt_gender' => 'o',
			'base_blades' => 'orders'
		];
	}

	use ControllerTrait {
		ControllerTrait::trait_index as index;
		ControllerTrait::trait_show as show;
		ControllerTrait::trait_destroy as destroy;
		ControllerTrait::trait_find as find;
		ControllerTrait::trait_all as all;
	}

	public function home($id){
		$order = $this->repository->with(['tasks', 'bills'])->find($id);
		if($order->user_id != Auth::id() && !PermHelper::isAdmin()){
			return view('app.errors.permission');
		}
		return view('app.orders.home', compact('order'));
	}

	public function list(){
		$orders = $this->repository->orderBy('id', 'desc')->findWhere([
			'user_id' => Auth::id(),
			['status_id', '<>', STATUS_ORDER_EM_ABERTO]
		]);
		return view('app.orders.list', compact('orders'));
	}

	public function orderIsReady($order){
		$isReady = $order && $order->status_id == STATUS_ORDER_EM_ABERTO && count($order->items) > 0;
		return $isReady;
	}

	public function formAddress(){
		$order = $this->repository->current();
		return view('app.orders.forms.address', compact('order'));
	}

	public function cart(){
		$order = $this->repository->current();
		if($this->orderIsReady($order)){
			$this->updateDeliveryCost($order);
			$order = $this->repository->current();
		}
		return view('app.orders.cart', compact('order'));
	}

	public function updateDeliveryCost($order){
		try {
			$deliveryMethods = [];
			foreach ($order->items as $key => $item) {
				$dados = [
					'tipo'			=> 'sedex,pac',
					'formato'		=> 'caixa',
					'cep_origem'	=> CEP_ORIGEM,
					'cep_destino'	=> $order->zipcode,
					'peso'			=> $item->product['weight'],
					'comprimento'	=> $item->product['length'],
					'altura'		=> $item->product['height'],
					'largura'		=> $item->product['width'],
					'diametro'		=> $item->product['diameter'],
				];
				$deliveryMethods = Correios::frete($dados);
				$orderItem = $order->items[$key];
				$validMethods = [];
				$form = null;
				$time = null;
				$cost = null;
				foreach ($deliveryMethods as $method) {
					if($method['valor'] > 0){
						$validMethods[] = [
							'codigo' => $method['codigo'] == 4510 ? DELIVERY_METHOD_NORMAL : DELIVERY_METHOD_EXPRESS,
							'prazo' => $method['prazo'],
							'valor' => $method['valor'] * $item->quantity
						];
						if($method['valor'] <= $cost || $cost == null){
							$form = $method['codigo'] == 4510 ? DELIVERY_METHOD_NORMAL : DELIVERY_METHOD_EXPRESS;
							$time = $method['prazo'];
							$cost = $method['valor'] * $item->quantity;
						}
					}
				}

				if($orderItem['free_shipping'] == 1 && $form != null){
					foreach ($validMethods as $key => $method) {
						if($method['codigo'] == $form){
							$validMethods[$key]['valor'] = 0;
						}
					}
					$cost = 0;
				}

				// Adiciona opção de 'Retirada em loja'
				$validMethods[] = [
					'codigo' => 0,
					'prazo' => 0,
					'valor' => 0
				];
				$orderItem['delivery_form'] = $form == null ? 0 : $form;
				$orderItem['delivery_time'] = $time == null ? 0 : $time;
				$orderItem['delivery_cost'] = $cost == null ? 0 : $cost;
				
				$orderItem['delivery_methods'] = json_encode($validMethods);
				$this->itemRepository->update($orderItem->toArray(), $orderItem['id']);
			}
		} catch (ValidatorException $e) {
			return response()->json([
				'error'   => true,
				'message' => "Falha ao atualizar formas de entrega!"
			]);
		}
	}

	public function delivery(){
		$deliveryMethods = [];
		$order = $this->repository->current();
		if(!$this->orderIsReady($order)){
			return view('app.orders.cart', compact('order'));
		}
		return view('app.orders.delivery', compact('order'));
	}

	public function billet(){
		$order = $this->repository->current();
		if(!$this->orderIsReady($order)){
			return view('app.orders.cart', compact('order'));
		}
		$order->payment_method = PAYMENT_METHOD_BILLET;
		foreach ($order->items as $item) {
			$item->payment_installments = 1;
			$this->itemRepository->update($item->toArray(), $item->id);
		}
		$this->repository->update($order->toArray(), $order->id);
		return redirect(route('orders.checkout'));
	}

	public function card(){
		$order = $this->repository->current();
		if(!$this->orderIsReady($order)){
			return view('app.orders.cart', compact('order'));
		}
		$order->payment_method = PAYMENT_METHOD_CREDIT_CARD;
		$this->repository->update($order->toArray(), $order->id);
		$order = $this->repository->current();
		foreach ($order->items as $item) {
			$item->payment_installments = 1;
			$this->itemRepository->update($item->toArray(), $item->id);
		}
		return view('app.orders.card', compact(['order']));
	}

	public function createCard(){
		$order = $this->repository->current();
		$cards = $this->cardRepository->all();
		if(!$this->orderIsReady($order)){
			return view('app.orders.cart', compact('order'));
		}
		return view('app.orders.card_create', compact(['order']));
	}

	public function selectCard(Request $request){
		$req = $request->all();
		$order = $this->repository->current();
		$card = $this->cardRepository->find($req['card_id']);
		if(!$card || $order->user_id != $card->user_id){
			return $this->payment();
		}
		$order->payment_method = "creditCard";
		$order->card_id = $card->id;
		$this->repository->update($order->toArray(), $order->id);
		$response = [
			'message' => 'Forma de pagamento atualizado',
			'data'    => $order->toArray(),
		];
		return response()->json($response);
	}

	public function payment(){
		$order = $this->repository->current();
		if(!$this->orderIsReady($order)){
			return view('app.orders.cart', compact('order'));
		}
		$cards = $this->cardRepository->all();
		$session = $this->getSession();
		$order->session = $session;
		$this->repository->update($order->toArray(), $order->id);
		return view('app.orders.payment', compact(['order', 'cards', 'session']));
	}

	public function storeAddress(Request $request){
		try {
			$order = $this->repository->current();
			if(!$this->orderIsReady($order)){
				return view('app.orders.cart', compact('order'));
			}
			$address = $request->all();
			$updateCost = $order->zipcode == $address['zipcode'] ? false : true;
			$order->zipcode = $address['zipcode'] ? $address['zipcode'] : '';
			$order->district = $address['district'] ? $address['district'] : '';
			$order->city = $address['city'] ? $address['city'] : '';
			$order->state = $address['state'] ? $address['state'] : '';
			$order->street = $address['street'] ? $address['street'] : '';
			$order->number = $address['number'] ? $address['number'] : '';
			$order->complement = $address['complement'] ? $address['complement'] : '';
			$this->repository->update($order->toArray(), $order->id);
			//if($updateCost){
				$this->updateDeliveryCost($order);
			//}
			$order = $this->repository->current();
			$response = [
				'message' => 'Endereço atualizado',
				'data'    => $order->toArray(),
			];
			return response()->json($response);
		} catch (ValidatorException $e) {
			return response()->json([
				'error'   => true,
				'message' => $e->getMessageBag()
			]);
		}
	}

	public function checkout(){
		$order = $this->repository->current();
		if(!$this->orderIsReady($order) || $order->payment_method == null){
			return view('app.orders.cart', compact('order'));
		}
		return view('app.orders.checkout', compact('order'));
	}

	public function success($id){
		$order = $this->repository->find($id);
		if($order->user_id != Auth::id()){
			return view('app.errors.permission');
		}
		return view('app.orders.success', compact('order'));
	}

	private function getSession(){
		$data = [
			'email' => PAGSEGURO_EMAIL,
			'token' => PAGSEGURO_TOKEN
		];
		$response = (new PagSeguro)->request(PagSeguro::SESSION_SANDBOX, $data);
		return $response->id;
	}

	public function getCheckoutData($order, $senderHash, $installments, $token){
		$base = []; $data = [];
		$cpType = strlen($order->client->cp) == 14 ? "CPF" : "CNPJ"; 
		$paymentMethod = $order->payment_method == PAYMENT_METHOD_BILLET ? "boleto" : "creditCard";

		foreach ($order->payment_groups as $key => $group) {
			$data[$key]['email'] = PAGSEGURO_EMAIL;
			$data[$key]['token'] = PAGSEGURO_TOKEN;
			$data[$key]['paymentMode'] = 'default';
			$data[$key]['paymentMethod'] = $paymentMethod;
			$data[$key]['currency'] = 'BRL';
			$data[$key]['receiverEmail'] = PAGSEGURO_EMAIL;
			$data[$key]['senderHash'] = $senderHash;
			$data[$key]['senderName'] = $order->client->name;
			$data[$key]['sender'.$cpType] = preg_replace('/\W/', '', $order->client->cp);
			$data[$key]['senderAreaCode'] = substr($order->client->phone1, 0, 2);
			$data[$key]['senderPhone'] = substr($order->client->phone1, 2, strlen($order->client->phone1));
			$data[$key]['senderEmail'] = 'c06040234054953856267@sandbox.pagseguro.com.br';
			$data[$key]['shippingAddressStreet'] = $order->street;
			$data[$key]['shippingAddressNumber'] = $order->number;
			$data[$key]['shippingAddressComplement'] = $order->complement ? $order->complement : "";
			$data[$key]['shippingAddressDistrict'] = $order->district;
			$data[$key]['shippingAddressPostalCode'] = $order->zipcode;
			$data[$key]['shippingAddressCity'] = $order->city;
			$data[$key]['shippingAddressState'] = $order->state;
			$data[$key]['shippingAddressCountry'] = 'BRA';
			$data[$key]['shippingType'] = '3';
			$shippingCost = 0.0;
			foreach ($group['items'] as $keyItem => $item) {
				$index = $keyItem+1;
				$shippingCost += $item->delivery_cost;
				$data[$key]['itemId'.$index] = $item->id;
				$data[$key]['itemDescription'.$index] = $item->product->name;
				$data[$key]['itemAmount'.$index] = number_format($item->value, 2, '.', '');
				$data[$key]['itemQuantity'.$index] = $item->quantity;
			}
			$data[$key]['shippingCost'] = number_format($shippingCost, 2, '.', '');
			if($order->payment_method == PAYMENT_METHOD_CREDIT_CARD){
				$insts = $installments[$key]['installments'];
				$value = $insts[$group['selected']-1]['installmentAmount'];
				$bdate = explode("-", $order->client->birthdate);
				$interest_free = explode('_', $key)[1];
				$data[$key]['creditCardToken'] = $token;
				$data[$key]['installmentQuantity'] = $group['selected'];
				$data[$key]['installmentValue'] = number_format($value, 2, '.', '');;
				$data[$key]['noInterestInstallmentQuantity'] = $interest_free;
				$data[$key]['creditCardHolderName'] = $order->client->name;
				$data[$key]['creditCardHolder'.$cpType] = preg_replace('/\W/', '', $order->client->cp);
				$data[$key]['creditCardHolderBirthDate'] = "$bdate[2]/$bdate[1]/$bdate[0]";
				$data[$key]['creditCardHolderAreaCode'] = substr($order->client->phone1, 0, 2);;
				$data[$key]['creditCardHolderPhone'] = substr($order->client->phone1, 2, strlen($order->client->phone1));;
				$data[$key]['billingAddressStreet'] =  $order->street;
				$data[$key]['billingAddressNumber'] =  $order->number;
				$data[$key]['billingAddressComplement'] =  $order->complement ? $order->complement : "";
				$data[$key]['billingAddressDistrict'] =  $order->district;
				$data[$key]['billingAddressPostalCode'] =  $order->zipcode;
				$data[$key]['billingAddressCity'] =  $order->city;
				$data[$key]['billingAddressState'] =  $order->state;
				$data[$key]['billingAddressCountry'] =  'BRA';
			}
			$data[$key]['reference'] = "ORDER_".$order->id."/".$key;
		}

		return $data;
		//$base['notificationURL'] = 'https://sualoja.com.br/notifica.html';
	}

	public function postCheckout(OrderCheckoutRequest $request){
		try {
			$order = $this->repository->current();
			if(!$this->orderIsReady($order)){
				return response()->json([
					'error'   => true,
					'message' => "Pedido não encontrado"
				]);
			}
			if(!$request->senderHash){
				return response()->json([
					'error'   => true,
					'message' => "Falha na conexão com o PagSeguro"
				]);
			}
			$installmentList = $request->installments;
			$data = $this->getCheckoutData($order, $request->senderHash, $installmentList, $request->token);
			try{
				foreach ($data as $key => $value) {
					$request = (new PagSeguro)->request(PagSeguro::CHECKOUT_SANDBOX, $value);
					$order->payment_link = $request->paymentLink ? $request->paymentLink : "";
					$brief = [];
					if($order->payment_method != PAYMENT_METHOD_CREDIT_CARD){
						$brief = ['free_1' => [
							'quantity' => 1, 
							'installment' => $order->total, 
							'total' => $order->total
						]];
					}else{	
						foreach ($order->payment_groups as $key => $group) {
							$insts = $installmentList[$key]['installments'];
							$value = $insts[$group['selected']-1]['installmentAmount'];
							$total = $insts[$group['selected']-1]['totalAmount'];
							$brief[$key] = [
								'quantity' => $group['selected'], 
								'installment' => $value, 
								'total' => $total];
						}
					}
					$order->payment_brief = json_encode($brief);
				}
				$order->status_id = STATUS_ORDER_AG_PAG;
				$order->created_at = date('Y-m-d H:i:s');
				$this->repository->update($order->toArray(), $order->id);
				$this->taskRepository->createStarterTask($order->id);
				$response = [
					'error'   => false,
					'message' => "Compra finalizada"
				];
				return response()->json($response);
			} catch (\Exception $e) {
				$response = [
					'error'   => true,
					'message' => $e->getMessage()
				];
				return response()->json($response);
			}
		} catch (ValidatorException $e) {
			return response()->json([
				'error'   => true,
				'message' => $e->getMessageBag()
			]);
		}
	}

	public function changeItemInstallment(Request $request){
		try {
			$order = $this->repository->current();
			if(!$this->orderIsReady($order)){
				return response()->json([
					'error'   => true,
					'message' => "Pedido não encontrado"
				]);
			}
			foreach ($request->ids as $id) {
				$item = null;
				foreach ($order->items as $key => $val) {
					if($val->id == $id){
						$item = $val;
					}
				}
				if($item == null){
					return response()->json([
						'error'   => true,
						'message' => "Item não encontrado"
					]);
				}
				$item->payment_installments = $request->quantity;
				$this->itemRepository->update($item->toArray(), $item->id);
			}
			return response()->json([
				'message' => 'Forma de entrega alterada'
			]);
			
		} catch (\Exception $e) {
			return response()->json([
				'error' => true,
				'message' => "Falha ao alterar forma de entrega"
			]);
		}
	}

	public function changeItemMethod(Request $request){
		try {
			$order = $this->repository->current();
			if(!$this->orderIsReady($order)){
				return response()->json([
					'error'   => true,
					'message' => "Pedido não encontrado"
				]);
			}
			foreach ($request->ids as $id) {
				$item = null;
				foreach ($order->items as $key => $val) {
					if($val->id == $id){
						$item = $val;
					}
				}
				if($item == null){
					return response()->json([
						'error'   => true,
						'message' => "Item não encontrado"
					]);
				}
				$methods = json_decode($item->delivery_methods, true);
				foreach ($methods as $key => $value) {
					if($value['codigo'] == $request->codigo){
						$item['delivery_cost'] = $value['valor'];
						$item['delivery_time'] = $value['prazo'];
						$item['delivery_form'] = $value['codigo'];
						$this->itemRepository->update($item->toArray(), $item->id);
					}
				}
			}
			return response()->json([
				'message' => 'Forma de entrega alterada'
			]);
			
		} catch (\Exception $e) {
			return response()->json([
				'error' => true,
				'message' => "Falha ao alterar forma de entrega"
			]);
		}
	}

	public function calcDeliveryCost(){
		try {
			$order = $this->repository->current();
			if(!$this->orderIsReady($order)){
				return response()->json([
					'error'   => true,
					'message' => "Pedido não encontrado"
				]);
			}

			return Correios::cep('95890000');
			
		} catch (\Exception $e) {
			return response()->json([
				'error' => true,
				'message' => "Falha ao calcular o frete"
			]);
		}
	}

	public function addItem($product_id){
		$order = $this->repository->current();
		$product = $this->productRepository->find($product_id);
		$data = [
			'order_id' => $order->id,
			'product_id' => $product->id,
			'payment_installments' => 1
		];
		$items = $this->itemRepository->findWhere($data);
		if(count($items) > 0){
			$item = $items[0];
			$item->quantity++;
			$item->value = $product[PermHelper::lowerValueText()];
			$result = $this->itemRepository->update($item->toArray(), $item->id);
		}else{
			$data['quantity'] = 1;
			$data['value'] = $product[PermHelper::lowerValueText()];
			$data['interest_free'] = $product->interest_free;
			$data['free_shipping'] = $product->free_shipping;
			$result = $this->itemRepository->create($data);
		}
		return $result;
	}

	public function removeItem($item_id){
		$item = $this->itemRepository->find($item_id);
		$order = $this->repository->find($item->order_id);
		if($order->user_id != Auth::id()){
			return response('Ação não permitida para este usuário.', 403);
		}else{
			$this->itemRepository->delete($item_id);
		}
		return $items;
	}

}
