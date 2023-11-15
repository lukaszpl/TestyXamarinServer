<?php
require_once 'db_classes.php';


class DatabaseHandler
{
	//list of QuestionItem
	function getPublicExam($id)
	{
		require 'db_connect.php';
		$sql = "SELECT * FROM publicquestions JOIN publicquestionstoexam ON publicquestionstoexam.examquestion_id = publicquestions.id WHERE publicquestionstoexam.exam_id = $id";
		$result = $connect->query($sql);
		$listOfQuestions = array();
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				//php... bezsensowna operacja, bo język nie jest typowany :)
				$ans = json_decode($row['answers']);
				$ansconv = null;
				foreach ($ans as $value) {
					$ansconv = $value;
				}

				$listOfQuestions[] = new QuestionItem($row['question'], $row['base64Img'], $ansconv);
			}
		}
		return $listOfQuestions;
	}

	function getPublicExams()
	{
		require 'db_connect.php';
		$sql = "SELECT * FROM publicexamitem";
		$result = $connect->query($sql);
		$listOfExams = array();
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$listOfExams[] = new ExamItem($row['examName'], $row['information'], 1, DatabaseHandler::getPublicExam($row['id']));
			}
		}
		return $listOfExams;
	}

	function getJsonConfig()
	{
		require 'db_connect.php';
		$sql = "SELECT * FROM globalconfig";
		$result = $connect->query($sql);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				return new JSONConfig($row['organisationName'], $row['organisationInformations'], $row['logo_Base64'], $row['examScriptAddres'], DatabaseHandler::getPublicExams());
			}
		}
		return null;
	}
}

////////exams
require_once 'db_exam_classes.php';
class DatabaseExamHandler
{
	function getActiveExamForUser($userToken)
	{
		require 'db_connect.php';

		$sql = "SELECT activeexam.exam_id FROM activeexam JOIN users ON activeexam.user_id = users.id WHERE users.userToken = '$userToken'";
		$result = $connect->query($sql);
		$activeExamId;
		if ($result->num_rows > 0) { //jeżeli egzamin dla tokenu istnieje
			while ($row = $result->fetch_assoc()) {
				$activeExamId = $row['exam_id'];
			}
			//
			$sql2 = "SELECT examquestions.id, examquestions.question, examquestions.base64Img, examquestions.answers FROM examquestions JOIN questionstoexam ON examquestions.id = questionstoexam.examquestion_id WHERE questionstoexam.exam_id = $activeExamId";
			$result2 = $connect->query($sql2);

			$listOfQuestions = array();
			if ($result2->num_rows > 0) {
				while ($row = $result2->fetch_assoc()) {
					//php... bezsensowna operacja, bo język nie jest typowany :)
					$listOfAnswers = array();
					$ans = json_decode($row['answers']);
					$ansconv = null;
					foreach ($ans as $value) {
						$ansconv = $value;
						foreach ($value as $answers) {
							$listOfAnswers[] = new ExamModeAnswerItem($answers->Id, $answers->Answer, $answers->IsCorrect, false);
						}
					}
					$listOfQuestions[] = new ExamModeQuestionItem($row['id'], $row['question'], $row['base64Img'], $listOfAnswers);
				}
			}

			$sql3 = "SELECT * FROM examitem where examitem.id = $activeExamId";
			$result3 = $connect->query($sql3);
			if ($result3->num_rows > 0) {
				while ($row = $result3->fetch_assoc()) {
					return new ExamModeExamItem($row['id'], $row['examName'], $row['information'], $listOfQuestions);
				}
			}
		} else {
			return "inactive exam";
		}
	}
	//////////////////////////////////////////////////////////////////////////

	function saveExamResults($examJson, $examId, $userToken)
	{
		if (DatabaseExamHandler::checkExamIsActive($userToken)) {
			$examObj = json_decode($examJson);
			$toSaveQuestionsObj = array();
			foreach ($examObj as $question) {
				$answersFromDb = DatabaseExamHandler::getAnswersObjFormDb($question->Id);
				$toSaveAnswersObj = array();
				foreach ($question->Answers as $answer) {
					$toSaveAnswersObj[] = new ExamModeAnswerItemToSaveJson($answer->Id, DatabaseExamHandler::getAnswerTextFromObj($answer->Id, $answersFromDb), DatabaseExamHandler::getCorrectAnswerFromObj($answer->Id, $answersFromDb), $answer->UserAnswer);
				}
				$toSaveQuestionsObj[] = new ExamModeQuestionItem($question->Id, DatabaseExamHandler::getQuestionTitle($question->Id), DatabaseExamHandler::getBase64Img($question->Id), $toSaveAnswersObj);
			}
			$examToSave = new ExamModeExamItem($examId, DatabaseExamHandler::getExamField($examId, "examName"), DatabaseExamHandler::getExamField($examId, "information"), $toSaveQuestionsObj);
			//
			require 'db_connect.php';
			$userId = DatabaseExamHandler::getUserId($userToken);
			if ($userId) {
				$examJsonToSave = json_encode($examToSave, JSON_UNESCAPED_UNICODE);
				$sql = "INSERT INTO `examshistory` (`id`, `exam_id`, `user_id`, `exam_json`, `date`) VALUES (NULL, '$examId', $userId, '$examJsonToSave', NOW());";
				$result = $connect->query($sql);
				if ($result == true) {
					DatabaseExamHandler::setOffActiveExam($userId);
				} else {
					echo "Error: " . $sql . "<br>" . $connect->error;
				}
				//
				return true;
			}else{
				echo "user not exists";
			}
		} else {
			echo false;
		}
	}

	function getAnswersObjFormDb($questionid)
	{
		require 'db_connect.php';
		$sql = "SELECT answers FROM `examquestions` WHERE id = $questionid";
		$result = $connect->query($sql);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				return json_decode($row['answers']);
			}
		}
	}
	function getQuestionTitle($questionid)
	{
		require 'db_connect.php';
		$sql = "SELECT question FROM `examquestions` WHERE id = $questionid";
		$result = $connect->query($sql);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				return $row['question'];
			}
		}
	}
	function getBase64Img($questionid)
	{
		require 'db_connect.php';
		$sql = "SELECT base64Img FROM `examquestions` WHERE id = $questionid";
		$result = $connect->query($sql);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				return $row['base64Img'];
			}
		}
	}

	function getExamField($examId, $field)
	{
		require 'db_connect.php';
		$sql = "SELECT $field FROM `examitem` WHERE examitem.id = $examId";
		$result = $connect->query($sql);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				return $row[$field];
			}
		}
	}

	function getCorrectAnswerFromObj($answerid, $array)
	{
		foreach ($array as $answers) {
			foreach ($answers as $answer) {
				if ($answer->Id == $answerid)
					return $answer->IsCorrect;
			}
		}
	}

	function getAnswerTextFromObj($answerid, $array)
	{
		foreach ($array as $answers) {
			foreach ($answers as $answer) {
				if ($answer->Id == $answerid)
					return $answer->Answer;
			}
		}
	}

	function getUserId($userToken)
	{
		require 'db_connect.php';
		$sql = "SELECT id FROM `users` WHERE users.userToken = '$userToken'";
		$result = $connect->query($sql);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				return $row['id'];
			}
		} else {
			return false;
		}
	}

	function checkExamIsActive($userToken)
	{
		require 'db_connect.php';

		$sql = "SELECT activeexam.exam_id FROM activeexam JOIN users ON activeexam.user_id = users.id WHERE users.userToken = '$userToken'";
		$result = $connect->query($sql);
		$activeExamId;
		if ($result->num_rows > 0) { //jeżeli egzamin dla tokenu istnieje
			return true;
		}
		return false;
	}

	function setOffActiveExam($userId)
	{
		require 'db_connect.php';
		$sql = "DELETE FROM `activeexam` WHERE `activeexam`.`user_id` = $userId";
		$result = $connect->query($sql);
	}
}
