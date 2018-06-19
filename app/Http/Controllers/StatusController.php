<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Repositories\StatusRepository;

/**
 * Class StatusController.
 *
 * @package namespace App\Http\Controllers;
 */
class StatusController extends Controller{

    protected $repository;

    public function __construct(StatusRepository $repository){
        $this->repository = $repository;
    }

	/**
	 * Return list with all products.
	 */
	public function all(){
		return $this->repository->all();
	}

}
