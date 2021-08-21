<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function index() {

		$this->view->login = isset($_GET['login']) ? $_GET['login']: '';

		$this->render('index');
	}

	public function signUp(){
		$this->view->erroCadastro = false;

		$this->view->dadosCadastrados = false;

		$this->render('sign_up');
	}

	public function register(){

		$cliente = Container::getModel('Cliente');

		$cliente->__set('nome',$_POST['nome_register']);
		$cliente->__set('email',$_POST['email_register']);
		$cliente->__set('senha',$_POST['senha_register']);
		$cliente->__set('cpf',$_POST['cpf_register']);
		$cliente->__set('dataNascimento',$_POST['date_register']);

		if($cliente->validarCadastro()){
			
			if(!$cliente->getUserEmailOrCpf()) {
				
				$cliente->salvar();
				header('Location: /');

			}else{

				$this->view->dadosCadastrados = true;
				$this->render('sign_up');
			}

		}else{

			$this->view->erroCadastro = true;
			$this->render('sign_up');
		}
		
	}

	public function login(){
		
		$this->render('login');
	}

}


?>