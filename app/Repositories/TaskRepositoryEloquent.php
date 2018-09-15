<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TaskRepository;
use App\Models\Task;
use App\Validators\TaskValidator;

/**
 * Class TaskRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TaskRepositoryEloquent extends BaseRepository implements TaskRepository{
    /**
     * Specify Model class name
     */
    public function model(){
        return Task::class;
    }

    /**
     * Specify Validator class name
     */
    public function validator(){
        return TaskValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot(){
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
