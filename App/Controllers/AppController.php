<?php 

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action{
    
    public function home(){
        $this->validaAutenticar();

        $filme = Container::getModel('Filme');

        $this->view->infoHome = $filme->getAll();

        $this->render('home','layout2');
    }


    public function validaAutenticar(){

        session_start();

        if(!isset($_SESSION['id']) || $_SESSION['id'] =='' || $_SESSION['nome'] == '' ||  !isset($_SESSION['nome'])){

            header('Location: /?login=erro');
        }

    }

    public function validaAutenticarFunc(){

        session_start();

        if(!isset($_SESSION['id']) || $_SESSION['id'] =='' || $_SESSION['nome'] == '' ||  !isset($_SESSION['nome']) || !isset($_SESSION['funcionario']) || $_SESSION['funcionario'] != true){

            header('Location: /?login=erro');
        }

    }

    public function perfil(){
        $this->validaAutenticar();

        $cliente = Container::getModel('Cliente');

        $cliente->__set('id',$_SESSION['id']);

        $this->view->infoPerfil = $cliente->getInfoUser();

        $filme = Container::getModel('Filme');

        $filme->__set('idCliente',$_SESSION['id']);

        $this->view->movieDownloaded = $filme->getMovieDownloaded(); 

        $this->render('perfil','layout2');
    }

    public function about(){
        $this->validaAutenticar();

        $this->render('about','layout2');
    }


    public function page(){
        $this->validaAutenticar();

        $filme = Container::getModel('Filme');
        $filme->__set('genero',$_GET['search']);

        $this->view->infoPage = $filme->getPage();

        $this->render('page','layout2');
    }

    public function registerMovie(){
        $this->validaAutenticarFunc();

        $this->render('registerMovie','layout2');
    }

    public function registerFunc(){
        $this->validaAutenticarFunc();

        $this->render('registerFunc','layout2');
    }

    public function pageMovie(){
        $this->validaAutenticar();


        $filme = Container::getModel('Filme');
        $filme->__set('id',$_GET['id']);
        $this->view->infoMovie = $filme->getId();

        $this->view->infoMovie['dataLancamento'] = date("d/m/Y", strtotime($this->view->infoMovie['dataLancamento'] ) );

        $this->view->infoMovie['download'] = str_replace("filme/",'',$this->view->infoMovie['video']);

        $this->render('movie','layout2');
    }

    public function editMovie(){
        $this->validaAutenticarFunc();
    
        $this->render('editMovie','layout2');
    }   


    public function insertFunc(){
        $this->validaAutenticarFunc();

        $funcionario = Container::getModel('Funcionario');
        
        $funcionario->__set('nome',$_POST['nome_func']);
        $funcionario->__set('email',$_POST['email_func']);
        $funcionario->__set('senha',$_POST['senha_func']);
        $funcionario->__set('cpf',$_POST['cpf_func']);
        $funcionario->__set('rg',$_POST['rg_func']);
        $funcionario->__set('telefone',$_POST['telefone_func']);
        $funcionario->__set('dataNascimento',$_POST['diaNascimento_func']);

        if($funcionario->validarCadastro()){

            $funcionario->save();
            header('Location: /register_func?insert=1');

        }else{
            header('Location: /register_func?insert=2');
        }
    }

    public function insertMovie(){

        $this->validaAutenticarFunc();

        $filme = Container::getModel('Filme');

        $filme->__set('nome',$_POST['nome_filme']);
        $filme->__set('sinopse',$_POST['sinopse_filme']);
        $filme->__set('diretor',$_POST['diretor_filme']);
        $filme->__set('dataLancamento',$_POST['lanc_filme']);
        $filme->__set('idFuncionario',$_SESSION['id']);
        $filme->__set('genero',$_POST['genero_filme']);

        echo '1<br>';
        if($filme->autenticar()){
            $_UP['pasta_poster'] = '/projetos/video-tape/public/img/';

            $_UP['tamanho_poster'] = 1024*1024*5;

            $_UP['extensoes_poster'] = array('jpg','jpeg','png');

            if ($_FILES['poster_filme']['error'] != 0) {
                echo "Não foi possível fazer o upload do poster, erro:<br />" . $_UP['erros'][$_FILES['poster_filme']['error']];
            }

            $extensaoPoster = strtolower(end(explode('.',$_FILES['poster_filme']['name'])));

            if(in_array($extensaoPoster,$_UP['extensoes_poster']) == false){

                echo 'extensão não suportada no poster <br />';

            }else if($_UP['tamanho_poster'] < $_FILES['poster_filme']['size']){

                echo 'arquivo muito grande para ser suportado no poster <br />';

            }else{

                $nome_final_poster = md5(time()).'.'.$extensaoPoster;

                if(move_uploaded_file($_FILES['poster_filme']['tmp_name'],$_UP['pasta_poster'].$nome_final_poster)){
                    
                    $filme->__set('poster','img/'.$nome_final_poster);

                }else{
                    echo 'erro ao fazer o upload';
                }
            }

        $_UP['pasta_filme'] = '/projetos/video-tape/public/filme/';
        $_UP['tamanho_filme'] = 1024*1024*1024*10;
        $_UP['extensoes_filme'] = array('mp4');

        if ($_FILES['filme_filme']['error'] != 0) {
            echo "Não foi possível fazer o upload do poster, erro:<br />" . $_UP['erros'][$_FILES['filme_filme']['error']];
        }

        $extensaoFilme = strtolower(end(explode('.',$_FILES['filme_filme']['name'])));
        
        if(in_array($extensaoFilme,$_UP['extensoes_filme']) == false){
            echo '<br>extensão não suportada no video<br>';
        }else if($_FILES['filme_filme']['size'] > $_UP['tamanho_filme']){
            echo '<br>arquivo do filme muito grande <br>';
        }else{
            $nome_final_filme = md5(time()).'.'.$extensaoFilme;
            if(move_uploaded_file($_FILES['filme_filme']['tmp_name'],$_UP['pasta_filme'].$nome_final_filme)){

                $filme->__set('video','filme/'.$nome_final_filme);

            }else{
                echo 'erro filme!';
            }

        }

        if($filme->__get('poster') && $filme->__get('video')){

            $filme->save();

            header('Location: /register_movie?insert=1');
        }
    }


}

    public function editMovieData(){
        $this->validaAutenticarFunc();

        $filme = Container::getModel('Filme');

        $filme->__set('id',$_GET['id']);
        $filme->__set('nome',$_POST['nome_edit']);
        $filme->__set('sinopse',$_POST['sinopse_edit']);
        $filme->__set('diretor',$_POST['diretor_edit']);
        $filme->__set('dataLancamento',$_POST['lanc_edit']);
        $filme->__set('idFuncionario',$_SESSION['id']);
        $filme->__set('genero',$_POST['genero_edit']);

        if($filme->autenticar()){
            $_UP['pasta_poster'] = '/projetos/video-tape/public/img/';

            $_UP['tamanho_poster'] = 1024*1024*5;

            $_UP['extensoes_poster'] = array('jpg','jpeg','png');

            if ($_FILES['poster_edit']['error'] != 0) {
                echo "Não foi possível fazer o upload do poster, erro:<br />" . $_UP['erros'][$_FILES['poster_filme']['error']];
            }

            $extensaoPoster = strtolower(end(explode('.',$_FILES['poster_edit']['name'])));

            if(in_array($extensaoPoster,$_UP['extensoes_poster']) == false){

                echo 'extensão não suportada no poster <br />';

            }else if($_UP['tamanho_poster'] < $_FILES['poster_filme']['size']){

                echo 'arquivo muito grande para ser suportado no poster <br />';

            }else{

                $nome_final_poster = md5(time()).'.'.$extensaoPoster;

                if(move_uploaded_file($_FILES['poster_edit']['tmp_name'],$_UP['pasta_poster'].$nome_final_poster)){
                    
                    $filme->__set('poster','img/'.$nome_final_poster);

                }else{
                    echo 'erro ao fazer o upload';
                }
            }

        $_UP['pasta_filme'] = '/projetos/video-tape/public/filme/';
        $_UP['tamanho_filme'] = 1024*1024*1024*10;
        $_UP['extensoes_filme'] = array('mp4');

        if ($_FILES['filme_edit']['error'] != 0) {
            echo "Não foi possível fazer o upload do poster, erro:<br />" . $_UP['erros'][$_FILES['filme_filme']['error']];
        }

        $extensaoFilme = strtolower(end(explode('.',$_FILES['filme_edit']['name'])));
        
        if(in_array($extensaoFilme,$_UP['extensoes_filme']) == false){
            echo '<br>extensão não suportada no video<br>';
        }else if($_FILES['filme_edit']['size'] > $_UP['tamanho_filme']){
            echo '<br>arquivo do filme muito grande <br>';
        }else{
            $nome_final_filme = md5(time()).'.'.$extensaoFilme;
            if(move_uploaded_file($_FILES['filme_edit']['tmp_name'],$_UP['pasta_filme'].$nome_final_filme)){

                $filme->__set('video','filme/'.$nome_final_filme);

            }else{
                echo 'erro filme!';
            }

        }

        if($filme->__get('poster') && $filme->__get('video')){

            $filme->edit();

            header('Location: /edit_movie?insert=1');
        }
    }   
}

    public function SearchMovie(){
        $this->validaAutenticar();

        $filme = Container::getModel('Filme');

        $filme->__set('nome',$_GET['search']);

        $this->view->infoPage =  $filme->SearchMovie();

        $this->render('page','layout2');
}

    public function thanks(){
        $this->validaAutenticar();

        $filme = Container::getModel('Filme');

        $filme->__set('id',$_GET['filme']);
        $filme->__set('idCliente',$_GET['cliente']);

        $filme->registerDownload();

        $this->render('thanks','layout2');
    }

    /*public function timeline(){

            $this->validaAutenticar();
            
            $tweet = Container::getModel('tweet');

            $tweet->__set('id_usuario',$_SESSION['id']);

           

            $total_registros_pagina = 10;
            $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

            $deslocamento = ($pagina - 1) * $total_registros_pagina;

            $this->view->pagina_ativa = $pagina;

            $this->view->tweets = $tweet->getPorPagina($total_registros_pagina,$deslocamento);

            $total_tweets = $tweet->getTotalRegistros();
            $this->view->total_de_paginas = ceil($total_tweets['total']/$total_registros_pagina);



            $usuario = Container::getModel('Usuario');

            $usuario->__set('id',$_SESSION['id']);
            
            $this->view->info_usuario = $usuario->getInfoUsuario();
            $this->view->total_tweets = $usuario->getTotalTweets();
            $this->view->total_seguindo = $usuario->getTotalSeguindo();
            $this->view->total_seguidores = $usuario->getTotalSeguidores();

            $this->render('timeline');
        
    }

    public function tweet(){

        $this->validaAutenticar();   
        
            $tweet = Container::getModel('Tweet');

            $tweet->__set('tweet',$_POST['tweet']);
            $tweet->__set('id_usuario',$_SESSION['id']);

            $tweet->salvar();

            header('Location: /timeline');

    }
    
    public function validaAutenticar(){

        session_start();

        if(!isset($_SESSION['id']) || $_SESSION['id'] =='' || $_SESSION['nome'] == '' ||  !isset($_SESSION['nome'])){

            header('Location: /?login=erro');
        }

    }

    public function quemSeguir(){

        $this->validaAutenticar();

        $this->view->usuarios = array();
        $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';

        if($pesquisarPor != ''){
            $usuario = Container::getModel('Usuario');
            $usuario->__set('nome',$pesquisarPor);
            $usuario->__set('id',$_SESSION['id']);
            $this->view->usuarios = $usuario->getAll();
        }

        $this->render('quemSeguir');
    }

    public function acao(){
        $this->validaAutenticar();

        $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
        $id_usuario = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

        $usuario = Container::getModel('Usuario');
        $usuario->__set('id',$_SESSION['id']);

        if($acao == 'seguir'){

            $usuario->seguirUsuario($id_usuario);
        }else if($acao == 'deixar_de_seguir'){

            $usuario->deixarSeguirUsuario($id_usuario);
        }

        header('Location: /quem_seguir');
    }

    public function apagar_tweet(){
        $this->validaAutenticar();

        $tweet = Container::getModel('Tweet');
        $tweet->__set('id',$_GET['tweet_deletado']);
        $tweet->deleteTweet();

        header('Location: /timeline');
    }*/

   

}    
?>