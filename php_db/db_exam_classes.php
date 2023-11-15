<?php 

class ExamModeExamItem{
  public $Id;
  public $ExamName;
  public $Information;
  public $Questions;

  function __construct($id, $examName, $information, $questions) {
    $this->Id=$id;
    $this->ExamName=$examName;
    $this->Information=$information;
    $this->Questions=$questions;	   
  }
}

class ExamModeQuestionItem{
  public $Id;
  public $Question;
  public $Base64Img;
  public $Answers;

  function __construct($id, $question, $base64Img, $answers) {
    $this->Id=$id;
    $this->Question=$question;
    $this->Base64Img=$base64Img;
    $this->Answers=$answers;   
  }
}

class ExamModeAnswerItem implements JsonSerializable {
  public $Id;
  public $Answer;
  public $UserAnswer;
  public $IsCorrect;

  function __construct($id, $answer, $isCorrect, $userAnswer) {
    $this->Id=$id;
    $this->Answer=$answer;
    $this->IsCorrect=$isCorrect;
    $this->UserAnswer=$userAnswer;
  }

  public function jsonSerialize(){
    return ['Id' => $this->Id,
    'Answer' => $this->Answer,
    'UserAnswer' => $this->UserAnswer
    ];
  }
}

class ExamModeAnswerItemToSaveJson{
    public $Id;
    public $Answer;
    public $UserAnswer;
    public $IsCorrect;
  
    function __construct($id, $answer, $isCorrect, $userAnswer) {
      $this->Id=$id;
      $this->Answer=$answer;
      $this->IsCorrect=$isCorrect;
      $this->UserAnswer=$userAnswer;
    }
  }

?>