<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Repositories\CategoryRepository;
use App\Validators\CategoryValidator;

/**
 * Class CategoriesController.
 *
 * @package namespace App\Http\Controllers;
 */
class CategoriesController extends Controller{

    protected $repository;
    protected $validator;

    public function __construct(CategoryRepository $repository, CategoryValidator $validator){
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Show the form for create resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('app.categories.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $category = $this->repository->find($id);
        return view('app.categories.edit', compact('category'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  CategoryCreateRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CategoryCreateRequest $request){
        try {
            $this->validator->with($request->except('_token'))->passesOrFail(ValidatorInterface::RULE_CREATE);
            $product = $this->repository->create($request->except('_token'));
            $response = [
                'message' => 'Categoria registrada',
                'data'    => $product->toArray(),
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
     * @param  CategoryUpdateRequest $request
     * @param  string            $id
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(CategoryUpdateRequest $request, $id){
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $product = $this->repository->update($request->all(), $id);
            $response = [
                'message' => 'Categoria atualizada',
                'data'    => $product->toArray(),
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $deleted = $this->repository->delete($id);
        return response()->json([
            'message' => 'Categoria removida',
            'deleted' => $deleted,
        ]);
    }

	/**
	 * Return list with all products.
	 */
	public function all(){
		return $this->repository->all();
	}

}
