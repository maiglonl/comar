<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\ProductUpdateRequest;
use App\Repositories\ProductRepository;

class ProductController extends Controller{

	private $repository;

	public function __construct(ProductRepository $repository){
		$this->repository = $repository;
	}

	public function home(){
		return view('app.product.home');
	}

	public function listProducts(){
		return view('app.product.list', ['products' => $this->repository->all()]);
	}
}
