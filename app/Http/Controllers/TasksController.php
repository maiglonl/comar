<?php

namespace App\Http\Controllers;

use App\Repositories\TaskRepository;

class TasksController extends Controller{

    public function __construct(TaskRepository $repository){
        $this->repository = $repository;
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

}
