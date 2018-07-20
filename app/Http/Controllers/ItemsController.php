<?php

namespace App\Http\Controllers;

use App\Repositories\ItemRepository;

class ItemsController extends Controller{
	
	public function __construct(ItemRepository $repository){
		$this->repository = $repository;
		$this->names = [
			'plural' => 'items',
			'singular' => 'item',
			'pt_plural' => 'itens',
			'pt_singular' => 'item',
			'pt_gender' => 'o',
			'base_blades' => 'items'
		];
	}

	/**
	 * Disponible methods from Trait.
	 */
	use ControllerTrait {
		ControllerTrait::trait_find as find;
		ControllerTrait::trait_store as store;
		ControllerTrait::trait_update as update;
		ControllerTrait::trait_destroy as destroy;
	}

	/**
	 * Increase the Item amount in 1.
	 */
	public function increase($id){
		$item = $this->repository->find($id);
		$item->amount++;
		$item = $this->repository->update($item->toArray(), $id);
		return response()->json([
			'data' => $item,
			'message' => "Quantidade do item atualizada"
		]);
	}

	/**
	 * Decrease the Item amount in 1.
	 */
	public function decrease($id){
		$item = $this->repository->find($id);
		if($item->amount <= 1){
			return response()->json([
				'error' => true,
				'message' => "Quantidade mínima atingida"
			]);
		}
		$item->amount--;
		$item = $this->repository->update($item->toArray(), $id);
		return response()->json([
			'data' => $item,
			'message' => "Quantidade do item atualizada"
		]);
	}
}
