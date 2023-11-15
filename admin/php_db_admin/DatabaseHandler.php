<?php

use DatabaseHandler as GlobalDatabaseHandler;

require_once 'db_admin_classes.php';

class DatabaseHandler
{
    static function GetConfiguration()
    {
        require 'db_admin_connect.php';
        $sql = "SELECT * FROM `globalconfig`";
        $result = $connect->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return new Config($row['organisationName'], $row['organisationInformations'], $row['logo_Base64'], $row['examScriptAddres']);
            }
        }
    }
    static function SetConfiguration($organisationName, $organisationInformations, $logo_Base64, $examScriptAddres)
    {
        require 'db_admin_connect.php';
        $organisationName = $connect->real_escape_string($organisationName);
        $organisationInformations = $connect->real_escape_string($organisationInformations);
        $logo_Base64 = $connect->real_escape_string($logo_Base64);
        $examScriptAddres = $connect->real_escape_string($examScriptAddres);
        $sql = "UPDATE `globalconfig` SET `organisationName` = '$organisationName', `organisationInformations` = '$organisationInformations', `logo_Base64` = '$logo_Base64', `examScriptAddres` = '$examScriptAddres' WHERE `globalconfig`.`id` = 1;";
        $result = $connect->query($sql);
        if ($result) {
            return true;
        }
        return false;
    }
    static function GetAllPublicExams()
    {
        require 'db_admin_connect.php';
        $list = array();
        $sql = "SELECT * FROM `publicexamitem` ";
        $result = $connect->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $list[] = array($row['id'], $row['examName'], $row['information']);
            }
        }
        return $list;
    }
    static function GetPublicExam($examId)
    {
        require 'db_admin_connect.php';
        $sql = "SELECT * FROM publicquestions JOIN publicquestionstoexam ON publicquestionstoexam.examquestion_id = publicquestions.id WHERE publicquestionstoexam.exam_id = $examId";
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

                $listOfQuestions[] = new QuestionItem($row['examquestion_id'], $row['question'], $row['base64Img'], $ansconv);
            }
        }
        return $listOfQuestions;
    }
    static function GetPublicExamItem($examId)
    {
        require 'db_admin_connect.php';
        $sql = "SELECT * FROM `publicexamitem` WHERE publicexamitem.id = $examId";
        $result = $connect->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return array($row['examName'], $row['information']);
            }
        }
        return null;
    }
    static function DeletePublicExam($examId)
    {
        require 'db_admin_connect.php';
        $sql = "DELETE FROM `publicexamitem` WHERE `publicexamitem`.`id` = $examId";
        $result = $connect->query($sql);
        if ($connect->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
    static function UpdatePublicExam($examId, $examName, $information)
    {
        require 'db_admin_connect.php';
        $examName = $connect->real_escape_string($examName);
        $information = $connect->real_escape_string($information);
        $sql = "UPDATE `publicexamitem` SET `examName` = '$examName ', `information` = '$information ' WHERE `publicexamitem`.`id` = $examId;";
        if ($connect->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
    static function AddPublicExam($examName, $information)
    {
        require 'db_admin_connect.php';
        $examName = $connect->real_escape_string($examName);
        $information = $connect->real_escape_string($information);
        $sql = "INSERT INTO `publicexamitem` (`id`, `examName`, `information`) VALUES (NULL, '$examName', '$information');";
        if ($connect->query($sql) === TRUE) {
            $last_id = $connect->insert_id;
            return $last_id;
        } else {
            return false;
        }
    }
    static function GetPublicQuestion($questionId)
    {
        require 'db_admin_connect.php';
        $sql = "SELECT * FROM `publicquestions` WHERE id=$questionId";
        $result = $connect->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return new QuestionItem($row['id'], $row['question'], $row['base64Img'], $row['answers']);
            }
        }
    }
    static function AddPublicQuestion($examId, $question, $base64Img, $answers)
    {
        require 'db_admin_connect.php';
        $question = $connect->real_escape_string($question);
        $base64Img = $connect->real_escape_string($base64Img);
        $answers = $connect->real_escape_string($answers);
        $sql = "INSERT INTO `publicquestions` (`id`, `question`, `base64Img`, `answers`) VALUES (NULL, '$question', '$base64Img', '$answers');";
        if ($connect->query($sql) === TRUE) {
            $last_id = $connect->insert_id;
            //
            $sql2 = "INSERT INTO `publicquestionstoexam` (`id`, `exam_id`, `examquestion_id`) VALUES (NULL, '$examId', '$last_id');";
            $connect->query($sql2);
            //         
            return $last_id;
        } else {
            return false;
        }
    }
    static function UpdatePublicQuestion($id, $question, $base64Img, $answers)
    {
        require 'db_admin_connect.php';
        $question = $connect->real_escape_string($question);
        $base64Img = $connect->real_escape_string($base64Img);
        $answers = $connect->real_escape_string($answers);
        $sql = "UPDATE `publicquestions` SET `question` = '$question', `base64Img` = '$base64Img', `answers` = '$answers' WHERE `publicquestions`.`id` = $id;";
        $result = $connect->query($sql);
        if ($result) {
            return true;
        }
        return false;
    }
    static function DeletePublicQuestion($questionId)
    {
        require 'db_admin_connect.php';
        $sql = "DELETE FROM `publicquestionstoexam` WHERE `publicquestionstoexam`.`examquestion_id` = $questionId";
        $result = $connect->query($sql);
        $sql2 = "DELETE FROM `publicquestions` WHERE `publicquestions`.`id` = $questionId";;
        $result2 = $connect->query($sql2);
        if (($result) && ($result2)) {
            return true;
        }
        return false;
    }

    //////////
    //Exams
    /////////

    static function AddExam($examName, $information){
        require 'db_admin_connect.php';
        $examName = $connect->real_escape_string($examName);
        $information = $connect->real_escape_string($information);
        $sql = "INSERT INTO `examitem` (`id`, `examName`, `information`) VALUES (NULL, '$examName', '$information');";
        if ($connect->query($sql) === TRUE) {
            $last_id = $connect->insert_id;
            return $last_id;
        } else {
            return false;
        }
    }
    static function DeleteExam($examId){
        require 'db_admin_connect.php';
        $sql = "DELETE FROM `examitem` WHERE `examitem`.`id` = $examId";
        $result = $connect->query($sql);
        if ($connect->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
    static function UpdateExam($examId, $examName, $information){
        require 'db_admin_connect.php';
        $examName = $connect->real_escape_string($examName);
        $information = $connect->real_escape_string($information);
        $sql = "UPDATE `examitem` SET `examName` = '$examName ', `information` = '$information ' WHERE `examitem`.`id` = $examId;";
        if ($connect->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
    static function GetExamItem($examId){
        require 'db_admin_connect.php';
        $sql = "SELECT * FROM `examitem` WHERE examitem.id = $examId";
        $result = $connect->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return array($row['examName'], $row['information']);
            }
        }
        return null;
    }

    static function GetExam($examId){
        require 'db_admin_connect.php';
        $sql = "SELECT * FROM examquestions JOIN questionstoexam ON questionstoexam.examquestion_id = examquestions.id WHERE questionstoexam.exam_id = $examId";
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

                $listOfQuestions[] = new QuestionItem($row['examquestion_id'], $row['question'], $row['base64Img'], $ansconv);
            }
        }
        return $listOfQuestions;
    }

    static function GetAllExams(){
        require 'db_admin_connect.php';
        $list = array();
        $sql = "SELECT * FROM `examitem` ";
        $result = $connect->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $list[] = array($row['id'], $row['examName'], $row['information']);
            }
        }
        return $list;
    }

    static function AddExamQuestion($examId, $question, $base64Img, $answers){
        require 'db_admin_connect.php';
        $question = $connect->real_escape_string($question);
        $base64Img = $connect->real_escape_string($base64Img);
        $answers = $connect->real_escape_string($answers);
        $sql = "INSERT INTO `examquestions` (`id`, `question`, `base64Img`, `answers`) VALUES (NULL, '$question', '$base64Img', '$answers');";
        if ($connect->query($sql) === TRUE) {
            $last_id = $connect->insert_id;
            //
            $sql2 = "INSERT INTO `questionstoexam` (`id`, `exam_id`, `examquestion_id`) VALUES (NULL, '$examId', '$last_id');";
            $connect->query($sql2);
            //         
            return $last_id;
        } else {
            return false;
        }
    }
    static function DeleteExamQuestion($questionId){
        require 'db_admin_connect.php';
        $sql = "DELETE FROM `questionstoexam` WHERE `questionstoexam`.`examquestion_id` = $questionId";
        $result = $connect->query($sql);
        $sql2 = "DELETE FROM `examquestions` WHERE `examquestions`.`id` = $questionId";;
        $result2 = $connect->query($sql2);
        if (($result) && ($result2)) {
            return true;
        }
        return false;
    }

    static function GetExamQuestion($questionId){
        require 'db_admin_connect.php';
        $sql = "SELECT * FROM `examquestions` WHERE id=$questionId";
        $result = $connect->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return new QuestionItem($row['id'], $row['question'], $row['base64Img'], $row['answers']);
            }
        }
    }
    static function UpdateExamQuestion($id, $question, $base64Img, $answers){
        require 'db_admin_connect.php';
        $question = $connect->real_escape_string($question);
        $base64Img = $connect->real_escape_string($base64Img);
        $answers = $connect->real_escape_string($answers);
        $sql = "UPDATE `examquestions` SET `question` = '$question', `base64Img` = '$base64Img', `answers` = '$answers' WHERE `examquestions`.`id` = $id;";
        $result = $connect->query($sql);
        if ($result) {
            return true;
        }
        return false;
    }
    
    ///////////
    //users & groups
    ///////////

    static function AddNewGroup($name){
        require 'db_admin_connect.php';
        $name = $connect->real_escape_string($name);
        $sql = "INSERT INTO `usersgroups` (`id`, `name`) VALUES (NULL, '$name');";
        if ($connect->query($sql) === TRUE) {
            $last_id = $connect->insert_id;
            return $last_id;
        } else {
            return false;
        }
    }
    static function DeleteGroup($id){
        require 'db_admin_connect.php';
        $sql = "DELETE FROM `usersgroups` WHERE `usersgroups`.`id` = $id";
        if ($connect->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
    static function GetAllGroups(){
        require 'db_admin_connect.php';
        $list = array();
        $sql = "SELECT * FROM usersgroups";
        $result = $connect->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $list[] = new GroupItem($row['id'], $row['name']);
            }
        }
        return $list;
    }
    static function GetGroup($id){
        require 'db_admin_connect.php';
        $sql = "SELECT * FROM `usersgroups` WHERE id=$id";
        $result = $connect->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return $row['name'];
            }
        }
    }
    static function UpdateGroup($id, $name){
        require 'db_admin_connect.php';
        $name = $connect->real_escape_string($name);
        $sql = "UPDATE `usersgroups` SET `name` = '$name' WHERE `usersgroups`.`id` = $id;";
        if ($connect->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
    
    //
    static function GetAllUsers(){
        require 'db_admin_connect.php';
        $sql = "SELECT * FROM `users`";
        $result = $connect->query($sql);
        $listOfUsers = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $listOfUsers[] = new UserItem($row['id'], $row['userToken'], $row['name'], $row['surname'], $row['group_id'], DatabaseHandler::GetGroup($row['group_id']));
            }
        }
        return $listOfUsers;
    }
    static function GetUser($userId){
        require 'db_admin_connect.php';
        $sql = "SELECT * FROM `users` WHERE users.id = $userId";
        $result = $connect->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return new UserItem($row['id'], $row['userToken'], $row['name'], $row['surname'], $row['group_id'], DatabaseHandler::GetGroup($row['group_id']));
            }
        }
    }
    static function UpdateUser($userId, $userToken, $name, $surname, $group_id){
        require 'db_admin_connect.php';
        $userToken = $connect->real_escape_string($userToken);
        $name = $connect->real_escape_string($name);
        $surname = $connect->real_escape_string($surname);
        $sql = "UPDATE `users` SET `userToken` = '$userToken', `name` = '$name', `surname` = '$surname', `group_id` = '$group_id' WHERE `users`.`id` = $userId;";
        if ($connect->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
    static function AddUser($userToken, $name, $surname, $group_id){        
        require 'db_admin_connect.php';
        $userToken = $connect->real_escape_string($userToken);
        $name = $connect->real_escape_string($name);
        $surname = $connect->real_escape_string($surname);
        $sql = "INSERT INTO `users` (`id`, `userToken`, `name`, `surname`, `group_id`) VALUES (NULL, '$userToken', '$name', '$surname', '$group_id');";
        if ($connect->query($sql) === TRUE) {
            $last_id = $connect->insert_id;
            return $last_id;
        } else {
            return false;
        }
    }
    static function DeleteUser($userId){
        require 'db_admin_connect.php';
        $sql = "DELETE FROM `users` WHERE `users`.`id` = $userId";
        if ($connect->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }


    ///////
    //activeexams
    //////

    static function GetAllActiveExams(){
        require 'db_admin_connect.php';
        $sql = "SELECT * FROM `activeexam`";
        $result = $connect->query($sql);
        $listOfActiveExams = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $listOfActiveExams[] = new ActiveExamItem($row['id'], $row['user_id'], $row['exam_id'], DatabaseHandler::GetExamItem($row['exam_id']), DatabaseHandler::GetUser($row['user_id']));
            }
        }
        return $listOfActiveExams;
    }

    static function AddActiveExam($userId, $examId){
        require 'db_admin_connect.php';
        $sql = "INSERT INTO `activeexam` (`id`, `user_id`, `exam_id`) VALUES (NULL, '$userId', '$examId');";
        if ($connect->query($sql) === TRUE) {
            $last_id = $connect->insert_id;
            return $last_id;
        } else {
            return false;
        }
    }

    static function AddActiveExamForGroup($groupId, $examId){
        $users = DatabaseHandler::GetAllUsersByGroup($groupId);
        foreach ($users as $user){
            DatabaseHandler::AddActiveExam($user->Id, $examId);
        }
        return true;
    }

    static function GetAllUsersByGroup($groupId){
        require 'db_admin_connect.php';
        $sql = "SELECT * FROM `users` WHERE users.group_id = $groupId";
        $result = $connect->query($sql);
        $listOfUsers = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $listOfUsers[] = new UserItem($row['id'], $row['userToken'], $row['name'], $row['surname'], $row['group_id'], DatabaseHandler::GetGroup($row['group_id']));
            }
        }
        return $listOfUsers;
    }

    static function DeleteActiveExam($ActiveExamId){
        require 'db_admin_connect.php';
        $sql = "DELETE FROM `activeexam` WHERE `activeexam`.`id` = $ActiveExamId";
        if ($connect->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    //////
    //history
    /////

    static function GetHistoryTable(){
        require 'db_admin_connect.php';
        $sql = "SELECT * FROM `examshistory`";
        $result = $connect->query($sql);
        $listOfHistory = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $listOfHistory[] = new HistoryTableItem($row['id'], $row['exam_id'], $row['user_id'], DatabaseHandler::GetUser($row['user_id']), $row['date']);
            }
        }
        return $listOfHistory;
    }

    static function GetHistoryJson($recordId){
        require 'db_admin_connect.php';
        $sql = "SELECT exam_json FROM `examshistory` WHERE id=$recordId";
        $result = $connect->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return $row['exam_json'];
            }
        }
    }

    static function DeleteHistoryItem($recordId){
        require 'db_admin_connect.php';
        $sql = "DELETE FROM `examshistory` WHERE `examshistory`.`id` = $recordId";
        if ($connect->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

}
