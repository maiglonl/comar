<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller {
	/**
	 * Create a new controller instance.
	 */
	public function __construct(){
		$this->middleware('auth');
	}

	/**
	 * Show the static dashboard.
	 */
	public function index(){
		return view('home');
	}

	/**
	 * Show the app dashboard.
	 */
	public function appIndex(){
		return view('app.home');
	}
}
