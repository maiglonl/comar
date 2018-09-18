<?php

namespace App\Http\Controllers;

use App\Repositories\TaskRepository;
use App\Repositories\StageRepository;

class TasksController extends Controller{

	public function __construct(
		TaskRepository $repository,
		StageRepository $stageRepository
	){
		$this->repository = $repository;
		$this->stageRepository = $stageRepository;
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
		$stages = $this->stageRepository->all();
		return view('app.tasks.workflow', compact('tasks', 'stages'));
	}

}
