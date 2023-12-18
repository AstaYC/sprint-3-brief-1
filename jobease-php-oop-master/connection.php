<?php
class Connection {
    protected $con ;

    public function __construct(){
        $this->con = new mysqli("localhost","root","","dbjob");
           if ($this->con->connect_error){
            die("connection failed:".$this->con->connect_error);
           }
    }
    
    public function getCon(){
        return $this->con ;     
    }
}
?>

<!-- $read = new CRUD_jobs();
		$resultat=$read->read(); -->