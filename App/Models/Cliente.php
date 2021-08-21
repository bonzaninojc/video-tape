<?php 

namespace App\Models;

use MF\Model\Model;

class Cliente extends Model{

    private $id;
    private $nome;
    private $email;
    private $senha;
    private $cpf;
    private $dataNascimento;

    public function __get($atributo){
        return $this->$atributo;
    }

    public function __set($atributo, $valor){
       $this->$atributo = $valor; 
    }

    //salvar
    public function salvar(){
        $query = "insert into cliente(nome,email,senha,cpf,dataNascimento)values(:nome,:email,:senha,:cpf,:date) ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome',$this->__get('nome'));
        $stmt->bindValue(':email',$this->__get('email'));
        $stmt->bindValue(':senha',$this->__get('senha'));
        $stmt->bindValue(':cpf',$this->__get('cpf'));
        $stmt->bindValue(':date',$this->__get('dataNascimento'));
        $stmt->execute();

        return $this;
    }

    //validar cadastro
    public function validarCadastro(){
        $validar = true;

        if( strlen($this->__get('nome')) < 3){
            $validar = false;
        } 

        if( strlen($this->__get('email')) < 3){
            $validar = false;
        }

        if( strlen($this->__get('senha')) < 3){
            $validar = false;
        }
        

        return $validar;
    }


    //recupera um user por email
    public function getUserEmailOrCpf(){
        $query = "select * from cliente where email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email',$this->__get('email'));
        $stmt->execute();
        return  $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    #autenticando user
    public function autenticar(){
        $query = "select * from cliente where email = :email and senha=:senha";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email',$this->__get('email'));
        $stmt->bindValue(':senha',$this->__get('senha'));
        $stmt->execute();

        $cliente = $stmt->fetch(\PDO::FETCH_ASSOC);

        if($cliente['id'] != '' && $cliente['nome'] != '' ){

            $this->__set('id',$cliente['id']);
            $this->__set('nome',$cliente['nome']);
            $this->__set('cpf',$cliente['cpf']);
            $this->__set('dataNascimento',$cliente['dataNascimento']);
            
        }

        return $this;
    }

    #rec contas por nome
    public function getAll(){
        $query = "
        select 
            u.id,u.nome,u.email, 
            (
                select 
                    count(*) 
                from 
                    usuarios_seguidores as us
                where
                    us.id_usuario =:id_usuario and us.id_usuario_seguindo = u.id
            ) as seguindo_sn
        from 
            usuarios as u 
        where 
            u.nome like :nome and u.id != :id_usuario";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome',"%".$this->__get('nome')."%");
        $stmt->bindValue(':id_usuario',$this->__get('id'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    #seguir usuario
    public function seguirUsuario($id_usuario_seguir){
        $query = "insert into usuarios_seguidores(id_usuario,id_usuario_seguindo)values(:id_usuario,:id_usuario_seguir);";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario_seguir',$id_usuario_seguir);
        $stmt->bindValue(':id_usuario',$this->__get('id'));
        $stmt->execute();

        return true;
    }
    
    #deixa de seguir usuario
    public function deixarSeguirUsuario($id_usuario_seguir){
        $query = 'delete from usuarios_seguidores where id_usuario = :id_usuario and id_usuario_seguindo = :id_usuario_seguir';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario',$this->__get('id'));
        $stmt->bindValue(':id_usuario_seguir',$id_usuario_seguir);
        $stmt->execute();

        return true;
    }

    #informações do user
    public function getInfoUser(){
        $query = "select * from cliente where id= :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario',$this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    #rec total de tweets
    public function getTotalTweets(){
        $query = "select count(*)as total_tweet from tweets where id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario',$this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    #total de usuarios q o user tá seguindo
    public function getTotalSeguindo(){
        $query = "select count(*) as total_seguindo from usuarios_seguidores where id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario',$this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    #total de seguidores
    public function getTotalSeguidores(){
        $query = "select count(*) as total_seguidores from usuarios_seguidores where id_usuario_seguindo = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario',$this->__get('id'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}

?>