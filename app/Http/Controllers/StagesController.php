<?php

namespace App\Http\Controllers;

use App\Repositories\StageRepository;

class StagesController extends Controller{

    public function __construct(StageRepository $repository){
        $this->repository = $repository;
        $this->names = [
            'plural' => 'stages',
            'singular' => 'stage',
            'pt_plural' => 'etapas',
            'pt_singular' => 'etapa',
            'pt_gender' => 'a',
            'base_blades' => 'stages'
        ];
    }

    /**
     * Disponible methods from Trait.
     */
    use ControllerTrait {
        ControllerTrait::trait_all as all;
    }

	public function allWithTasks(){
		return $this->repository->with(['open_tasks'])->all();
	}

}
