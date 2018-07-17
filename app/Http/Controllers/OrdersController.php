<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\OrderCreateRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Requests\OrderCheckoutRequest;
use App\Repositories\OrderRepository;
use App\Repositories\ItemRepository;
use App\Repositories\ProductRepository;
use App\Validators\OrderValidator;
use Auth;
use App\PagSeguro\PagSeguro;

/**
 * Class OrdersController.
 *
 * @package namespace App\Http\Controllers;
 */
class OrdersController extends Controller
{
	/**
	 * @var OrderRepository
	 */
	protected $repository;

	/**
	 * @var OrderValidator
	 */
	protected $validator;

	/**
	 * OrdersController constructor.
	 *
	 * @param OrderRepository $repository
	 * @param OrderValidator $validator
	 */
	public function __construct(
		OrderRepository $orderRepository, 
		ItemRepository $itemRepository, 
		ProductRepository $productRepository, 
		OrderValidator $validator)
	{
		$this->itemRepository = $itemRepository;
		$this->productRepository = $productRepository;
		$this->orderRepository = $orderRepository;
		$this->validator = $validator;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(){
		$this->orderRepository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
		$orders = $this->orderRepository->all();

		if (request()->wantsJson()) {
			return response()->json([
				'data' => $orders,
			]);
		}

		return view('orders.index', compact('orders'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  OrderCreateRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 *
	 * @throws \Prettus\Validator\Exceptions\ValidatorException
	 */
	public function store(OrderCreateRequest $request){
		try {

			$this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

			$order = $this->orderRepository->create($request->all());

			$response = [
				'message' => 'Order created.',
				'data'    => $order->toArray(),
			];

			if ($request->wantsJson()) {

				return response()->json($response);
			}

			return redirect()->back()->with('message', $response['message']);
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
	 * Display the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function cart(){
		$order = $this->current();
		return view('app.orders.cart', compact('order'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function checkout($id){
		$data = [
			'email' => 'maiglonl@gmail.com',
			'token' => 'AA06F28B1DBB4CB3939D6BE9FF9E5FB0'
		];
		$response = (new PagSeguro)->request(PagSeguro::SESSION_SANDBOX, $data);
		$order = $this->orderRepository->find($id);
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
	 *
	 * @param  OrderCheckoutRequest $request
	 *
	 * @return Response
	 *
	 * @throws \Prettus\Validator\Exceptions\ValidatorException
	 */
	public function postCheckout(OrderCheckoutRequest $request){
		try {
			//$this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
			//$order = $this->orderRepository->update($request->all(), $id);
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
	 * Display the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($id){
		$order = $this->orderRepository->find($id);
		return view('orders.show', compact('order'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id){
		$order = $this->orderRepository->find($id);
		return view('orders.edit', compact('order'));
	}

	/**
	 * Add Item to current order.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function addItem($product_id){
		$order = $this->current();
		$product = $this->productRepository->find($product_id);
		$data = [
			'order_id' => $order->id,
			'product_id' => $product->id
		];
		$items = $this->itemRepository->findWhere($data);
		error_log(\App\Helpers\PermHelper::lowerValueText());
		error_log($product[\App\Helpers\PermHelper::lowerValueText()]);
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
	 * Add Item to current order.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function removeItem($item_id){
		$item = $this->itemRepository->find($item_id);
		$order = $this->orderRepository->find($item->order_id);
		if($order->user_id != Auth::id()){
			return response('Ação não permitida para este usuário.', 403);
		}else{
			$this->itemRepository->delete($item_id);
		}
		return $items;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  OrderUpdateRequest $request
	 * @param  string            $id
	 *
	 * @return Response
	 *
	 * @throws \Prettus\Validator\Exceptions\ValidatorException
	 */
	public function update(OrderUpdateRequest $request, $id){
		try {

			$this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

			$order = $this->orderRepository->update($request->all(), $id);

			$response = [
				'message' => 'Order updated.',
				'data'    => $order->toArray(),
			];

			if ($request->wantsJson()) {

				return response()->json($response);
			}

			return redirect()->back()->with('message', $response['message']);
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
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id){
		$deleted = $this->orderRepository->delete($id);

		if (request()->wantsJson()) {

			return response()->json([
				'message' => 'Order deleted.',
				'deleted' => $deleted,
			]);
		}

		return redirect()->back()->with('message', 'Order deleted.');
	}

	/**
	 * Return current open order or create a new.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function current(){
		$order = $this->orderRepository->findWhere(['user_id' => Auth::id(), 'status_id' => STATUS_ORDER_EM_ABERTO])->first();
		if(!$order){
			$order = $this->orderRepository->create(['user_id' => Auth::id(), 'status_id' => STATUS_ORDER_EM_ABERTO]);
		}
		return $order;
	}

}
