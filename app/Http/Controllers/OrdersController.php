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
use App\Validators\OrderValidator;
use Auth;

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
	public function __construct(OrderRepository $repository, OrderValidator $validator)
	{
		$this->repository = $repository;
		$this->validator  = $validator;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
		$orders = $this->repository->all();

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
	public function store(OrderCreateRequest $request)
	{
		try {

			$this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

			$order = $this->repository->create($request->all());

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
	public function cart($id){
		$order = $this->repository->find($id);
		if($order->status_id != STATUS_ORDER_EM_ABERTO){
			return view('orders.show', compact('order'));
		}
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
		$order = $this->repository->find($id);
		if($order->status_id != STATUS_ORDER_EM_ABERTO){
			return view('orders.show', compact('order'));
		}
		return view('app.orders.checkout', compact('order'));
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
				'senderEmail' => 'comprador@uol.com.br',
				'senderPhone' => '997398991',
				'senderAreaCode' => '51',



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
				'senderEmail' => 'comprador@uol.com.br',
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
			];
			// number_format($product->value, 2, '.', '')
			$response = [
				'message' => 'Compra realizada com sucesso.',
				'data'    => '1'//$order->toArray(),
			];
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
		$order = $this->repository->find($id);
		return view('orders.show', compact('order'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$order = $this->repository->find($id);

		return view('orders.edit', compact('order'));
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
	public function update(OrderUpdateRequest $request, $id)
	{
		try {

			$this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

			$order = $this->repository->update($request->all(), $id);

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
	public function destroy($id)
	{
		$deleted = $this->repository->delete($id);

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
		$order = $this->repository->findWhere(['user_id' => Auth::id(), 'status_id' => STATUS_ORDER_EM_ABERTO])->first();
		if(!$order){
			$order = $this->repository->create(['user_id' => Auth::id(), 'status_id' => STATUS_ORDER_EM_ABERTO]);
		}

		return response()->json($order);
	}

}
