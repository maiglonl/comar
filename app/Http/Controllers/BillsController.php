<?php

namespace App\Http\Controllers;

use App\Repositories\BillRepository;
use Auth;

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

    public function finishBill($id){
        $bill = $this->repository->find($id);
        $bill->done = 1;
        $bill->user_id = Auth::id();
        return $this->repository->update($bill->toArray(), $bill->id);
    }

	public function allOpenCredit(){
		return $this->repository->findWhere(['done' => false, 'type' => 'credit']);
	}

	public function allOpenDebit(){
		return $this->repository->findWhere(['done' => false, 'type' => 'debit']);
	}

}
