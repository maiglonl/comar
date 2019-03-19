<?php

namespace App\Http\Controllers;

use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use App\Helpers\DateHelper;
use Auth;

class UsersController extends Controller {

	/**
	 * UsersController constructor.
	 */
	public function __construct(OrderRepository $orderRepository, UserRepository $repository) {
		//$this->middleware('auth');
		$this->orderRepository = $orderRepository;
		$this->repository = $repository;
		$this->names = [
			'plural' => 'users',
			'singular' => 'user',
			'pt_plural' => 'usuários',
			'pt_singular' => 'usuário',
			'pt_gender' => 'o',
			'base_blades' => 'users'
		];
	}

	/**
	 * Disponible methods from Trait.
	 */
	use ControllerTrait {
		ControllerTrait::trait_edit as edit;
		ControllerTrait::trait_store as store;
		ControllerTrait::trait_update as update;
		ControllerTrait::trait_find as find;
	}

	/**
	 * Display a listing of the resource.
	 */
	public function index() {
		$this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
		if(Auth::user()->role == USER_ROLES_ADMIN){
			$users = $this->repository->all();
		}else{
			$users = $this->repository->findWhere(['parent_id' => Auth::user()->id]);
		}
		return view('app.users.index', compact('users'));
	}

	/**
	 * Display the specified resource.
	 */
	public function show($id) {
		$user = $this->repository->find($id);
		if(Auth::user()->role != USER_ROLES_ADMIN && $user->parent_id != Auth::user()->id){
			return view('app.errors.permission');
		}else{
			return view('app.users.show', compact('user'));
		}
		
	}

	/**
	 * Show the form for create resource.
	 */
	public function network($id = null) {
		$id = $id == null ? Auth::id() : $id;
		if(Auth::user()->role != USER_ROLES_ADMIN && $this->getNetworkPosition($id) < 0){
			return view('app.errors.permission');
		}
		$user = $this->repository->with(['childrens', 'parent'])->find($id);
		$user->sales = $this->getUserLastSales($id, 6);
		if($user->parent){
			$user->parent->sales = $this->getUserLastSales($user->parent->id);
		}
		foreach ($user->childrens as $key => $value) {
			$user->childrens[$key]->sales = $this->getUserLastSales($value->id);
		}
		return Auth::user() ? view('app.users.network', compact(['user'])) : view('auth.register', compact('users'));
	}

	/**
	 * Get the user sales by period.
	 */
	public function getUserLastSales($userId, $months = 1) {
		//DB::enableQueryLog();
		$months--;
		$dateStart = DateHelper::subDate(date('Y-m-01'), 'P'.$months.'M');
		$dateEnd = DateHelper::addDate(date('Y-m-01'), 'P1M');
		$sales = $this->orderRepository->findWhere([
			'user_id' => $userId,
			[ 'created_at', '<=', $dateEnd ],
			[ 'created_at', '>=', $dateStart ]
		]);
		$monthList = [];
		for ($i=$months; $i >= 0; $i--) {
			$monthList["'".DateHelper::subDate(date('Ym'), 'P'.$i.'M', 'Ym')."'"] = 0.0;
		}
		foreach ($sales as $key => $sale) {
			$date = date_create($sale->created_at);
			$monthList["'".$date->format('Ym')."'"] += $sale->total_items;
		}

		return $monthList;
	}

	/**
	 * Get the network position from user.
	 */
	public function getNetworkPosition($userId, $level = 0) {
		$user = $this->repository->find($userId);
		if($user->id == Auth::id()){
			return $level;
		}
		if($user->parent_id == null){
			return -1;
		}
		if($user->parent_id == Auth::id()){
			return $level+1;
		}else{
			return $this->getNetworkPosition($user->parent_id, $level+1);
		}
	}

	/**
	 * Show the form for create resource.
	 */
	public function create() {
		$users = $this->repository->all();
		return Auth::user() ? view('app.users.create', compact('users')) : view('auth.register', compact('users'));
	}

	/**
	 * Return list with all users.
	 */
	public function all(){
		if(Auth::user()->role != USER_ROLES_ADMIN){
			return $this->repository->findWhere(['parent_id' => Auth::user()->id]);
		}else{
			return $this->repository->all();
		}
	}
}
