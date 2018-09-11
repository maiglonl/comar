<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\StageRepository;
use App\Models\Stage;
use App\Validators\StageValidator;

/**
 * Class StageRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class StageRepositoryEloquent extends BaseRepository implements StageRepository{
    /**
     * Specify Model class name
     */
    public function model(){
        return Stage::class;
    }

    /**
     * Specify Validator class name
     */
    public function validator(){
        return StageValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot(){
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
