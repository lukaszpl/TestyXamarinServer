<?php 
    require 'DatabaseHandler.php';
    //$token = $_POST["token"];
    $organisationName = $_POST["organisationName"];
    $organisationInformations = $_POST["organisationInformations"];
    $logo_Base64 = $_POST["logo_Base64"];
    $examScriptAddres = $_POST["examScriptAddres"];
    //if($token == ""){
        echo json_encode(DatabaseHandler::SetConfiguration($organisationName, $organisationInformations, $logo_Base64, $examScriptAddres));
   // }

?>