<?php

namespace App\Helpers;

class NavHelper{
	/**
	 * Verifica se rota é ativa e retorna classe correspondente 
	 * @param  [string] $path  [Rota]
	 * @param  [string] $class [Classe a ser ativada]
	 * @return [string]        [Classe]
	 */

	public static function classActivePath($pathList, $class = 'active'){
		foreach ($pathList as $key => $path) {
			if(NavHelper::verifyRoute($path) == true){
				return " $class";
			}
		}
		return '';		
	}

	public static function verifyRoute($path){
		$path = explode('.', $path);
		$segment = 1;
		foreach($path as $p) {
			if((request()->segment($segment) == $p) == false) {
				return false;
			}
			$segment++;
		}
		return true;
	}
}