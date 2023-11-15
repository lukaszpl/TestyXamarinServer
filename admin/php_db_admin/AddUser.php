<?php 
    require 'DatabaseHandler.php';
    //$token = $_POST["token"];
    //if($token == ""){
        $Usertoken = $_POST["UserToken"];
        $name = $_POST["Name"];
        $surname = $_POST["Surname"];
        $group_id = $_POST["Group_id"];
        echo json_encode(DatabaseHandler::AddUser($Usertoken, $name, $surname, $group_id));
   // }

?>