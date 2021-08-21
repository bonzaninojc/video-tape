<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() {

		$routes['index'] = array(
			'route' => '/',
			'controller' => 'indexController',
			'action' => 'index'
		);

		$routes['sign_up'] = array(
			'route' => '/sign_up',
			'controller' => 'indexController',
			'action' => 'signUp'
		);

		$routes['register'] = array(
			'route' => '/register',
			'controller' => 'indexController',
			'action' => 'register'
		);

		$routes['login'] = array(
			'route' => '/login',
			'controller' => 'indexController',
			'action' => 'login'
		);

		$routes['auth_cliente'] = array(
			'route' => '/auth_cliente',
			'controller' => 'AuthController',
			'action' => 'authCliente'
		);

		$routes['auth_funcionario'] = array(
			'route' => '/auth_funcionario',
			'controller' => 'AuthController',
			'action' => 'authFuncionario'
		);
	
		$routes['home'] = array(
			'route' => '/home',
			'controller' => 'AppController',
			'action' => 'home'
		);

		$routes['perfil'] = array(
			'route' => '/perfil',
			'controller' => 'AppController',
			'action' => 'perfil'
		);

		$routes['about'] = array(
			'route' => '/about',
			'controller' => 'AppController',
			'action' => 'about'
		);

		$routes['page'] = array(
			'route' => '/page',
			'controller' => 'AppController',
			'action' => 'page'
		);

		$routes['out'] = array(
			'route' => '/out',
			'controller' => 'AuthController',
			'action' => 'out'
		);

		$routes['register_movie'] = array(
			'route' => '/register_movie',
			'controller' => 'AppController',
			'action' => 'registerMovie'
		);

		$routes['register_func'] = array(
			'route' => '/register_func',
			'controller' => 'AppController',
			'action' => 'registerFunc'
		);

		$routes['insert_func'] = array(
			'route' => '/insert_func',
			'controller' => 'AppController',
			'action' => 'insertFunc'
		);

		$routes['movie'] = array(
			'route' => '/movie',
			'controller' => 'AppController',
			'action' => 'pageMovie'
		);

		$routes['insert_movie'] = array(
			'route' => '/insert_movie',
			'controller' => 'AppController',
			'action' => 'insertMovie'
		);

		$routes['edit_movie'] = array(
			'route' => '/edit_movie',
			'controller' => 'AppController',
			'action' => 'editMovie'
		);

		$routes['edit_movie_data'] = array(
			'route' => '/edit_movie_data',
			'controller' => 'AppController',
			'action' => 'editMovieData'
		);

		$routes['search_movie'] = array(
			'route' => '/search_movie',
			'controller' => 'AppController',
			'action' => 'SearchMovie'
		);

		$routes['thanks'] = array(
			'route' => '/thanks',
			'controller' => 'AppController',
			'action' => 'thanks'
		);

		$this->setRoutes($routes);
	}

}

?>