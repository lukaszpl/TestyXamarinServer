<?php 
    require 'DatabaseHandler.php';
    //$token = $_POST["token"];
    //if($token == ""){
        $id = $_POST["Id"];
        echo DatabaseHandler::GetHistoryJson($id);
   // }

?>