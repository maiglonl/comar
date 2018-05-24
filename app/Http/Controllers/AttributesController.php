<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\AttributeCreateRequest;
use App\Http\Requests\AttributeUpdateRequest;
use App\Repositories\AttributeRepository;
use App\Validators\AttributeValidator;

/**
 * Class AttributesController.
 *
 * @package namespace App\Http\Controllers;
 */
class AttributesController extends Controller{

	protected $repository;
	protected $validator;

	public function __construct(AttributeRepository $repository, AttributeValidator $validator){
		$this->repository = $repository;
		$this->validator  = $validator;
	}

	/**
	 * Show the form for create resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($product_id) {
		return view('app.attributes.create', compact('product_id'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$attribute = $this->repository->find($id);
		return view('app.attributes.edit', compact('attribute'));
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  AttributeCreateRequest $request
	 * @return \Illuminate\Http\Response
	 * @throws \Prettus\Validator\Exceptions\ValidatorException
	 */
	public function store(AttributeCreateRequest $request){
		try {
			$this->validator->with($request->except('_token'))->passesOrFail(ValidatorInterface::RULE_CREATE);
			$product = $this->repository->create($request->except('_token'));
			$response = [
				'message' => 'Attribute created.',
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
	 * Update the specified resource in storage.
	 *
	 * @param  AttributeUpdateRequest $request
	 * @param  string            $id
	 * @return Response
	 * @throws \Prettus\Validator\Exceptions\ValidatorException
	 */
	public function update(AttributeUpdateRequest $request, $id){
		try {
			$this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
			$product = $this->repository->update($request->all(), $id);
			$response = [
				'message' => 'Attribute updated.',
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
			'message' => 'Attribute deleted.',
			'deleted' => $deleted,
		]);
	}

}
