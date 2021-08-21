<?php 

namespace App\Models;

use MF\Model\Model;

class Filme extends Model{

    private $id;
    private $nome;
    private $sinopse;
    private $diretor;
    private $dataLancamento;
    private $idFuncionario;
    private $genero;
    private $poster;
    private $video;
    private $idCliente;


    public function __get($atributo){
        return $this->$atributo;
    }

    public function __set($atributo, $valor){
        $this->$atributo = $valor;
    }

    public function autenticar(){
        $validar = true;

        if($this->__get('nome') < 3){
            $validar = false;
        }else if($this->__get('diretor') < 3){
            $validar = false;
        }else if($this->__get('genero') < 3 ){
            $validar = false;
        }

        return $validar;
    }

    public function save(){

        $query = 'insert into filme(nome,sinopse,diretor,dataLancamento,idFuncionario,genero,poster,video)values(:nome,:sinopse,:diretor,:data,:idFunc,:genero,:poster,:video)';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome',$this->__get('nome'));
        $stmt->bindValue(':sinopse',$this->__get('sinopse'));
        $stmt->bindValue(':diretor',$this->__get('diretor'));
        $stmt->bindValue(':data',$this->__get('dataLancamento'));
        $stmt->bindValue(':idFunc',$this->__get('idFuncionario'));
        $stmt->bindValue(':genero',$this->__get('genero'));
        $stmt->bindValue(':poster',$this->__get('poster'));
        $stmt->bindValue(':video',$this->__get('video'));
        $stmt->execute();

        return $this;
    }

    public function getAll(){
        $query = "select id,nome,dataLancamento,poster from filme";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getId(){
        $query = "select * from filme where id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id',$this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function edit(){
        $query="update filme set nome = :nome, sinopse= :sinopse, diretor = :diretor, dataLancamento = :dataL, idFuncionario = :idFunc, genero = :genero, poster = :poster, video = :video where id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome',$this->__get('nome'));
        $stmt->bindValue(':sinopse',$this->__get('sinopse'));
        $stmt->bindValue(':diretor',$this->__get('diretor'));
        $stmt->bindValue(':dataL',$this->__get('dataLancamento'));
        $stmt->bindValue(':idFunc',$this->__get('idFuncionario'));
        $stmt->bindValue(':genero',$this->__get('genero'));
        $stmt->bindValue(':poster',$this->__get('poster'));
        $stmt->bindValue(':video',$this->__get('video'));
        $stmt->bindValue(':id',$this->__get('id'));
        $stmt->execute();

        return $this;
    }


    public function getPage(){
        
        $query = "select id,nome,poster from filme where genero LIKE :genero";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':genero',$this->__get('genero'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function SearchMovie(){
        $query = "select id,nome,poster from filme where nome like  :nome";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome',$this->__get('nome'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function registerDownload(){
        $query = "insert into filme_cliente(idCliente,idFilme)values(:idC,:idF)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':idC',$this->__get('idCliente'));
        $stmt->bindValue(':idF',$this->__get('id'));
        $stmt->execute();

        return $this;
    }

    public function getMovieDownloaded(){
        $query = "SELECT f.id,f.nome,f.poster FROM filme AS f RIGHT JOIN filme_cliente as fc on(fc.idFilme = f.id) WHERE fc.idCliente = :idC";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':idC',$this->__get('idCliente'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}

?>