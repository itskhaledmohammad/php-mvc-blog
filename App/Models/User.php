<?php
namespace App\Models;
use PDO;

class User extends \Core\Model {
  // returns true if no match is found
  public static function checkUserName($username) {
    try {
      $db = static::getDB();
      $query = 'SELECT * FROM users WHERE username = ?';
      $statement = $db->prepare($query);
      $statement->execute([$username]);
      $count = $statement->rowCount();
      if ($count == 0) {
        return true;
      }
      else {
        return false;
      }
    }
    catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  // returns true if no match is found
  public static function checkEmail($email) {
    try {
      $db = static::getDB();
      $query = 'SELECT * FROM USERS WHERE email = ?';
      $statement = $db->prepare('SELECT * FROM USERS WHERE email = ?');
      $statement->execute([$email]);
      $count = $statement->rowCount();
      if ($count == 0) {
        return true;
      }
      else {
        return false;
      }
    }
    catch (PDOException $e) {
      print_r($e);
      // echo $e->getMessage();
    }
  }



  public static function registerUser(
    $first_name,
    $last_name,
    $username,
    $email,
    $password
  ) {
    try {
      $db = static::getDB();
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $query = 'INSERT INTO users(username,email,password,first_name,last_name) VALUES(:username,:email,:password,:first_name,:last_name)';
      $statement = $db->prepare($query);
      $statement->execute(['username'=>$username,'email'=>$email,'password'=>$hashed_password,'first_name'=>$first_name,'last_name'=>$last_name]);
    }
    catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  // returns true if user is logged in
  // and sets the session
  public static function loginUser(
    $email,
    $password
  )
  {
    try {
      $db = static::getDB();
      $query = 'SELECT * from users WHERE email = ?';
      $statement = $db->prepare($query);
      $statement->execute([$email]);
      $count = $statement->rowCount();
      $user = $statement->fetch(PDO::FETCH_ASSOC);
      $db=null;
      $hash = $user['password'];
      if ($count == 0) {
        echo 'login failed';
        return false;
      } else {
        if (password_verify($password, $hash)) {
          $_SESSION['email'] = $user['email'];
          $_SESSION['username'] = $user['username'];
          $_SESSION['password'] = $user['password'];
          return true;
        }
        else {
          return false;
        }
      }
    }
    catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

}
