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

	public function __construct(
		OrderRepository $repository, 
		ItemRepository $itemRepository, 
		ProductRepository $productRepository)
	{
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
		return $order && $order->status_id == STATUS_ORDER_EM_ABERTO && count($order->items) > 0;
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
			$orderItem['delivery_form'] = null;
			$orderItem['delivery_time'] = null;
			$orderItem['delivery_cost'] = null;
			foreach ($deliveryMethods as $method) {
				if($method['erro']['codigo'] == 0){
					$validMethods[] = [
						'codigo' => $method['codigo'],
						'prazo' => $method['prazo'],
						'valor' => $method['valor']
					];
					if($method['valor'] <= $orderItem['delivery_cost'] || $orderItem['delivery_cost'] == null){
						$orderItem['delivery_form'] = $method['codigo'];
						$orderItem['delivery_time'] = $method['prazo'];
						$orderItem['delivery_cost'] = $orderItem['free_shipping'] ? 0 : $method['valor'];
					}
				}
			}
			// Adiciona opção de 'Retirada em loja'
			$validMethods[] = [
				'codigo' => 0,
				'prazo' => 0,
				'valor' => 0
			];
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
			$order->zipcode = $address['zipcode'] ? $address['zipcode'] : '';
			$order->district = $address['district'] ? $address['district'] : '';
			$order->city = $address['city'] ? $address['city'] : '';
			$order->state = $address['state'] ? $address['state'] : '';
			$order->street = $address['street'] ? $address['street'] : '';
			$order->number = $address['number'] ? $address['number'] : '';
			$order->complement = $address['complement'] ? $address['complement'] : '';
			$this->repository->update($order->toArray(), $order->id);
			$this->updateDeliveryCost($order);
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
		if(!$this->orderIsReady($order)){
			return view('app.orders.cart', compact('order'));
		}
		$data = [
			'email' => 'maiglonl@gmail.com',
			'token' => 'AA06F28B1DBB4CB3939D6BE9FF9E5FB0'
		];
		$response = (new PagSeguro)->request(PagSeguro::SESSION_SANDBOX, $data);

		$session = new \SimpleXMLElement($response->getContents());
		$session = $session->id;

		$amount = number_format(24301, 2, '.', '');

		return view('app.orders.checkout', compact('order', 'session', 'amount'));
	}

	/**
	 * Return formatted data to checkout request
	 */
	public function getCheckoutData($order){
		$data = [];

		$data['email'] = 'maiglonl@gmail.com';
		$data['token'] = 'AA06F28B1DBB4CB3939D6BE9FF9E5FB0';
		$data['paymentMode'] = 'default';
		
		$data['receiverEmail'] = 'maiglonl@gmail.com';
		$data['paymentMethod'] = 'creditCard';
		$data['currency'] = 'BRL';
		$data['senderName'] = 'Maiglon A Lubacheuski';
		$data['senderCPF'] = '02557961027';
		$data['senderEmail'] = 'comprador@sandbox.pagseguro.com.br';
		$data['senderPhone'] = '997398991';
		$data['senderAreaCode'] = '51';
		$data['installmentValue'] = number_format($data['installmentValue'], 2, '.', '');
		$data['shippingAddressCountry'] = 'BRA';
		$data['billingAddressCountry'] = 'BRA';


		$data['paymentMode'] = 'default';
		$data['paymentMethod'] = 'boleto';
		$data['receiverEmail'] = 'maiglonl@gmail.com';
		$data['currency'] = 'BRL';
		$data['extraAmount'] = '0.00';
		$data['itemId1'] = '0001';
		$data['itemDescription1'] = 'Notebook Prata';
		$data['itemAmount1'] = '24300.00';
		$data['itemQuantity1'] = '1';
		$data['notificationURL'] = 'https://sualoja.com.br/notifica.html';
		$data['reference'] = 'REF1234';
		$data['senderName'] = 'Jose Comprador';
		$data['senderCPF'] = '22111944785';
		$data['senderAreaCode'] = '11';
		$data['senderPhone'] = '56273440';
		$data['senderEmail'] = 'comprador@uol.com.br';
		$data['senderHash'] = 'abc123';
		$data['shippingAddressStreet'] = 'Av. Brig. Faria Lima';
		$data['shippingAddressNumber'] = '1384';
		$data['shippingAddressComplement'] = '5o andar';
		$data['shippingAddressDistrict'] = 'Jardim Paulistano';
		$data['shippingAddressPostalCode'] = '01452002';
		$data['shippingAddressCity'] = 'Sao Paulo';
		$data['shippingAddressState'] = 'SP';
		$data['shippingAddressCountry'] = 'BRA';
		$data['shippingType'] = '1';
		$data['shippingCost'] = '1.00';

		/*
		$data['paymentMode'] = 'default';
		$data['paymentMethod'] = 'creditCard';
		$data['receiverEmail'] = 'maiglonl@gmail.com';
		$data['currency'] = 'BRL';
		$data['extraAmount'] = '1.00';
		$data['itemId1'] = '0001';
		$data['itemDescription1'] = 'Notebook Prata';
		$data['itemAmount1'] = '24300.00';
		$data['itemQuantity1'] = '1';
		$data['notificationURL'] = 'https://sualoja.com.br/notifica.html';
		$data['reference'] = 'REF1234';
		$data['senderName'] = 'Jose Comprador';
		$data['senderCPF'] = '22111944785';
		$data['senderAreaCode'] = '11';
		$data['senderPhone'] = '56273440';
		$data['senderEmail'] = 'comprador@sandbox.pagseguro.com.br';
		$data['senderHash'] = 'abc123';
		$data['shippingAddressStreet'] = 'Av. Brig. Faria Lima';
		$data['shippingAddressNumber'] = '1384';
		$data['shippingAddressComplement'] = '5o andar';
		$data['shippingAddressDistrict'] = 'Jardim Paulistano';
		$data['shippingAddressPostalCode'] = '01452002';
		$data['shippingAddressCity'] = 'Sao Paulo';
		$data['shippingAddressState'] = 'SP';
		$data['shippingAddressCountry'] = 'BRA';
		$data['shippingType'] = '1'; // [1 => Encomenda normal (PAC), 2 => SEDEX, 3 => Não expecificado (Entregadoras);
		$data['shippingCost'] = '1.00';
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
			$data = $this->getCheckoutData($order);
			try{
				$response = (new PagSeguro)->request(PagSeguro::CHECKOUT_SANDBOX, $data);
			} catch (\Exception $e) {
				//dd($e->getMessage());
			}
			return response()->json($response);
		} catch (ValidatorException $e) {
			return response()->json([
				'error'   => true,
				'message' => $e->getMessageBag()
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
