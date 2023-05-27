<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

 
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];


  if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    // Display an error message if any fields are empty
    echo "Please fill in all fields.";
  } elseif ($password != $confirm_password) {
   
    echo "Passwords do not match.";
  } else {
   
    $conn = new mysqli("localhost", "username", "password", "database_name");
    
   
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    
 
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
     
      echo "This email is already registered.";
    } else {
  
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      
      $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $username, $email, $hashed_password);
      $stmt->execute();
      
     
      $conn->close();

  
      header("Location: success.php");
      exit();
    }
  }
}
?>
