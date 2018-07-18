<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;

class CategoriesController extends Controller{

	public function __construct(CategoryRepository $repository){
		$this->repository = $repository;
		$this->names = [
			'plural' => 'categories',
			'singular' => 'category',
			'pt_plural' => 'categorias',
			'pt_singular' => 'categoria',
			'pt_gender' => 'a',
			'base_blades' => 'categoryes'
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
