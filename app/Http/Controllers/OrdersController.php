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
	 * Display the specified resource.
	 */
	public function cart(){
		$order = $this->repository->current();
		return view('app.orders.cart', compact('order'));
	}

	/**
	 * Display the specified resource.
	 */
	public function checkout($id){
		$data = [
			'email' => 'maiglonl@gmail.com',
			'token' => 'AA06F28B1DBB4CB3939D6BE9FF9E5FB0'
		];
		$response = (new PagSeguro)->request(PagSeguro::SESSION_SANDBOX, $data);
		$order = $this->repository->find($id);
		if($order->status_id != STATUS_ORDER_EM_ABERTO){
			return view('orders.show', compact('order'));
		}

		$session = new \SimpleXMLElement($response->getContents());
		$session = $session->id;

		$amount = number_format(24301, 2, '.', '');

		return view('app.orders.checkout', compact('order', 'session', 'amount'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function postCheckout(OrderCheckoutRequest $request){
		try {
			//$this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
			//$order = $this->repository->update($request->all(), $id);
			$data = [
				'email' => 'maiglonl@gmail.com',
				'token' => 'AA06F28B1DBB4CB3939D6BE9FF9E5FB0',
				'paymentMode' => 'default',
				'paymentMethod' => 'creditCard',
				'receiverEmail' => 'maiglonl@gmail.com',
				'currency' => 'BRL',
				'senderName' => 'Maiglon A Lubacheuski',
				'senderCPF' => '02557961027',
				'senderEmail' => 'comprador@sandbox.pagseguro.com.br',
				'senderPhone' => '997398991',
				'senderAreaCode' => '51',
				'installmentValue' => number_format($data['installmentValue'], 2, '.', ''),
				'shippingAddressCountry' => 'BRA',
				'billingAddressCountry' => 'BRA',

				/*
				'paymentMode' => 'default',
				'paymentMethod' => 'creditCard',
				'receiverEmail' => 'suporte@lojamodelo.com.br',
				'currency' => 'BRL',
				'extraAmount' => '1.00',
				'itemId1' => '0001',
				'itemDescription1' => 'Notebook Prata',
				'itemAmount1' => '24300.00',
				'itemQuantity1' => '1',
				'notificationURL' => 'https://sualoja.com.br/notifica.html',
				'reference' => 'REF1234',
				'senderName' => 'Jose Comprador',
				'senderCPF' => '22111944785',
				'senderAreaCode' => '11',
				'senderPhone' => '56273440',
				'senderEmail' => 'comprador@sandbox.pagseguro.com.br',
				'senderHash' => 'abc123',
				'shippingAddressStreet' => 'Av. Brig. Faria Lima',
				'shippingAddressNumber' => '1384',
				'shippingAddressComplement' => '5o andar',
				'shippingAddressDistrict' => 'Jardim Paulistano',
				'shippingAddressPostalCode' => '01452002',
				'shippingAddressCity' => 'Sao Paulo',
				'shippingAddressState' => 'SP',
				'shippingAddressCountry' => 'BRA',
				'shippingType' => '1', // [1 => Encomenda normal (PAC), 2 => SEDEX, 3 => Não expecificado (Entregadoras)]
				'shippingCost' => '1.00',
				'creditCardToken' => '4as56d4a56d456as456dsa',
				'installmentQuantity' => '5',
				'installmentValue' => '125.22',
				'noInterestInstallmentQuantity' => '2',
				'creditCardHolderName' => 'Jose Comprador',
				'creditCardHolderCPF' => '22111944785',
				'creditCardHolderBirthDate' => '27/10/1987',
				'creditCardHolderAreaCode' => '11',
				'creditCardHolderPhone' => '56273440',
				'billingAddressStreet' => 'Av. Brig. Faria Lima',
				'billingAddressNumber' => '1384',
				'billingAddressComplement' => '5o andar',
				'billingAddressDistrict' => 'Jardim Paulistano',
				'billingAddressPostalCode' => '01452002',
				'billingAddressCity' => 'Sao Paulo',
				'billingAddressState' => 'SP',
				'billingAddressCountry' => 'BRA',
				*/
			];
			// number_format($product->value, 2, '.', '')
			
			try{
				$response = (new PagSeguro)->request(PagSeguro::CHECKOUT_SANDBOX, $data);
			} catch (\Exception $e) {
				dd($e->getMessage());
			}
			return response()->json($response);
		} catch (ValidatorException $e) {
			if ($request->wantsJson()) {
				return response()->json([
					'error'   => true,
					'message' => $e->getMessageBag()
				]);
			}
			return redirect()->back()->withErrors($e->getMessageBag())->withInput();
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
			$item->value = $product[\App\Helpers\PermHelper::lowerValueText()];
			$result = $this->itemRepository->update($item->toArray(), $item->id);
		}else{
			$data['amount'] = 1;
			$data['value'] = $product[\App\Helpers\PermHelper::lowerValueText()];
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
