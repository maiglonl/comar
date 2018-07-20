<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

/**
 * Class ProductsController.
 *
 * @package namespace App\Http\Controllers;
 */
class ProductsController extends Controller {

	protected $repository;

	public function __construct(ProductRepository $repository) {
		$this->repository = $repository;
		$this->names = [
			'plural' => 'products',
			'singular' => 'product',
			'pt_plural' => 'produtos',
			'pt_singular' => 'produto',
			'pt_gender' => 'o',
			'base_blades' => 'products'
		];
	}

	/**
	 * Disponible methods from Trait.
	 */
	use ControllerTrait {
		ControllerTrait::trait_index as index;
		ControllerTrait::trait_desc as desc;
		ControllerTrait::trait_show as show;
		ControllerTrait::trait_create as create;
		ControllerTrait::trait_edit as edit;
		ControllerTrait::trait_store as store;
		ControllerTrait::trait_update as update;
		ControllerTrait::trait_find as find;
		ControllerTrait::trait_all as all;
	}

	/**
	 * Display a listing of the resource.
	 */
	public function shop() {
		$this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
		$products = $this->repository->all();

		return view('app.products.shop', compact('products'));
	}

	/**
	 * Delete specific image from Product.
	 */
	private function deleteAllImages($id){
		$path = env('FILES_PATH_PRODUCTS')."/".$id."/";
		$files = Storage::files($path);
		foreach ($files as $file) {
			Storage::delete($file);
		}
	}

	/**
	 * Delete specific image from Product.
	 */
	public function deleteImage($id, $index){
		$path = env('FILES_PATH_PRODUCTS')."/".$id."/";
		$files = Storage::files($path);
		Storage::delete($files[$index]);
		$this->refreshImageNames($id);
		return response()->json([
			'message' => 'Imagem removida'
		]);
	}

	/**
	 * Upload specific image to Product.
	 */
	public function uploadImage(Request $request, $id){
		$path = env('FILES_PATH_PRODUCTS')."/".$id."/";
		foreach ($request->file('fileInput') as $key => $file) {
			$files = Storage::files($path);
			$store = Storage::putFileAs($path, $file, count($files)."_".date('YmdHis').".".$file->getClientOriginalExtension());
		}
		return response()->json([
			'message' => 'Imagens atualizadas'
		]);
	}

	/* Altera o indice da imagem */
	public function pullImage(Request $request, $id, $index){
		$path = env('FILES_PATH_PRODUCTS')."/".$id."/";
		$files = Storage::files($path);
		$minIndex = $index < 1 ? 0 : $index-1;
		Storage::move($files[$index], $path.$minIndex.".".File::extension($files[$index]));
		$this->refreshImageNames($id);
		return response()->json([
			'message' => 'Imagem atualizada'
		]);
	}

	/* Altera o indice da imagem */
	public function pushImage(Request $request, $id, $index){
		$path = env('FILES_PATH_PRODUCTS')."/".$id."/";
		$files = Storage::files($path);
		$newIndex = $index+1;
		Storage::move($files[$index], $path.$newIndex."_99999999999999.".File::extension($files[$index]));
		$this->refreshImageNames($id);
		return response()->json([
			'message' => 'Imagem atualizada.'
		]);
	}

	private function refreshImageNames($id){
		$path = env('FILES_PATH_PRODUCTS')."/".$id."/";
		$files = Storage::files($path);
		foreach ($files as $key => $file) {
			Storage::move($file, $path.$key."_".date('YmdHis').".".File::extension($file));
		}
		return true;
	}
}
