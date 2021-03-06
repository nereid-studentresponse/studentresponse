
<?php

include_once "DB.php";
include_once "Course.php";
include_once "Student.php";

class CourseDAO {

  private $dbh; // This is an instance of Class_DB to be injected in the functions.

  function __construct($dbh){
      $this->dbh=$dbh;
  }
  function dbConnect(){
      $dbConnection=$this->dbh->createConnexion();
      return $dbConnection;
  }

  function get($id) {
   echo "<br>Getting course id= $id<br>";
   
   $dbConnection=$this->dbConnect();
   $query=$dbConnection->prepare("SELECT * FROM course WHERE id = :id ");
   $query->bindParam(':id', $id);
   $query->execute();
   $result=$query->fetch(PDO::FETCH_ASSOC);
   
   $course = new Course($result['id'], $result['title'], $result['description']);
   
   
   return $course;
   
  }

  function listAll() {
    echo "<br>Listiiiiing<br>";
    
    $dbConnection=$this->dbConnect();
    $query=$dbConnection->prepare("SELECT * FROM course ");
    $query->execute();
    $result=$query->fetchAll();
    
    
    $courseArray;
    $arrayCounter = 0;
    
    foreach ($result as &$row) {
      $tempCourse = new Course($row['id'], $row['title'], $row['description']);
      $courseArray[$arrayCounter] = $tempCourse;
      $arrayCounter++;
    }
    
    
    
    // echo print_r($result);
    // echo "<br><br>";
    // echo print_r($courseArray);
    return $courseArray;
  }
  
  //create a course and bind it to the teacher
  function insert($course, $teacher) {
    echo "<br>Inserting course id=". $course->getId() . " <br>";

    $dbConnection=$this->dbConnect();
    $query=$dbConnection->prepare("INSERT INTO `course` (`title`, `description`) VALUES (:title, :description) ");
    //$query->bindParam(':id', $course->getId());
    $query->bindParam(':title', $course->getTitle());
    $query->bindParam(':description', $course->getDescription());
	
	$count = $query->execute();
	$courseId = $dbConnection->lastInsertId();
	
	$query2=$dbConnection->prepare("INSERT INTO `creation` (`id_course`, `id_teacher`) VALUES (:id_course, :id_teacher) ");
	$query2->bindParam(':id_course', $courseId);
    $query2->bindParam(':id_teacher', $teacher->getId());
	
    $count = $count && $query2->execute();
    
    return array("result" => $count > 0, "courseId" => $courseId);
  }

  function update($course) {
    $dbConnection=$this->dbConnect();
    $query=$dbConnection->prepare("UPDATE `course` SET  `title`=:tile, `description`=:description WHERE `id` = :id ");
    $query->bindParam(':id', $course->getId());
    $query->bindParam(':title', $course->getTitle());
    $query->bindParam(':description', $course->getDescription());
    $count = $query->execute();
    
    return $count > 0;
  }

  function delete($course) {
    $dbConnection=$this->dbConnect();
    $query=$dbConnection->prepare("DELETE FROM `course` WHERE `id` = :id ");
    $query->bindParam(':id', $course->getId());
    $count = $query->execute();
    
    return $count > 0;
  }

  /**
   * Get all the courses a student is enrolled in
   */
  function getByStudent($student) {
    $dbConnection=$this->dbConnect();
    $query=$dbConnection->prepare("SELECT c.id, c.title, c.description FROM course c JOIN enroll e ON c.id = e.id_course WHERE e.id_student = :sid ");
    $query->bindParam(':sid', $student->getId());
    $query->execute();
    $result=$query->fetchAll();
    
    
    $courseArray = array();
    $arrayCounter = 0;
    
    foreach ($result as &$row) {
      $tempCourse = new Course($row['id'], $row['title'], $row['description']);
      $courseArray[$arrayCounter] = $tempCourse;
      $arrayCounter++;
    }

    return $courseArray;
  }
  
   /**
   * Get all the courses a teacher is responsible for
   */
  function getByTeacher($teacher) {
    $dbConnection=$this->dbConnect();
    $query=$dbConnection->prepare("SELECT c.id, c.title, c.description FROM course c JOIN creation cr ON c.id = cr.id_course WHERE cr.id_teacher = :sid ");
    $query->bindParam(':sid', $teacher->getId());
    $query->execute();
    $result=$query->fetchAll();
    
    
    $courseArray = array();
    $arrayCounter = 0;
    
    foreach ($result as &$row) {
      $tempCourse = new Course($row['id'], $row['title'], $row['description']);
      $courseArray[$arrayCounter] = $tempCourse;
      $arrayCounter++;
    }

    return $courseArray;
  }

  /**
   * Returns the courses avaible for a given student. For now just returns courses he's not enrolled in.
   */
  function getAvailableByStudent($student) {
    $dbConnection=$this->dbConnect();
    $query=$dbConnection->prepare("SELECT c.id, c.title, c.description FROM course c WHERE c.id NOT IN (SELECT e.id_course FROM enroll e WHERE e.id_student = :sid and e.id_course = c.id) ");
    $query->bindParam(':sid', $student->getId());
    $query->execute();
    $result=$query->fetchAll();
    
    
    $courseArray;
    $arrayCounter = 0;
    
    foreach ($result as &$row) {
      $tempCourse = new Course($row['id'], $row['title'], $row['description']);
      $courseArray[$arrayCounter] = $tempCourse;
      $arrayCounter++;
    }

    return $courseArray;
  }

}

?>
