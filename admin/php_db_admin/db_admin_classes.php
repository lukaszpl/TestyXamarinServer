<?php

class Config
{
  public $OrganisationName;
  public $OrganisationInformations;
  public $Logo_Base64;
  public $ExamScriptAddres;

  function __construct($organisationName, $organisationInformations, $logo_Base64, $examScriptAddres)
  {
    $this->OrganisationName = $organisationName;
    $this->OrganisationInformations = $organisationInformations;
    $this->Logo_Base64 = $logo_Base64;
    $this->ExamScriptAddres = $examScriptAddres;
  }
}
class ExamItem
{
  public $ExamName;
  public $Information;
  public $Version;
  public $Questions;

  function __construct($examName, $information, $version, $questions)
  {
    $this->ExamName = $examName;
    $this->Information = $information;
    $this->Version = $version;
    $this->Questions = $questions;
  }
}

class QuestionItem
{
  public $Id;
  public $Question;
  public $Base64Img;
  public $Answers;

  function __construct($id, $question, $base64Img, $answers)
  {
    $this->Id = $id;
    $this->Question = $question;
    $this->Base64Img = $base64Img;
    $this->Answers = $answers;
  }
}

class AnswerItem
{
  public $Answer;
  public $IsCorrect;

  function __construct($answer, $isCorrect)
  {
    $this->Answer = $answer;
    $this->IsCorrect = $isCorrect;
  }
}

class GroupItem
{
  public $Id;
  public $Name;

  function __construct($id, $name)
  {
    $this->Id = $id;
    $this->Name = $name;
  }
}

class UserItem
{
  public $Id;
  public $UserToken;
  public $Name;
  public $Surname;
  public $Group_id;
  public $Group_name;

  function __construct($id, $userToken, $name, $surname, $group_id, $group_name)
  {
    $this->Id = $id;
    $this->UserToken = $userToken;
    $this->Name = $name;
    $this->Surname = $surname;
    $this->Group_id = $group_id;
    $this->Group_name = $group_name;
  }
}

class ActiveExamItem
{
  public $Id;
  public $User_Id;
  public $Exam_Id;
  public $ExamItem;
  public $User;
  function __construct($id, $user_id, $exam_id, $examItem, $user)
  {
    $this->Id = $id;
    $this->User_Id = $user_id;
    $this->Exam_Id = $exam_id;
    $this->ExamItem = $examItem;
    $this->User = $user;
  }
}

class HistoryTableItem
{
  public $Id;
  public $Exam_id;
  public $User_id;
  public $User;
  public $Date;
  function __construct($id, $exam_id, $user_id, $user, $date)
  {
    $this->Id = $id;
    $this->Exam_id = $exam_id;
    $this->User_id = $user_id;
    $this->User= $user;
    $this->Date = $date;
  }
}
