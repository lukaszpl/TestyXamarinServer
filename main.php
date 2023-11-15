<?php
    require 'php_db/db_methods.php';
    echo json_encode(DatabaseHandler::getJsonConfig());
?>