<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=web', 'admin', 'pass123');
if(isset($_SESSION['id'])){

}else{
    header("Location: test.php");
    exit;
}
if(isset($_GET['logout'])){
    session_destroy();
    header("Location: test.php");
    exit;
}
if(isset($_POST['submit'])){
    if($_POST['submit']=='reverse'){
      $pdo->beginTransaction();
      try{
        $exct = "update customeraccounts set balance = balance + :bids where identificationNumber = :bidder";
        $new_bids = (double)$_POST['bid'];
        $test = $pdo->prepare($exct);
        $test->bindParam(':bids', $new_bids);
        $test->bindParam(':bidder', $_POST['bidder']); 
        $test->execute();

        $exct = "update customeraccounts set balance = balance - :bids where identificationNumber = :seller";
        $new_bids = (double)$_POST['bid'];
        $test = $pdo->prepare($exct);
        $test->bindParam(':bids', $new_bids);
        $test->bindParam(':seller', $_POST['seller']); 
        $test->execute();
        $exct = "update transactionHistory set status=1 where id = :id ";
        $id = (int)$_POST['history_id'];
        $test = $pdo->prepare($exct);
        $test->bindParam(':id', $id);
        $test->execute();
      } catch(PDOException $exception){
        $pdo->rollback();
      }
      $pdo->commit();
    }
}
$exct = "select id, bids_hist.max_bids as bids, auctionproducts.cid as seller, bidshistory.customer_id as bidder from auctionproducts
join bidshistory
on auctionproducts.id = bidshistory.product_id
join (select max(bidshistory.bids) as max_bids,product_id as productid from bidshistory group by product_id) bids_hist
on  auctionproducts.id = bids_hist.productid and bidshistory.bids = bids_hist.max_bids
where closing_time<current_timestamp() and status =0;";
$test = $pdo->prepare($exct); 
$test->execute();
  while ($row = $test->fetch(PDO::FETCH_ASSOC)) {
    $pdo->beginTransaction();
    try{
      $exct = "update customeraccounts set balance = balance + :bids where identificationNumber = :seller";
  $new_bids = (double)$row['bids'];
  echo $new_bids;
  echo $row['seller'];
  $test = $pdo->prepare($exct);
  $test->bindParam(':bids', $new_bids);
  $test->bindParam(':seller', $row['seller']); 
  $test->execute();

  $exct = "insert into transactionHistory (seller, bidder, bid, time) values (:seller, :bidder, :bids, CURRENT_TIMESTAMP())";
  $test = $pdo->prepare($exct); 
  $test->bindParam(':seller', $row['seller']);
  $test->bindParam(':bidder',$row['bidder']);
  $test->bindParam(':bids', $row['bids']);
  $test->execute();

  $exct = "update auctionproducts set status = 1 where id = :id";
  $test = $pdo->prepare($exct); 
  $test->bindParam(':id', $row['id']);
  $test->execute();
    }catch(PDOException $exception){
      $pdo->rollback();
    }
    $pdo->commit();
  
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
<div class="container" style="background-color:#f1f1f1">
    <button type="submit" class="cancelbtn" name="logout">Log out</button>
  </div>
</form>
<form action="updateBalanced.php" method="POST">
<div class="container" style="background-color:#f1f1f1">
    <button type="submit" class="cancelbtn" name="submit" value="transaction">Update Balance Page</button>
  </div>
</form>
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">transaction ID</th>
      <th scope="col">seller ID</th>
      <th scope="col">bidder ID</th>
      <th scope="col">Amount</th>
      <th scope="col">Time</th>
    </tr>
  </thead>
  <tbody>
  <div class="row">
  <form action="#" method="POST">
    <div class="col">
      <input type="datetime-local" name="opentime" id="opentime">
    </div>
    <div class="col">
    <input type="datetime-local" name="closingtime" id="closingtime">
    </div>
    <div class="col">
    <input type="submit" name="submit" value="select">
    </div>
  </form>
  </div>
<?php
$exct='select * from transactionHistory';
$data = $pdo->prepare($exct); 

if(isset($_POST['submit'])){
  if($_POST['submit']=='select'){
    $exct = 'select * from transactionHistory where time >= :opentime and time <= :closingtime';
    $data = $pdo->prepare($exct); 
    $data->bindParam(':opentime', $_POST['opentime']);
    $data->bindParam(':closingtime',$_POST['closingtime']);
  
  }

}
$data->execute();
while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
    echo '<tr>';
    echo '<th scope="row">'.$row['id'].'</th>';
    echo '<td>'.$row['seller'].'</td>';
    echo '<td>'.$row['bidder'].'</td>';
    echo '<td>'.$row['bid'].'</td>';
    echo '<td>'.$row['time'].'</td>';
    if ($row['status']==0){
      echo '<form action="#" method="POST">';
      echo '<td>'.'<input type="hidden" name="seller" value="'.$row['seller'].'">'.'<input type="hidden" name="bidder" value="'.$row['bidder'].'">'.'<input type="hidden" name="bid" value="'.$row['bid'].'">'.'<input type="submit" name="submit" value="reverse">'.'<input type="hidden" id="history_id" name="history_id" value="'.$row['id'].'">'.'</td>';
      echo '</form>';
    }else {
      echo '<td>'.'reverse'.'</td>';
    }
  echo '</tr>';
    }
?>
  </tbody>
</table>

</body>
</html>