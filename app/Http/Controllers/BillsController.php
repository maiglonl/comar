<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\BillCreateRequest;
use App\Http\Requests\BillUpdateRequest;
use App\Repositories\BillRepository;
use App\Validators\BillValidator;

/**
 * Class BillsController.
 *
 * @package namespace App\Http\Controllers;
 */
class BillsController extends Controller
{
    /**
     * @var BillRepository
     */
    protected $repository;

    /**
     * @var BillValidator
     */
    protected $validator;

    /**
     * BillsController constructor.
     *
     * @param BillRepository $repository
     * @param BillValidator $validator
     */
    public function __construct(BillRepository $repository, BillValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $bills = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $bills,
            ]);
        }

        return view('bills.index', compact('bills'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  BillCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(BillCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $bill = $this->repository->create($request->all());

            $response = [
                'message' => 'Bill created.',
                'data'    => $bill->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bill = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $bill,
            ]);
        }

        return view('bills.show', compact('bill'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bill = $this->repository->find($id);

        return view('bills.edit', compact('bill'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  BillUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(BillUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $bill = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Bill updated.',
                'data'    => $bill->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Bill deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Bill deleted.');
    }
}
