<?php
namespace App\Controllers;
use App\Dao\User;

class UsersController {
	public function selectAll(){
		if (AuthController::checkAuth()) {
			$stmt = User::selectAll();
			return User::return($stmt);
		}
	}
}