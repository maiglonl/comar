<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use Auth;

/**
 * Class UsersController.
 *
 * @package namespace App\Http\Controllers;
 */
class UsersController extends Controller {
	/**
	 * @var UserRepository
	 */
	protected $repository;

	/**
	 * @var UserValidator
	 */
	protected $validator;

	/**
	 * UsersController constructor.
	 *
	 * @param UserRepository $repository
	 * @param UserValidator $validator
	 */
	public function __construct(UserRepository $repository, UserValidator $validator) {
		$this->repository = $repository;
		$this->validator  = $validator;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
		if(Auth::user()->role != USER_ROLES_ADMIN){
			$users = $this->repository->findWhere(['parent_id' => Auth::user()->id]);
		}else{
			$users = $this->repository->all();
		}
		return view('app.users.index', compact('users'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
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
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		$users = $this->repository->all();
		return Auth::user() ? view('app.users.create', compact('users')) : view('auth.register', compact('users'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$user = $this->repository->find($id);
		return view('app.users.edit', compact('user'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  UserCreateRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 *
	 * @throws \Prettus\Validator\Exceptions\ValidatorException
	 */
	public function store(UserCreateRequest $request) {
		try {
			$this->validator->with($request->except('_token'))->passesOrFail(ValidatorInterface::RULE_CREATE);
			$user = $this->repository->create($request->except('_token'));
			$response = [
				'message' => 'Usuário registrado',
				'data'    => $user->toArray(),
			];
			return response()->json($response);
		} catch (ValidatorException $e) {
			return response()->json([
				'error'   => true,
				'message' => $e->getMessageBag()
			]);
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  UserUpdateRequest $request
	 * @param  string            $id
	 *
	 * @return Response
	 *
	 * @throws \Prettus\Validator\Exceptions\ValidatorException
	 */
	public function update(UserUpdateRequest $request, $id) {
		try {
			$this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
			$user = $this->repository->update($request->all(), $id);
			$response = [
				'message' => 'Usuário atualizado',
				'data'    => $user->toArray(),
			];
			return response()->json($response);
		} catch (ValidatorException $e) {
			return response()->json([
				'error'   => true,
				'message' => $e->getMessageBag()
			]);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$deleted = $this->repository->delete($id);
		return response()->json([
			'message' => 'Usuário removido',
			'deleted' => $deleted,
		]);
	}

	/**
	 * Return the specified user.
	 *
	 * @param  int $id
	 */
	public function find($id){
		return $this->repository->find($id);
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
