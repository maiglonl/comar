<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\BillRepository;
use App\Repositories\TaskRepository;
use Auth;

class HomeController extends Controller {
	/**
	 * Create a new controller instance.
	 */
	public function __construct(
		BillRepository $billRepository,
		TaskRepository $taskRepository
	){
		$this->middleware('auth');
		$this->billRepository = $billRepository;
		$this->taskRepository = $taskRepository;
	}

	/**
	 * Show the static dashboard.
	 */
	public function index(){
		return view('home');
	}

	/**
	 * Show the app dashboard.
	 */
	public function appIndex(){
		if(Auth::user()->role == USER_ROLES_PARTNER){
			return redirect('orders/list');
		}
		$toPayAll = $this->taskRepository->findWhere([
			"stage_id" => 1,
			"date_conclusion" => null
		]);
		$toPay = [];
		foreach ($toPayAll as $key => $value) {
			if($value->order['user_id'] == Auth::id()){
				$toPay[] = $value;
			}
		}
		$toReceive = $this->billRepository->findWhere([
			"user_id" => Auth::id(),
			"type" => "debit",
			"done" => 0
		]);
		$received = $this->billRepository->findWhere([
			"user_id" => Auth::id(),
			"type" => "debit",
			"done" => 1
		]);
		return view('app.home', ['toPay' => $toPay, 'toReceive' => $toReceive, 'received' => $received]);	
	}
}
