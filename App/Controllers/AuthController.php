<?php 

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AuthController extends Action{

    
    public function authCliente(){
        $cliente = Container::getModel('Cliente');

        $cliente->__set('email',$_POST['email_login']);
        $cliente->__set('senha',$_POST['senha_login']);

        $cliente->autenticar();

        if($cliente->__get('cpf') != '' && $cliente->__get('nome') != ''){
            session_start();

            $_SESSION['id'] = $cliente->__get('id');
            $_SESSION['nome'] = $cliente->__get('nome');

            header('Location: /home');
        }else{
            header('Location: /login?login=erro');
        }
    }

    public function authFuncionario(){

        $funcionario = Container::getModel('Funcionario');

        $funcionario->__set('email',$_POST['email_login']);
        $funcionario->__set('senha',$_POST['senha_login']);

        $funcionario->autenticar();

        if($funcionario->__get('id') != '' && $funcionario->__get('nome') != ''){
            
            session_start();

            $_SESSION['id'] = $funcionario->__get('id');
            $_SESSION['nome'] = $funcionario->__get('nome');
            $_SESSION['telefone'] = $funcionario->__get('telefone');
            $_SESSION['rg'] = $funcionario->__get('rg');
            $_SESSION['funcionario'] = true;

            header('Location: /home');

        }else{

            header('Location: /?login=erro');
        }
    }


    
    /*public function autenticar(){
        
        $usuario = Container::getModel('Usuario');
        $usuario->__set('email',$_POST['email']);
        $usuario->__set('senha', md5($_POST['senha']));
        
        $usuario->autenticar();
       
        if($usuario->__get('id') != '' && $usuario->__get('nome') != ''){
            
            session_start();

            $_SESSION['id'] = $usuario->__get('id');
            $_SESSION['nome'] = $usuario->__get('nome');

            header('Location: /timeline');

        }else{
            header('Location: /?login=erro');
        }
    }*/

    public function out(){
        session_start();
        session_destroy();
        header('Location: /');
    }
}


?>