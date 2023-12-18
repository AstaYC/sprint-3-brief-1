<?php
session_start();
require 'connection.php';
class Authentification {
    protected $con ;
    public function __construct(){
        $this->con = new Connection();
    }
    
    public function register($username,$email,$password,$role){
        $con = $this->con->getCon();
        $passwordhash = md5($password);
        $stmt=$con->prepare("INSERT INTO users (`username`,`email`,`password`,`role_name`)VALUES(?,?,?,?)");
        $stmt->bind_param("ssss",$username,$email,$passwordhash,$role);

        if ($stmt->execute()){
            echo "bien enregistrer , bienvenue $username";
            header("location:login.php");
            return true ;
        }else{
            echo "il ya un probleme repeter s'il vous plait";
            return false ;
        }
   }
    
    public function login ($username,$password){
        $passwordhached = md5($password);
        $con = $this->con->getCon();
        $stmt=$con->prepare("SELECT * FROM users WHERE username = ? LIMIT 1 ");
        $stmt->bind_param("s",$username);
        $stmt->execute();
        $r=$stmt->get_result();
        $v=$r->fetch_assoc();
        if ($r->num_rows == 1 ){
               $_SESSION['username'] = $username ;
               if($passwordhached == $v['password'] && $v['role_name'] == 'admin'){
                  echo "bienvenue admin $username";
                  $_SESSION['password'] = $passwordhached;
                  $_SESSION['role']=$v['role_name'];
                  header("location:dashboard/dashboard.php");
               }else if ($passwordhached == $v['password'] && $v['role_name'] == 'candidat'){
                  echo "bienvenue $username";
                  $_SESSION['password'] = $passwordhached;
                  $_SESSION['role']=$v['role_name'];
                  header("location:index.php");
               }else {
                echo "il ya un problem dans la recuperation du password";
               }
        }else{
            echo "username n'est pas existe";
        }
    }
}

class CRUD_jobs {
   public function __construct(){
    $this->con = new Connection();
   }

    public function create ($title , $description , $company , $location , $date_created , $status){
       $con =$this->con->getCon();
       $stmt= $con->prepare("INSERT into jobs (title , `description` , `company` , `location` , `date_created` , `status`) VALUES (? , ? , ? , ? , ? , ?)");
       $stmt->bind_param("ssssss" , $title , $description , $company , $location , $date_created , $status);
       $stmt-> execute();
       $stmt->close();
    }

    public function read(){
        $con = $this->con->getCon();
        $stmt = $con->prepare("SELECT * FROM jobs");
        $stmt->execute();
        $r = $stmt->get_result();
        
        $results = array(); // Créer un tableau pour stocker les résultats
    
        while ($row = $r->fetch_assoc()) {
            $results[] = $row; // Ajouter chaque ligne de résultat au tableau
        }
    
        return $results; // Retourner le tableau contenant toutes les lignes
    }
    
    public function delete($title){
        $con = $this->con->getcon();
        $stmt = $con->prepare("DELETE FROM jobs WHERE title = $title");
        $stmt->execute();
        $r = $stmt->get_result();   
    }
}

if (isset($_GET['id'])){
    session_destroy();
    header("location:login.php");   
}
?>