<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=web', 'customer', '1');
if(isset($_SESSION['id'])){

}else{
    header("Location: test.php");
    exit;
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
<form action="product.php" method="POST">
<div class="container" style="background-color:#f1f1f1">
    <button type="submit" class="cancelbtn" name="addproduct">Add Product</button>
  </div>
</form>
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">email</th>
      <th scope="col">phone</th>
      <th scope="col">password</th>
      <th scope="col">fname</th>
      <th scope="col">lname</th>
      <th scope="col">address</th>
      <th scope="col">city</th>
      <th scope="col">country</th>
      <th scope="col">branch</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $select = "select*from customerAccounts join branches on branches.branch_code = customerAccounts.branch where identificationNumber = :id  ";
    $test = $pdo->prepare($select);
    $test->bindParam(':id', $_SESSION['id']);
    $test->execute();
    while ($row = $test->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<th>";
        echo $row['identificationNumber'];
        echo "</th>";
        echo "<td>";
        echo $row['email'];
        echo "</td>";
        echo "<td>";
        echo $row['phone'];
        echo "</td>";
        echo "<td>";
        echo $row['pass'];
        echo "</td>";
        echo "<td>";
        echo $row['fname'];
        echo "</td>";
        echo "<td>";
        echo $row['lname'];
        echo "</td>";
        echo "<td>";
        echo $row['address'];
        echo "</td>";
        echo "<td>";
        echo $row['city'];
        echo "</td>";
        echo "<td>";
        echo $row['country'];
        echo "</td>";
        echo "<td>";
        echo $row['branch'];
        echo "</td>";
        echo "</tr>";
    }

?>
  </tbody>
</table>


<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">Auction Name</th>
      <th scope="col">Minimum Price</th>
      <th scope="col">Total Payment</th>
      <th scope="col">Closing Time</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $select = "select a.auction_name as auction_name, a.minimum_price as minimum_price,bid.bids as total_payment, a.closing_time as closing_time from bidshistory bid
    join auctionproducts a
    on bid.product_id = a.id
    where customer_id=:id;";
    $test = $pdo->prepare($select);
    $test->bindParam(':id', $_SESSION['id']);
    $test->execute();
    while ($row = $test->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<th>";
        echo $row['auction_name'];
        echo "</th>";
        echo "<td>";
        echo $row['minimum_price'];
        echo "</td>";
        echo "<td>";
        echo $row['total_payment'];
        echo "</td>";
        echo "<td>";
        echo $row['closing_time'];
        echo "</td>";
        echo "</tr>";
    }

?>
  </tbody>
</table>
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">Auction Name</th>
      <th scope="col">Minimum Price</th>
      <th scope="col">Closing Time</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $select = "select * from auctionproducts 
    where cid=:id;";
    $test = $pdo->prepare($select);
    $test->bindParam(':id', $_SESSION['id']);
    $test->execute();
    while ($row = $test->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<th>";
        echo $row['auction_name'];
        echo "</th>";
        echo "<td>";
        echo $row['minimum_price'];
        echo "</td>";
        echo "<td>";
        echo $row['closing_time'];
        echo "</td>";
        echo "<td>";
        echo '<td>'.'<form action="add_detail.php" method="POST">'.'<input type="hidden" name="product_id" value="'.$row['id'].'">'.'<input type="submit" name="submit" value="Auction">'.'</form>'.'</td>';
        echo "</td>";
        echo "</tr>";
    }

?>
  </tbody>
</table>
</body>
</html>