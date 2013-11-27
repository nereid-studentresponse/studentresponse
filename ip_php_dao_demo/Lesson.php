<?php

//include_once "Course.php";

class Lesson {

  private $id;
  private $title;
  private $subject;
  private $nblesson;
  private $course //This should be the entire object? or just the id (like a "pointer")?

  function __construct($id, $title, $subject, $nblesson, $course){
      $this->id=$id;
      $this->title=$title;
      $this->subject=$subject;
	  $this->nblesson=$nblesson;
	  $this->course= $course;
  }
  
  function getId() {
    return $this->id;
  }
  
  function getTitle() {
    return $this->title;
  }
  
  function getSubject() {
    return $this->subject;
  }
  
  function getLessonNumber() {
    return $this->nblesson;
  }
  
  function getCourse() {
    return $this->course;
  }

}

?>