<?php
// Start the session
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=web', 'admin', 'pass123');
if(isset($_POST['lg'])){
    $exct = "select * from admin";
    $test = $pdo->query($exct);
    foreach ($test as $row) {
        if($_POST['uname']==$row['username'] && password_verify($_POST['psw'],$row['pass'])){
            $_SESSION['id'] = $row['username'];
            header("Location: updateBalanced.php");
            exit;

        }else{
            echo 'fail to login';
        }
        }  
}
$exct = "select * from admin";
$test = $pdo->query($exct);
        $count = 0;
        foreach ($test as $row) {
          if('admin'==$row['username'] && password_verify('1',$row['pass'])){
              $count = 1;
          }else{
          }
        }
        if ($count == 0) {
          $exct = "insert into admin values ('admin', :pass)";
          $test = $pdo->prepare($exct);
          $hash = password_hash("1",PASSWORD_DEFAULT);
          $test->bindParam(':pass', $hash);
          $test->execute();
        } else {
        }   
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {font-family: Arial, Helvetica, sans-serif;}
form {border: 3px solid #f1f1f1;}

input[type=text], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

button {
  background-color: #04AA6D;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}

button:hover {
  opacity: 0.8;
}

.cancelbtn {
  width: auto;
  padding: 10px 18px;
  background-color: #f44336;
}

.imgcontainer {
  text-align: center;
  margin: 24px 0 12px 0;
}

img.avatar {
  width: 40%;
  border-radius: 50%;
}

.container {
  padding: 16px;
}

span.psw {
  float: right;
  padding-top: 16px;
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
  span.psw {
     display: block;
     float: none;
  }
  .cancelbtn {
     width: 100%;
  }
}
</style>
</head>
<body>

<h2>Login Form</h2>
<form action="#" method="post">
  <div class="imgcontainer">
    <img src="img_avatar2.png" alt="Avatar" class="avatar">
  </div>

  <div class="container">
    <label for="uname"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="uname" required>

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required>
        
    <button type="submit" name="lg">Login</button>
    
  </div>

  <div class="container" style="background-color:#f1f1f1">
    <span class="link"><a href="test.php">customer module</a></span>
  </div>
</form>

</body>
</html>









