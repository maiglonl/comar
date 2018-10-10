<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Auth;

class UsersController extends Controller {

	/**
	 * UsersController constructor.
	 */
	public function __construct(UserRepository $repository) {
		$this->middleware('auth');
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
	public function network() {
		$users = $this->repository->findWhere(['parent_id' => Auth::id()]);
		$teste = $this->repository->with(['parent'])->findWhere(['id' => 1]);
		return Auth::user() ? view('app.users.network', compact('users')) : view('auth.register', compact('users'));
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
