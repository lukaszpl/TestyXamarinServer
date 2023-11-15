<?php 
    require 'DatabaseHandler.php';
    //$token = $_POST["token"];
    //if($token == ""){
        $id = $_POST["Id"];
        echo json_encode(DatabaseHandler::GetGroup($id));
   // }

?>