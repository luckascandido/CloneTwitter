<?php
namespace App\Models;
use MF\Model\Model;

class Usuario extends Model{
    private $id;
    private $nome;
    private $email;
    private $senha;

    public function __get($atributo)
    {
       return $this->$atributo;
    }
    public function __set($atributo, $value)
    {
        $this->$atributo = $value;
    }
    //salvar
    public function salvar(){
        $query = "insert into usuarios(nome,email,senha)values(:nome,:email,:senha)";
        $stmt= $this->db->prepare($query );
        $stmt->bindValue(":nome",$this->__get("nome"));
        $stmt->bindValue(":email",$this->__get("email"));
        $stmt->bindValue(":senha",$this->__get("senha"));
        $stmt->execute();

        return $this;
    }

    //validar cadastro
     public function valiadarCadastro(){
        $valido= true;
        if(strlen($this->__get("nome")) < 3){
            $valido = FALSE;
        }
        if(strlen($this->__get("email")) < 3){
            $valido = FALSE;
        }

        if(strlen($this->__get("senha")) < 3){
            $valido = FALSE;
        }


        return $valido;
       }

    //recuperar usuario por email
    public function getUsuarioPorEmail(){
        $query="select nome,email from usuarios where email = :email ";
        $stmt =$this->db->prepare($query);
        $stmt->bindValue(":email",$this->__get("email"));
        $stmt->execute();

        return   $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    //autentifica usuario
    public function autenticar(){
        $query="select id, nome, email from usuarios where email = :email and senha = :senha";
        $stmt= $this->db->prepare($query);
        $stmt->bindValue(":email",$this->__get("email"));
        $stmt->bindValue(":senha",$this->__get("senha"));
        $stmt->execute();

        $usuario =  $stmt->fetch(\PDO::FETCH_ASSOC);

        if($usuario['id'] != '' && $usuario['nome'] != ''){
            $this->__set('id', $usuario['id']);
            $this->__set('nome',$usuario['nome']);
     
        }

        return   $this;
    }
    //retorna todos os parametros de nome e id
    public function getAll(){
        $query = "
        select 
            u.id, u.nome, u.email, 
            (
                select
                    count(*)
                from
                    usuarios_seguidores as us
                where
                    us.id_usuario=:id_usuario and us.id_usuario_seguindo = u.id 
            ) as seguindo_sn
        from 
            usuarios as u
        where 
            u.nome like :nome and u.id != :id_usuario
        order by
           u.nome desc
         ";
        $stmt= $this->db->prepare($query );
        $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    //segue outros usuarios;
    public function seguirUsuario($id_usuario_seguindo){
            $query = "
            insert into 
            usuarios_seguidores(id_usuario, id_usuario_seguindo)
            values(:id_usuarios,:id_usuario_seguindo)
             ";
            $stmt= $this->db->prepare($query );
            $stmt->bindValue(':id_usuarios',$this->__get('id'));
            $stmt->bindValue(':id_usuario_seguindo',$id_usuario_seguindo);
            $stmt->execute();
            return true;
    }
    //deixa de seguior outros usuarios;
    public function deixarSeguirUsuario($id_usuario_seguindo){
        $query = "
        delete from usuarios_seguidores
        where
        id_usuario = :id_usuario and id_usuario_seguindo= :id_usuario_seguindo
         ";
        $stmt= $this->db->prepare($query );
        $stmt->bindValue(':id_usuario',$this->__get('id'));
        $stmt->bindValue(':id_usuario_seguindo',$id_usuario_seguindo);
        $stmt->execute();
        return true;
    }
    //inforam??oes do Usuario
    public function getInfoUsuario(){
        $query = "
            select 
                nome
            from
                usuarios
            where
                id=:id_usuario
         ";
        $stmt= $this->db->prepare($query );
        $stmt->bindValue(':id_usuario',$this->__get('id'));
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    //informa??oes de tweets
    public function getTotalTweets(){
        $query = "
            select 
                count(*) as total_tweet
            from
                tweets
            where
            id_usuario=:id_usuario
         ";
        $stmt= $this->db->prepare($query );
        $stmt->bindValue(':id_usuario',$this->__get('id'));
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    //total de usuarios que estamos seguindo
    public function getTotalSeguindo(){
        $query = "
            select 
                count(*) as total_seguindo
            from
                usuarios_seguidores
            where
            id_usuario=:id_usuario
         ";
        $stmt= $this->db->prepare($query );
        $stmt->bindValue(':id_usuario',$this->__get('id'));
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    //total de usuarios seguidores
    public function getTotalSeguidores(){
        $query = "
            select 
                count(*) as total_seguidores
            from
                usuarios_seguidores
            where
            id_usuario_seguindo=:id_usuario
         ";
        $stmt= $this->db->prepare($query );
        $stmt->bindValue(':id_usuario',$this->__get('id'));
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}

?>