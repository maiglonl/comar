<?php

namespace App\Helpers;
use DateTime;
use DatePeriod;
use DateInterval;

class DateHelper{
	// Função que subtrai periodo de datas em String
	public static function subDate($data, $periodo){
		$newDate = date_create($data);
		$newDate->sub(new DateInterval($periodo));
		return $newDate->format('Y-m-d');
	}

	// Função que adiciona periodo de datas em String
	public static function addDate($data, $periodo){
		$newDate = date_create($data);
		$newDate->add(new DateInterval($periodo));
		return $newDate->format('Y-m-d');
	}
}