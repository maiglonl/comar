<?php

namespace App\Helpers;

class FileHelper{
	/**
	 * Lê e converte arquivo xml em array 
	 * @param  [string] $file [Caminho do arquivo de origem]
	 * @return [array]        [Array com resultado ca conversão]
	 */
	public static function xmlToArray($file){
		$xml = implode('',file($file));
		$toRemove = ['rap', 'turss', 'crim', 'cred', 'j', 'rap-code', 'evic'];
		$nameSpaceDefRegEx = '(\S+)=["\']?((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']?';
		foreach( $toRemove as $remove ) {
			$xml = str_replace('<'.$remove.':', '<', $xml);
			$xml = str_replace('</'.$remove.':', '</', $xml);
			$xml = str_replace($remove.':commentText', 'commentText', $xml);
			$pattern = "/xmlns:{$remove}{$nameSpaceDefRegEx}/";
			$xml = preg_replace($pattern, '', $xml, 1);
		}
		return json_decode(json_encode(simplexml_load_string($xml)), true);
	}

	/**
	 * Converte arvore de arquivos em array 
	 * @param  [string] $dir  [Caminho de origem]
	 * @return [array]        [Array com resultado da conversão]
	 */
	public static function dirToArray($dir){
		$ds = DIRECTORY_SEPARATOR;
		$result = array(); 
		$cdir = scandir($dir); 
		foreach ($cdir as $key => $value){ 
			if (!in_array($value,array(".",".."))){
				if (is_dir($dir . $ds . $value)){
					$result[$value] = FileHelper::dirToArray($dir.$ds.$value);
				} else {
					$result[] = $value;
				}
			}
		}
		return $result;
	}
}