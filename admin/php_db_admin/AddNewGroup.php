<?php 
    require 'DatabaseHandler.php';
    //$token = $_POST["token"];
    //if($token == ""){
        $name= $_POST["Name"];
        echo json_encode(DatabaseHandler::AddNewGroup($name));
   // }

?>