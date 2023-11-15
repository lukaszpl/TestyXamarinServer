<?php
	require 'php_db/db_methods.php';
	$operation = $_GET["operation"];
	$token = $_GET["token"];

	if(($operation == "GETEXAM") && ($token != "")){		
		echo json_encode(DatabaseExamHandler::getActiveExamForUser($token));
	}
	else{
		//POST		
		$POSToperation = $_POST["POSToperation"];
		$POSTtoken = $_POST["POSTtoken"];
		$POSTexamId = $_POST["POSTExamId"];
		$POSTexamdata = $_POST["POSTdata"];
		if(($POSToperation == "SENDEXAM") && ($POSTtoken != "")){
			if(DatabaseExamHandler::saveExamResults($POSTexamdata, $POSTexamId, $POSTtoken)){
				echo "Odpowiedź serwera: Odpowiedzi zostały przesłane";
			}else{
				echo "Egzamin nie jest już aktywny lub wystąpił jakiś problem, nie przesłano odpowiedzi :( Pilnie skontaktuj się z prowadzącym!";
			}
		}else{
			echo "NULL";
		}
	}
	
?>