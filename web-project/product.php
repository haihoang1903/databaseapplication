<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=web', 'customer', 'pass123');
if(isset($_POST['lg'])){
    try{
      $check = 0;
        if(is_float(floatval($_POST['price'])) && $_POST['price'] > 0 ) {

        } else {
            $check = 1;
        }
        if($check == 0){
          $exct = "insert into auctionproducts (auction_name,minimum_price,closing_time,cid) values(:auction_name,:minimum_price,:closing_time,:cid)";
          $test = $pdo->prepare($exct);
          $test->bindParam(':auction_name', $_POST['name']);
          $test->bindParam(':minimum_price', $_POST['price']);
          $test->bindParam(':closing_time', $_POST['closing']);
          $test->bindParam(':cid', $_SESSION['id']);
          $test->execute();
          header("Location: index.php");
              exit;
        }
    }catch(PDOException $e){
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

<h2>Add Product</h2>

<form action="#" method="post">
  <div class="imgcontainer">
    <img src="img_avatar2.png" alt="Avatar" class="avatar">
  </div>

  <div class="container">
    <label for="name"><b>Product name</b></label>
    <input type="text" placeholder="Enter product name" name="name" required>
    <label for="price"><b>Product price</b></label>
    <input type="text" placeholder="Enter product price" name="price" required>
    <label for="time"><b>Product closing time</b></label>
    <input type="datetime-local" placeholder="Enter product closing time" name="closing" required>
    <button type="submit" name="lg">Add Product</button>
  </div>
</form>

</body>
</html>