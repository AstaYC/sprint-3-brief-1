<?php
require '../auth.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $delete = new CRUD_jobs();
        $delete->delete($title);
    }
 
?>
