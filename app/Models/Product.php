<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Support\Facades\Storage;

/**
 * Class Product.
 *
 * @package namespace App\Models;
 */
class Product extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [	'name', 'value', 'description', 'status'];

	protected $appends = ['files'];

    public function getFilesAttribute(){
    	$path = env('FILES_PATH_PRODUCTS')."/".$this->id;
        return Storage::files($path);
    }

}
