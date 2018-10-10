<?php

namespace App\Http\Controllers;

use App\Repositories\BillRepository;

class BillsController extends Controller{

    public function __construct(BillRepository $repository){
        $this->repository = $repository;
        $this->names = [
            'plural' => 'bills',
            'singular' => 'bill',
            'pt_plural' => 'contas',
            'pt_singular' => 'conta',
            'pt_gender' => 'a',
            'base_blades' => 'bills'
        ];
    }

    /**
     * Disponible methods from Trait.
     */
    use ControllerTrait {
        ControllerTrait::trait_all as all;
    }

	public function allOpenCredit(){
		return $this->repository->findWhere(['done' => false, 'type' => 'credit']);
	}

	public function allOpenDebit(){
		return $this->repository->findWhere(['done' => false, 'type' => 'debit']);
	}

}
