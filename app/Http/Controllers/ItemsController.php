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
	 * Increase the Item quantity in 1.
	 */
	public function increase($id){
		$item = $this->repository->find($id);
		$item->quantity++;
		$item = $this->repository->update($item->toArray(), $id);
		return response()->json([
			'data' => $item,
			'message' => "Quantidade do item atualizada"
		]);
	}

	/**
	 * Decrease the Item quantity in 1.
	 */
	public function decrease($id){
		$item = $this->repository->find($id);
		if($item->quantity <= 1){
			return response()->json([
				'error' => true,
				'message' => "Quantidade mínima atingida"
			]);
		}
		$item->quantity--;
		$item = $this->repository->update($item->toArray(), $id);
		return response()->json([
			'data' => $item,
			'message' => "Quantidade do item atualizada"
		]);
	}
}
