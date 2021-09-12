<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=web', 'customer', 'pass123');
if(isset($_SESSION['id'])){

}else{
    header("Location: test.php");
    exit;
}
if(isset($_POST['submit'])) {
  if('Submit'==$_POST['submit']){
    // Count total files
  $countfiles = count($_FILES['files']['name']);
    
  // Prepared statement
  $exct = "update customerAccounts set picture = :select where identificationNumber = :id";
  $statement = $pdo->prepare($exct);
  $statement->bindParam(':id', $_SESSION['id']);
  
 
  // Loop all files
  for($i = 0; $i < $countfiles; $i++) {
 
      // File name
      $filename = $_FILES['files']['name'][$i];
     
      // Location
      $targetfile = 'upload/'.$filename;
     
      // file extension
      $fileextension = pathinfo(
          $targetfile, PATHINFO_EXTENSION);
            
      $fileextension = strtolower($fileextension);
     
      // Valid image extension
      $validextension = array("png","jpeg","jpg");
     
      if(in_array($fileextension, $validextension)) {
 
          // Upload file
          if(move_uploaded_file(
              $_FILES['files']['tmp_name'][$i],
              $targetfile)
          ) {

              // Execute query
              $statement->bindParam(':select',$targetfile);
              $statement->execute();
          }
      }
  }
    
  echo "File upload successfully";
  }
}
if(isset($_GET['back'])){
  header("Location: index.php");
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
<form action="#" method="GET">
<div class="container" style="background-color:#f1f1f1">
    <button type="submit" class="cancelbtn" name="back">Main menu</button>
  </div>
</form>
<form method='post' action=''
        enctype='multipart/form-data'>
        <input type='file' name='files[]' multiple />
        <input type='submit' value='Submit' name='submit' />
</form>
<div style="font-size:40px">User Profile</div>
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">email</th>
      <th scope="col">phone</th>
      <th scope="col">fname</th>
      <th scope="col">lname</th>
      <th scope="col">address</th>
      <th scope="col">city</th>
      <th scope="col">country</th>
      <th scope="col">branch</th>
      <th scope="col">profile picture</th>
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
        echo "<td>";
        echo '<img src="'.$row['picture'].'" style="width:200px;height:200px">';
        echo "</td>";
        echo "</tr>";
    }

?>
  </tbody>
</table>

<div style="font-size:40px">Bid History</div>
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
<div style="font-size:40px">Your Product</div>
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
        echo '<td>'.'<form action="add_detail.php" method="POST">'.'<input type="hidden" name="product_id" value="'.$row['id'].'">'.'<input type="submit" name="submit" value="Extra Data">'.'</form>'.'</td>';
        echo "</td>";
        echo "</tr>";
    }
  
?>
  </tbody>
</table>
<div style="font-size:40px">Your Win Auction Product</div>
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">Auction ID</th>
      <th scope="col">Bids History</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $select = "select bids_hist.max_bids as bids_history,auctionproducts.id as auction_id from auctionproducts 
    join bidsHistory on bidsHistory.product_id = auctionproducts.id
    join (select max(bidshistory.bids) as max_bids,product_id as productid from bidshistory group by product_id) bids_hist
    on auctionproducts.id = bids_hist.productid and bidshistory.bids = bids_hist.max_bids 
    where closing_time < current_timestamp()
    and customer_id = :id;";
    $test = $pdo->prepare($select);
    $test->bindParam(':id', $_SESSION['id']);
    $test->execute();
    while ($row = $test->fetch(PDO::FETCH_ASSOC)) {
      echo "<tr>";
        echo "<th>";
        echo $row['auction_id'];
        echo "</th>";
        echo "<td>";
        echo $row['bids_history'];
        echo "</td>";
        echo "</tr>";
    }
    ?>
  </tbody>
  </table> 
</body>
</html>