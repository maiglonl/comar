<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\OrderCheckoutRequest;
use App\Repositories\OrderRepository;
use App\Repositories\ItemRepository;
use App\Repositories\ProductRepository;
use App\PagSeguro\PagSeguro;
use App\Helpers\PermHelper;
use \Correios;
/**
 * Class OrdersController.
 */
class OrdersController extends Controller{
	protected $repository;

	public function __construct( OrderRepository $repository, ItemRepository $itemRepository, ProductRepository $productRepository){
		$this->repository = $repository;
		$this->itemRepository = $itemRepository;
		$this->productRepository = $productRepository;

		$this->names = [
			'plural' => 'orders',
			'singular' => 'order',
			'pt_plural' => 'pedidos',
			'pt_singular' => 'pedido',
			'pt_gender' => 'o',
			'base_blades' => 'orders'
		];
	}

	/**
	 * Disponible methods from Trait.
	 */
	use ControllerTrait {
		ControllerTrait::trait_index as index;
		ControllerTrait::trait_show as show;
		ControllerTrait::trait_destroy as destroy;
		ControllerTrait::trait_find as find;
		ControllerTrait::trait_all as all;
	}

	/**
	 * Verify if order is ready
	 */
	public function orderIsReady($order){
		$isReady = $order && $order->status_id == STATUS_ORDER_EM_ABERTO && count($order->items) > 0;
		return $isReady;
	}

	/**
	 * Display the address form.
	 */
	public function formAddress(){
		$order = $this->repository->current();
		return view('app.orders.forms.address', compact('order'));
	}

	/**
	 * Display the specified resource.
	 */
	public function cart(){
		$order = $this->repository->current();
		if($this->orderIsReady($order)){
			$this->updateDeliveryCost($order);
			$order = $this->repository->current();
		}
		return view('app.orders.cart', compact('order'));
	}

	/**
	 * Calculate delivery cost
	 */
	public function updateDeliveryCost($order){
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
						'codigo' => $method['codigo'],
						'prazo' => $method['prazo'],
						'valor' => $method['valor']
					];
					if($method['valor'] <= $cost || $cost == null){
						$form = $method['codigo'];
						$time = $method['prazo'];
						$cost = $method['valor'];
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
	}

	/**
	 * Display the specified resource.
	 */
	public function delivery(){
		$deliveryMethods = [];
		$order = $this->repository->current();
		if(!$this->orderIsReady($order)){
			return view('app.orders.cart', compact('order'));
		}
		return view('app.orders.delivery', compact('order'));
	}

	/**
	 * Display the specified resource.
	 */
	public function billet(){
		$order = $this->repository->current();
		if(!$this->orderIsReady($order)){
			return view('app.orders.cart', compact('order'));
		}
		$order->payment_method = PAYMENT_METHOD_BILLET;
		foreach ($order->items as $item) {
			$item->payment_installments = 1;
			$item->payment_installment = $item->product[PermHelper::lowerValueText()];
			$this->itemRepository->update($item->toArray(), $item->id);
		}

		$this->repository->update($order->toArray(), $order->id);
		return redirect(route('orders.checkout'));
	}

	/**
	 * Display the specified resource.
	 */
	public function payment(){
		$order = $this->repository->current();
		if(!$this->orderIsReady($order)){
			return view('app.orders.cart', compact('order'));
		}
		return view('app.orders.payment', compact('order'));
	}

	/**
	 * Display the specified resource.
	 */
	public function card(){
		$order = $this->repository->current();
		if(!$this->orderIsReady($order)){
			return view('app.orders.cart', compact('order'));
		}
		return view('app.orders.card', compact('order'));
	}

	/**
	 * Update Address data.
	 */
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
			if($updateCost){
				$this->updateDeliveryCost($order);
			}
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

	/**
	 * Display the specified resource.
	 */
	public function checkout(){
		$order = $this->repository->current();
		if(!$this->orderIsReady($order) || $order->payment_method == null){
			return view('app.orders.cart', compact('order'));
		}
		$session = $this->getSession();
		return view('app.orders.checkout', compact('order', 'session'));
	}

	/**
	 * Display the specified resource.
	 */
	private function getSession(){
		$data = [
			'email' => PAGSEGURO_EMAIL,
			'token' => PAGSEGURO_TOKEN
		];
		$response = (new PagSeguro)->request(PagSeguro::SESSION_SANDBOX, $data);
		$session = new \SimpleXMLElement($response->getContents());
		return $session->id;
	}

	/**
	 * Return formatted data to checkout request
	 */
	public function getCheckoutData($order, $senderHash){
		$base = []; $data = [];
		$cpType = strlen($order->client->cp) == 14 ? "CPF" : "CNPJ"; 
		$paymentMethod = $order->payment_method == PAYMENT_METHOD_BILLET ? "boleto" : "creditCard";

		$base['email'] = PAGSEGURO_EMAIL;
		$base['token'] = PAGSEGURO_TOKEN;
		$base['paymentMode'] = 'default';
		$base['paymentMethod'] = $paymentMethod;
		$base['currency'] = 'BRL';
		$base['receiverEmail'] = PAGSEGURO_EMAIL;
		$base['senderHash'] = $senderHash;
		$base['senderName'] = $order->client->name;
		$base['sender'.$cpType] = preg_replace('/\W/', '', $order->client->cp);
		$base['senderAreaCode'] = substr($order->client->phone1, 0, 2);;
		$base['senderPhone'] = substr($order->client->phone1, 2, strlen($order->client->phone1));
		$base['senderEmail'] = 'c06040234054953856267@sandbox.pagseguro.com.br';
		$base['shippingAddressStreet'] = $order->street;
		$base['shippingAddressNumber'] = $order->number;
		$base['shippingAddressComplement'] = $order->complement ? $order->complement : "";
		$base['shippingAddressDistrict'] = $order->district;
		$base['shippingAddressPostalCode'] = $order->zipcode;
		$base['shippingAddressCity'] = $order->city;
		$base['shippingAddressState'] = $order->state;
		$base['shippingAddressCountry'] = 'BRA';
		$base['shippingType'] = '3';
		$base['shippingCost'] = number_format($order->total_delivery, 2, '.', '');
		foreach ($order->items as $key => $item) {
			$index = $key+1;
			$base['itemId'.$index] = $item->id;
			$base['itemDescription'.$index] = $item->product->name;
			$base['itemAmount'.$index] = number_format($item->value, 2, '.', '');
			$base['itemQuantity'.$index] = $item->amount;
		}
		return $base;
		//$base['notificationURL'] = 'https://sualoja.com.br/notifica.html';
		//$base['reference'] = 'REF1234';

		// Boleto

		// CC

		/*
		$data['creditCardToken'] = '4as56d4a56d456as456dsa';
		$data['installmentQuantity'] = '5';
		$data['installmentValue'] = '125.22';
		$data['noInterestInstallmentQuantity'] = '2';
		$data['creditCardHolderName'] = 'Jose Comprador';
		$data['creditCardHolderCPF'] = '22111944785';
		$data['creditCardHolderBirthDate'] = '27/10/1987';
		$data['creditCardHolderAreaCode'] = '11';
		$data['creditCardHolderPhone'] = '56273440';
		$data['billingAddressStreet'] = 'Av. Brig. Faria Lima';
		$data['billingAddressNumber'] = '1384';
		$data['billingAddressComplement'] = '5o andar';
		$data['billingAddressDistrict'] = 'Jardim Paulistano';
		$data['billingAddressPostalCode'] = '01452002';
		$data['billingAddressCity'] = 'Sao Paulo';
		$data['billingAddressState'] = 'SP';
		$data['billingAddressCountry'] = 'BRA';
		 */
		
	}

	/**
	 * Update the specified resource in storage.
	 */
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
			$data = $this->getCheckoutData($order, $request->senderHash);
			try{
				$response = (new PagSeguro)->request(PagSeguro::CHECKOUT_SANDBOX, $data);
				dd($response);
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

	/**
	 * Change delivery form from item.
	 */
	public function changeItemMethod(Request $request){
		try {
			$order = $this->repository->current();
			if(!$this->orderIsReady($order)){
				return response()->json([
					'error'   => true,
					'message' => "Pedido não encontrado"
				]);
			}
			$item = null;
			foreach ($order->items as $key => $val) {
				if($val->id == $request->id){
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
					return response()->json([
						'message' => 'Forma de entrega alterada'
					]);
				}
			}
			return response()->json([
				'error'   => true,
				'message' => "Forma de entrega inválida"
			]);
			
		} catch (\Exception $e) {
			dd($e);
			return response()->json([
				'error' => true,
				'message' => $e
			]);
		}
	}

	/**
	 * Update the specified resource in storage.
	 */
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

	/**
	 * Add Item to current order.
	 */
	public function addItem($product_id){
		$order = $this->repository->current();
		$product = $this->productRepository->find($product_id);
		$data = [
			'order_id' => $order->id,
			'product_id' => $product->id
		];
		$items = $this->itemRepository->findWhere($data);
		if(count($items) > 0){
			$item = $items[0];
			$item->amount++;
			$item->value = $product[PermHelper::lowerValueText()];
			$result = $this->itemRepository->update($item->toArray(), $item->id);
		}else{
			$data['amount'] = 1;
			$data['value'] = $product[PermHelper::lowerValueText()];
			$data['interest_free'] = $product->interest_free;
			$data['free_shipping'] = $product->free_shipping;
			$result = $this->itemRepository->create($data);
		}
		return $result;
	}

	/**
	 * Remove Item from current order.
	 */
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
