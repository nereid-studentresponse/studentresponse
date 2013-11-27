
<?php

class Student {

  private $id;
  private $name;
  private $yearofstudy;
  private $confused

  function __construct($id, $name, $yearofstudy, $confused){
      $this->id=$id;
      $this->name=$name;
      $this->yearofstudy=$yearofstudy;
      $this->confused=$confused;
  }

  // Constructor from DB row
  function __construct($row) {
    $this->id=$row['id'];
    $this->name=$row['name'];
    $this->yearofstudy=$row['yearofstudy'];
    $this->confused=$row['confused'];
  }
  
  function getId() {
    return $this->id;
  }
  
  function getName() {
    return $this->name;
  }

  function getYearOfStudy() {
    return $this->yearofstudy;
  }

  function isConfused() {
    return $this->confused;
  }

}

?>