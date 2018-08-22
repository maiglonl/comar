<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Product;

/**
 * Class ProductTransformer.
 *
 * @package namespace App\Transformers;
 */
class ProductTransformer extends TransformerAbstract
{
	/**
	 * Transform the Product entity.
	 *
	 * @param \App\Models\Product $model
	 *
	 * @return array
	 */
	public function transform(Product $model)
	{
		return [
			'name' 			=> (int) $model->name,
			'description' 	=> $model->description,
			'category_id' 	=> $model->category_id,
			'value_partner' => (float) $model->value_partner,
			'value_seller' 	=> \App\Helpers\PermHelper::viewValues() ? (float) $model->value_seller : '',
			'weight' 		=> $model->weight,
			'height' 		=> $model->height,
			'width' 		=> $model->width,
			'length' 		=> $model->length,
			'diameter' 		=> $model->diameter,
			'quantity' 		=> $model->quantity,
			'status' 		=> $model->status
		];
	}
}
