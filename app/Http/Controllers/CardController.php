<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;

class CardsController extends Controller{

	public function __construct(CategoryRepository $repository){
		$this->repository = $repository;
		$this->names = [
			'plural' => 'cards',
			'singular' => 'card',
			'pt_plural' => 'cartões',
			'pt_singular' => 'cartão',
			'pt_gender' => 'o',
			'base_blades' => 'cards'
		];
	}

	/**
	 * Disponible methods from Trait.
	 */
	use ControllerTrait {
		ControllerTrait::trait_create as create;
		ControllerTrait::trait_edit as edit;
		ControllerTrait::trait_all as all;
		ControllerTrait::trait_store as store;
		ControllerTrait::trait_update as update;
	}

}
