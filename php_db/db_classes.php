<?php 

//learn mode objects

class ExamItem{
  public $ExamName;
  public $Information;
  public $Version;
  public $Questions;

  function __construct($examName, $information, $version, $questions) {
    $this->ExamName=$examName;
    $this->Information=$information;
    $this->Version=$version;
    $this->Questions=$questions;	   
  }
}

class QuestionItem{
  public $Question;
  public $Base64Img;
  public $Answers;

  function __construct($question, $base64Img, $answers) {
    $this->Question=$question;
    $this->Base64Img=$base64Img;
    $this->Answers=$answers;   
  }
}

class AnswerItem{
  public $Answer;
  public $IsCorrect;

  function __construct($answer, $isCorrect) {
    $this->Answer=$answer;
    $this->IsCorrect=$isCorrect;
  }
}

class JSONConfig{ 
  public $OrganisationName;
  public $OrganisationInformations;
  public $Logo_Base64;
  public $ExamScriptAddres;
  public $examItems;

  function __construct($organisationName, $organisationInformations, $logo_Base64, $examScriptAddres, $ExamItems) {
    $this->OrganisationName=$organisationName;
    $this->OrganisationInformations=$organisationInformations;
    $this->Logo_Base64=$logo_Base64;
    $this->ExamScriptAddres=$examScriptAddres;
    $this->examItems=$ExamItems;
  }
}

?>