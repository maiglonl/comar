<?php

namespace App\Helpers;
use App\Models\User;
use Auth;

class PermHelper{

	public static function viewValues(){
		if(!Auth::user()){
			return false;
		}
		switch (Auth::user()->role){
			case USER_ROLES_ADMIN:
			case USER_ROLES_SELLER: return true; break;
			default: return false; break;
		}
	}

	public static function lowerValue(){
		if(!Auth::user()){
			return false;
		}
		switch (Auth::user()->role){
			case USER_ROLES_ADMIN:
			case USER_ROLES_SELLER: return true; break;
			default: return false; break;
		}
	}

	public static function lowerValueText(){
		if(!Auth::user()){
			return 'value_partner';
		}
		switch (Auth::user()->role){
			case USER_ROLES_ADMIN:
			case USER_ROLES_SELLER: return 'value_seller'; break;
			default: return 'value_partner'; break;
		}
	}

}