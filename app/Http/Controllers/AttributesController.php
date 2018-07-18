<?php

namespace App\Http\Controllers;

use App\Repositories\AttributeRepository;

class AttributesController extends Controller{

	public function __construct(AttributeRepository $repository){
		$this->repository = $repository;
		$this->names = [
			'plural' => 'attributes',
			'singular' => 'attribute',
			'pt_plural' => 'atributos',
			'pt_singular' => 'atributo',
			'pt_gender' => 'o',
			'base_blades' => 'attributes'
		];
	}

	/**
	 * Disponible methods from Trait.
	 */
	use ControllerTrait {
		ControllerTrait::trait_edit as edit;
		ControllerTrait::trait_all as all;
		ControllerTrait::trait_store as store;
		ControllerTrait::trait_update as update;
		ControllerTrait::trait_destroy as destroy;
	}

	/**
	 * Show the form for create resource.
	 */
	public function create($product_id) {
		return view('app.attributes.create', compact('product_id'));
	}

}
