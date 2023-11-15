<?php 
    require 'DatabaseHandler.php';
    //$token = $_POST["token"];
    //if($token == ""){
        $id = $_POST["Id"];
        $Usertoken = $_POST["UserToken"];
        $name = $_POST["Name"];
        $surname = $_POST["Surname"];
        $group_id = $_POST["Group_id"];
        echo json_encode(DatabaseHandler::UpdateUser($id, $Usertoken, $name, $surname, $group_id));
   // }

?>