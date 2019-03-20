<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Support\Facades\Storage;
use Folklore\Image\Facades\Image;
use App\Models\Attribute;
use Auth;

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
	protected $fillable = [
		'name',
		'description',
		'category_id',
		'value_partner',
		'value_seller',
		'weight',
		'height',
		'width',
		'length',
		'diameter',
		'quantity',
		'interest_free',
		'free_shipping',
		'status'
	];

	protected $appends = ['files', 'thumbnails', 'value', 'value_show'];
	protected $with = ['attributes', 'category'];

	public function attributes(){
		return $this->hasMany(Attribute::class);
	}

	public function category(){
		return $this->belongsTo(Category::class);
	}

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

	public function getValueAttribute(){
		if(!Auth::user() || Auth::user()->role == USER_ROLES_PARTNER){
			$value = $this->value_partner;
			$value = $this->discount > 0 ? (1-($this->discount/100))*$this->value_partner : $this->value_partner;
			return $value;
		}
		return $this[\App\Helpers\PermHelper::lowerValueText()];
	}

	public function getValueShowAttribute(){
		$value = $this->value_partner;
		$value = $this->discount > 0 ? (1-($this->discount/100))*$this->value_partner : $this->value_partner;
		return $value;
	}

}
