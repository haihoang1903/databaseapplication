<?php
$pdo = new PDO('mysql:host=localhost;dbname=web', 'customer', 'pass123');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if(isset($_POST['lg'])){
    try{
      $check = 0;
      for ($i = 0;$i < strlen($_POST['phone']);++$i) {
        if((ord('0')-1) < ord($_POST['phone'][$i]) && (ord('9')+1) > ord($_POST['phone'][$i]) ){

        } else {
          echo 'phone must be numbers';
          echo "<br>";
          $check = 1;
        }
      }
      for ($i = 0;$i < strlen($_POST['id_number']);++$i) {
        if((ord('0')-1) < ord($_POST['id_number'][$i]) && (ord('9')+1) > ord($_POST['id_number'][$i]) ){

        } else {
          echo 'Identification number must be number';
          echo "<br>";
          $check = 1;
        }
      }
        if(12==strlen($_POST['id_number'])) {

        } else {
          echo 'Identification Number length must be 12';
          echo "<br>";
            $check = 1;
        }
        if(10==strlen($_POST['phone'])) {

        } else {
          echo 'phone number length must be 10';
          echo "<br>";
            $check = 1;
        }
        if($check == 0){
          $exct = "insert into customeraccounts (identificationNumber,email,phone,pass,branch,balance,fname,lname,address,city,country) values(:identificationNum,:email,:phone,:pass,:branch,:balance,:fname,:lname,:address,:city,:country)";
          $test = $pdo->prepare($exct);
          $test->bindParam(':email', $_POST['email']);
          $test->bindParam(':phone', $_POST['phone']);
          $hash = password_hash($_POST['psw'],PASSWORD_DEFAULT);
          $test->bindParam(':pass', $hash);
          $test->bindParam(':identificationNum', $_POST['id_number']);
          $branch = (int) $_POST['branch'];
          $test->bindParam(':branch',$branch);
          $firstBalance = (double)0;
          $test->bindParam(':balance', $firstBalance);
          $test->bindParam(':fname', $_POST['fname']);
          $test->bindParam(':lname', $_POST['lname']);
          $test->bindParam(':address', $_POST['address']);
          $test->bindParam(':city', $_POST['city']);
          $test->bindParam(':country', $_POST['country']);
          $test->execute();
        }

    }catch(PDOException $e){
      $error = $e->getMessage();
      echo '<script>window.alert("An error has occurred'.$error.'")</script>';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {font-family: Arial, Helvetica, sans-serif;}
form {border: 3px solid #f1f1f1;}

input[type=text], input[type=password], input[type=email] {
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

<h2>Register Form</h2>

<form action="#" method="post">
  <div class="imgcontainer">
    <img src="img_avatar2.png" alt="Avatar" class="avatar">
  </div>

  <div class="container">
    <label for="email"><b>Email</b></label>
    <input type="text" placeholder="Enter email" name="email" required>
    <label for="phone"><b>Phone</b></label>
    <input type="text" placeholder="Enter phone" name="phone" required>
    <label for="fname"><b>First Name</b></label>
    <input type="text" placeholder="Enter First Name" name="fname" required>
    <label for="lname"><b>Last Name</b></label>
    <input type="text" placeholder="Enter Last Name" name="lname" required>
    <label for="address"><b>Address</b></label>
    <input type="text" placeholder="Enter Address" name="address" required>
    <label for="city"><b>City</b></label>
    <input type="text" placeholder="Enter City" name="city" required>
    <label for="country"><b>Country</b></label>
    <input type="text" placeholder="Enter Country" name="country" required>
    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required>
    <label for="IndentificationNum"><b>Identification Number</b></label>
    <input type="text" placeholder="Enter Identification Number" name="id_number" required>
    <label for="branch"><b>Branch</b></label>
    <input type="number" placeholder="Enter branch" name="branch" required>
        
    <button type="submit" name="lg">Register</button>
    
  </div>

  <div class="container" style="background-color:#f1f1f1">
  <a href="test.php">
  <button type="button" class="cancelbtn">Cancel</button>
  </a>
  </div>
</form>

</body>
</html>









