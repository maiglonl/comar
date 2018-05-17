<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Support\Facades\Storage;
use Folklore\Image\Facades\Image;

/**
 * Class Product.
 *
 * @package namespace App\Models;
 */
class Product extends Model implements Transformable {
	use TransformableTrait;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [	'name', 'value', 'description', 'status'];

	protected $appends = ['files', 'thumbnails'];

	public function getFilesAttribute(){
		$path = env('FILES_PATH_PRODUCTS')."/".$this->id."/";
		$files = Storage::files($path);
		foreach ($files as $key => $file) {
			$files[$key] = '/'.str_replace("public", 'storage', $file);
		}
		return $files;
	}

	public function getThumbnailsAttribute(){
		$files = [];
		foreach ($this->files as $key => $file) {
			$files[$key] = Image::url($file,300,300,array('crop'));
		}
		return $files;
	}

}
