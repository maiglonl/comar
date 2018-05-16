<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Repositories\ProductRepository;
use App\Validators\ProductValidator;

/**
 * Class ProductsController.
 *
 * @package namespace App\Http\Controllers;
 */
class ProductsController extends Controller {
	/**
	 * @var ProductRepository
	 */
	protected $repository;

	/**
	 * @var ProductValidator
	 */
	protected $validator;

	/**
	 * ProductsController constructor.
	 *
	 * @param ProductRepository $repository
	 * @param ProductValidator $validator
	 */
	public function __construct(ProductRepository $repository, ProductValidator $validator) {
		$this->repository = $repository;
		$this->validator  = $validator;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
		$products = $this->repository->all();

		if (request()->wantsJson()) {
			return response()->json([
				'data' => $products,
			]);
		}

		return view('app.products.index', compact('products'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  ProductCreateRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 *
	 * @throws \Prettus\Validator\Exceptions\ValidatorException
	 */
	public function store(ProductCreateRequest $request) {
		try {
			$this->validator->with($request->except('_token'))->passesOrFail(ValidatorInterface::RULE_CREATE);
			$product = $this->repository->create($request->except('_token'));
			$response = [
				'message' => 'Product created.',
				'data'    => $product->toArray(),
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
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		$product = $this->repository->find($id);
		return view('app.products.show', compact('product'));
	}

	/**
	 * Show the form for create resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		return view('app.products.create', compact('product'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$product = $this->repository->find($id);
		return view('app.products.edit', compact('product'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  ProductUpdateRequest $request
	 * @param  string            $id
	 *
	 * @return Response
	 *
	 * @throws \Prettus\Validator\Exceptions\ValidatorException
	 */
	public function update(ProductUpdateRequest $request, $id) {
		try {
			$this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
			$product = $this->repository->update($request->all(), $id);
			$response = [
				'message' => 'Product updated.',
				'data'    => $product->toArray(),
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
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$deleted = $this->repository->delete($id);
		return response()->json([
			'message' => 'Product deleted.',
			'deleted' => $deleted,
		]);
	}

	/**
	 * Return the specified product.
	 *
	 * @param  int $id
	 */
	public function find($id){
		return $this->repository->find($id);
	}

	/**
	 * Return list with all products.
	 */
	public function all(){
		return $this->repository->all();
	}
}
