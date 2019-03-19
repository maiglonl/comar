<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class ExpiredPasswordController extends Controller {

	public function expired(){
		return view('auth.passwords.expired');
	}

	public function postExpired(Request $request){
		// Checking current password
		if (!Hash::check($request->current_password, $request->user()->password)) {
			return response()->json([
				'error'   => true,
				'message' => ['current_password' => ['Senha atual incorreta!']]
			]);
		}

		$request->user()->update([
			'password' => bcrypt($request->new_password),
			'password_changed_at' => Carbon::now()->toDateTimeString()
		]);
		return redirect()->back()->with(['status' => 'Senha alterada com sucesso']);
	}
}
