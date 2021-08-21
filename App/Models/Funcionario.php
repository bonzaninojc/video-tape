<?php 

namespace App\Models;

use MF\Model\Model;

class Funcionario extends Model{

   private $id;
   private $nome;
   private $email;
   private $cpf;
   private $rg;
   private $telefone;
   private $senha;
   private $dataNascimento;

   public function __set($atributo, $valor){
       $this->$atributo = $valor;
   }

   public function __get($atributo){
       return $this->$atributo;
   }

   public function autenticar(){

       $query = "select * from funcionario where email = :email and senha = :senha";
       $stmt = $this->db->prepare($query);
       $stmt->bindValue(':email',$this->__get('email'));
       $stmt->bindValue(':senha',$this->__get('senha'));
       $stmt->execute();

       $funcionario = $stmt->fetch(\PDO::FETCH_ASSOC);

       if($funcionario['id'] != '' && $funcionario['nome'] != ''){
            $this->__set('id',$funcionario['id']);
            $this->__set('nome',$funcionario['nome']);
            $this->__set('cpf',$funcionario['cpf']);
            $this->__set('rg',$funcionario['rg']);
            $this->__set('telefone',$funcionario['telefone']);
            $this->__set('dataNascimento',$funcionario['diaNascimento']);
       }
       
       return $this;
    }
    

    public function validarCadastro(){
        $validar = true;

        if($this->__get('nome') < 3){
            $validar = false;
        }else if($this->__get('email') < 3){
            $validar = false;
        } else if($this->__get('senha') < 3){
            $validar = false;
        }

        return $validar;
    }

    public function save(){
        $query = "insert into funcionario(nome,email,cpf,rg,telefone,senha,diaNascimento)values(:nome,:email,:cpf,:rg,:telefone,:senha,:dia)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome',$this->__get('nome'));
        $stmt->bindValue(':email',$this->__get('email'));
        $stmt->bindValue(':cpf',$this->__get('cpf'));
        $stmt->bindValue(':rg',$this->__get('rg'));
        $stmt->bindValue(':telefone',$this->__get('telefone'));
        $stmt->bindValue(':senha',$this->__get('senha'));
        $stmt->bindValue(':dia',$this->__get('dataNascimento'));
        $stmt->execute();

        return $this;
    }


   /*#salvar
   public function salvar(){
        $query = "insert into tweets(id_usuario,tweet)values(:id_usuario,:tweet)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario',$this->__get('id_usuario'));
        $stmt->bindValue(':tweet',$this->__get('tweet'));
        $stmt->execute();

        return $this;
    }

    #recuperar
    public function getAll(){
        $query = "
        select 
            t.id,t.id_usuario,u.nome,t.tweet, DATE_FORMAT(t.data,'%d/%m/%Y %H:%i') as data 
        from 
            tweets as t
            left join usuarios as u on(t.id_usuario = u.id) 
        where 
            t.id_usuario = :id_usuario
            or t.id_usuario in(select id_usuario_seguindo from usuarios_seguidores where id_usuario =:id_usuario)
        order by
            t.data desc";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario',$this->__get('id_usuario'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    #deletar tweet
    public function deleteTweet(){
        $query = "delete from tweets where id=:id_tweet";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_tweet',$this->__get('id'));
        $stmt->execute();

        return true;
    }

    public function getPorPagina($limit,$offset){
        $query = "
        select 
            t.id,t.id_usuario,u.nome,t.tweet, DATE_FORMAT(t.data,'%d/%m/%Y %H:%i') as data 
        from 
            tweets as t
            left join usuarios as u on(t.id_usuario = u.id) 
        where 
            t.id_usuario = :id_usuario
            or t.id_usuario in(select id_usuario_seguindo from usuarios_seguidores where id_usuario =:id_usuario)
        order by
            t.data desc
        limit
            $limit
        offset
            $offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario',$this->__get('id_usuario'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTotalRegistros(){
        $query = "
        select 
           count(*) as total
        from 
            tweets as t
            left join usuarios as u on(t.id_usuario = u.id) 
        where 
            t.id_usuario = :id_usuario
            or t.id_usuario in(select id_usuario_seguindo from usuarios_seguidores where id_usuario =:id_usuario)
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario',$this->__get('id_usuario'));
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }*/
}
?>