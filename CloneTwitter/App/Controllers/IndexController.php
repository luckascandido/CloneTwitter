<?php

namespace App\Controllers;

use App\Models\Usuario;
use MF\Controller\Action;
use MF\Model\Container;


class IndexController extends Action {

	public function index() {
		$this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
		$this->render('index', "layout");
	}
	//Inscreverse
	public function inscreverse() {
		$this->view->usuario = array(
			'nome'=> "",
			'email'=> "",
			'senha'=> "",
		);
	
		$this->render('inscreverse', "layout");
	}
	public function registrar() {
		//receber dados
		$usuario = Container::getModel("Usuario");
		$usuario->__set('nome',$_POST['nome']);
		$usuario->__set('email',$_POST['email']);
		$usuario->__set('senha',md5($_POST['senha']));
		//valida cadastro
		if($usuario->valiadarCadastro()&&count($usuario->getUsuarioPorEmail())==0){
				$usuario->salvar();
				$this->render('Cadastro',"layout");
				}
		else{
			$this->view->usuario = array(
				'nome'=> $_POST['nome'],
				'email'=> $_POST['email'],
				'senha'=> $_POST['senha'],
			);
			$this->view->erroCadastro= true;
			$this->render('inscreverse',"layout");
		
		}
	 }
	}
?>