<?php 
    require 'DatabaseHandler.php';
    //$token = $_POST["token"];
    //if($token == ""){
        $Id = $_POST["Id"];
        echo json_encode(DatabaseHandler::DeleteHistoryItem($Id));
   // }

?>