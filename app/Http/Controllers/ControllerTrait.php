<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class CategoriesController.
 *
 * @package namespace App\Http\Controllers;
 */
trait ControllerTrait{

	/**
	 * Display a listing of the resource.
	 */
	public function trait_index() {
		$resources = $this->repository->all();
		return view('app.'.$this->names['base_blades'].'.index', [$this->names['plural'] => $resources]);
	}

	/**
	 * Display the description of the resource.
	 */
	public function trait_show($id) {
		$resource = $this->repository->find($id);
		return view('app.'.$this->names['base_blades'].'.show', [$this->names['singular'] => $resource]);
	}

	/**
	 * Display the description of the resource.
	 */
	public function trait_desc($id) {
		$resource = $this->repository->find($id);
		return view('app.'.$this->names['base_blades'].'.desc', [$this->names['singular'] => $resource]);
	}

	/**
	 * Show the form for create resource.
	 */
	public function trait_create() {
		return view('app.'.$this->names['base_blades'].'.create');
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function trait_edit($id) {
		$model = $this->repository->find($id);
		return view('app.'.$this->names['base_blades'].'.edit', [$this->names['singular'] => $model]);
	}

	/**
	 * Return the resource.
	 */
	public function trait_find($id){
		return $this->repository->find($id);
	}

	/**
	 * Return list with all resources.
	 */
	public function trait_all(){
		return $this->repository->all();
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function trait_store(Request $request){
		try {
			$entity = $this->repository->create($request->except('_token'));
			$response = [
				'message' => ucfirst($this->names['pt_singular']).' criad'.$this->names['pt_gender'],
				'data'    => $entity->toArray(),
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
	 */
	public function trait_update(Request $request, $id){
		try {
			$entity = $this->repository->update($request->all(), $id);
			$response = [
				'message' => ucfirst($this->names['pt_singular']).' atualizad'.$this->names['pt_gender'],
				'data'    => $entity->toArray(),
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
	 */
	public function trait_destroy($id) {
		$deleted = $this->repository->delete($id);
		return response()->json([
			'message' => ucfirst($this->names['pt_singular']).' removid'.$this->names['pt_gender'],
			'data' => $deleted,
		]);
	}
}
