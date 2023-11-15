<?php 
    require 'DatabaseHandler.php';
    //$token = $_POST["token"];
    //if($token == ""){
        $userId = $_POST["Id"];
        echo json_encode(DatabaseHandler::DeleteUser($userId));
   // }

?>