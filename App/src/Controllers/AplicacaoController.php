<?php
namespace App\Controllers;
use App\Dao\Aplicacao;

class AplicacaoController {
	public function selectId(){
		return Aplicacao::selectId($_POST['id']);
	}
}