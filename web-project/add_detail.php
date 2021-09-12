<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=web', 'customer', 'pass123');
require 'vendor/autoload.php'; // include Composer's autoloader
if(isset($_POST['submit'])){
    $_SESSION['product_id'] = $_POST['product_id'];
    echo $_POST['product_id'];
}
$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->web->auctionProducts;
$search =  $collection->find(['id' => $_SESSION['product_id']]);
foreach ($search as $data) {
    echo $data['id'];
}
if(isset($_GET["submit"])){
    $count = 0;
    $search =  $collection->find(['id' => $_SESSION['product_id']]);
foreach ($search as $data) {
    echo $data['id'];
    $count ++;
}
    if($count == 0){
        $result = $collection->insertOne( [ 'id' => $_SESSION['product_id'], $_GET['field'] => $_GET['value'] ] );

    }else{
        $update = $collection->updateOne(['id'=>$_SESSION['product_id']],['$set'=>[$_GET['field'] => $_GET['value']]]);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    
    <title>Document</title>
</head>
<body>
    <form action="#" method="GET">
        <input type="text" name="field" id="">
        <input type="text" name="value" id="">
        <button type="submit" name="submit">Add data</button>
    </form>
    <button type="submit" name="submit"><a href="customer.php">Back</a></button>
    <table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">Extra Information</th>
      <th scope="col">Values</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $search =  $collection->find(['id' => $_SESSION['product_id']]);
    foreach ($search as $data) {
        foreach ($data as $key=>$value){
            if($key != '_id' && $key !='id'){
                echo "<tr>";
            echo "<th>";
            echo $key;
            echo "</th>";
            echo "<td>";
            echo $value;
            echo "</td>";
            echo "</tr>";
            }
        }
    }


?>
  </tbody>
</table>
</body>

</html>