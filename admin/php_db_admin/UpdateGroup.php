<?php 
    require 'DatabaseHandler.php';
    //$token = $_POST["token"];
    //if($token == ""){
        $id = $_POST["Id"];
        $name= $_POST["Name"];
        echo json_encode(DatabaseHandler::UpdateGroup($id, $name));
   // }

?>