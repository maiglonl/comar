<?php

namespace App\Http\Controllers;

use App\Repositories\TaskRepository;
use App\Repositories\StageRepository;
use App\Repositories\OrderRepository;
use App\Repositories\BillRepository;
use Illuminate\Http\Request;
use Auth;

class TasksController extends Controller{

	public function __construct(
		TaskRepository $repository,
		StageRepository $stageRepository,
		BillRepository $billRepository,
		OrderRepository $orderRepository
	){
		$this->repository = $repository;
		$this->stageRepository = $stageRepository;
		$this->billRepository = $billRepository;
		$this->orderRepository = $orderRepository;
		$this->names = [
			'plural' => 'tasks',
			'singular' => 'task',
			'pt_plural' => 'tarefas',
			'pt_singular' => 'tarefa',
			'pt_gender' => 'a',
			'base_blades' => 'tasks'
		];
	}

	/**
	 * Disponible methods from Trait.
	 */
	use ControllerTrait {
		ControllerTrait::trait_all as all;
	}

	public function workflow(){
		$stages = $this->stageRepository->with(['open_tasks'])->all();
		$credit_bills = $this->billRepository->findWhere(['done' => false, 'type' => 'credit']);
		$debit_bills = $this->billRepository->findWhere(['done' => false, 'type' => 'debit']);
		return view('app.tasks.workflow', compact('stages', 'credit_bills', 'debit_bills'));
	}

	public function finishTask($id){
		$task = $this->repository->find($id);
		$task->user_id = Auth::id();
		$task->date_conclusion = date('Y-m-d H:i:s');
		$result = $this->repository->update($task->toArray(), $id);
		$task = $this->repository->with('stage')->find($id);
		$order = $task->order;
		$order->status_id = $task->stage->status_id;
		$this->orderRepository->update($order->toArray(), $order->id);
		if($task->stage->next_stage_id != null){
			$this->repository->create([
				"order_id" => $task->order_id,
				"stage_id" => $task->stage->next_stage_id
			]);
		}

		switch ($task->stage_id) {
			case STAGE_PAYMENT:
				$this->billRepository->generateBills($task->order);
				break;
		}

		return response()->json([
			'data' => $result,
			'message' => "Tarefa finalizada."
		]);
	}

}
