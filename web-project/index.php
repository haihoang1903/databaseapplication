<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=web', 'customer', 'pass123');
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
<form action="customer.php" method="POST">
<div class="container" style="background-color:#f1f1f1">
    <button type="submit" class="cancelbtn" name="submit">Personal page</button>
  </div>
</form>
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Auction Name</th>
      <th scope="col">Minimum Price</th>
      <th scope="col">Max Price</th>
      <th scope="col">Number of Bids</th>
      <th scope="col">Closing Time</th>
    </tr>
  </thead>
  <tbody>
  <div class="row">
  <div class="dropdown col-sm-1 ">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  ASC
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <form method="POST" action="#">
    <button type="submit" class="dropdown-item" name="asc" value = "price">Sort by Minimum Price</button>
    </form>
    <form method="POST" action="#">
    <button type="submit" class="dropdown-item" name="asc" value = "closingtime">Sort by Closing Time</button>
    </form>
    <form method="POST" action="#">
    <button type="submit" class="dropdown-item" name="asc" value = "bidnum">Sort by Number of Bids</button>
    </form>
  </div>
</div>
<div class="dropdown col-sm-2">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  DESC
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <form method="POST" action="#">
    <button type="submit" class="dropdown-item" name="desc" value = "price">Sort by Minimum Price</button>
    </form>
    <form method="POST" action="#">
    <button type="submit" class="dropdown-item" name="desc" value = "closingtime">Sort by Closing Time</button>
    </form>
    <form method="POST" action="#">
    <button type="submit" class="dropdown-item" name="desc" value = "bidnum">Sort by Number of Bids</button>
    </form>
  </div>
</div>
  </div>
<?php
$exct = '';
if(isset($_POST['asc'])){
  if($_POST['asc']=='price'){
    $exct = 'select auctionproducts.id as id, ifnull(numbids.numbid,0) as bidnum,auctionproducts.closing_time as closing_time,auctionproducts.auction_name as auction_name ,auctionproducts.minimum_price as minimum_price, ifnull(max(bidshistory.bids),auctionproducts.minimum_price) as mx from auctionproducts
    left join bidshistory on auctionproducts.id = bidshistory.product_id left join (select product_id as prod_id,count(*) as numbid from bidsHistory group by bidsHistory.product_id) numbids on auctionproducts.id = numbids.prod_id 
    where CURRENT_TIMESTAMP() <= closing_time
    group by (auctionproducts.id)
    order by mx asc;';
  }
  if($_POST['asc']=='closingtime'){
    $exct = 'select auctionproducts.id as id, ifnull(numbids.numbid,0) as bidnum,auctionproducts.closing_time as closing_time,auctionproducts.auction_name as auction_name ,auctionproducts.minimum_price as minimum_price, ifnull(max(bidshistory.bids),auctionproducts.minimum_price) as mx from auctionproducts
    left join bidshistory on auctionproducts.id = bidshistory.product_id left join (select product_id as prod_id,count(*) as numbid from bidsHistory group by bidsHistory.product_id) numbids on auctionproducts.id = numbids.prod_id 
    where CURRENT_TIMESTAMP() <= closing_time
    group by (auctionproducts.id)
    order by closing_time asc;';
  }  
  if($_POST['asc']=='bidnum'){
    $exct = 'select auctionproducts.id as id, ifnull(numbids.numbid,0) as bidnum,auctionproducts.closing_time as closing_time,auctionproducts.auction_name as auction_name ,auctionproducts.minimum_price as minimum_price, ifnull(max(bidshistory.bids),auctionproducts.minimum_price) as mx from auctionproducts
    left join bidshistory on auctionproducts.id = bidshistory.product_id left join (select product_id as prod_id,count(*) as numbid from bidsHistory group by bidsHistory.product_id) numbids on auctionproducts.id = numbids.prod_id 
    where CURRENT_TIMESTAMP() <= closing_time
    group by (auctionproducts.id)
    order by bidnum asc;';
  }
}else if(isset($_POST['desc'])){
  if($_POST['desc']=='price'){
    $exct = 'select auctionproducts.id as id, ifnull(numbids.numbid,0) as bidnum,auctionproducts.closing_time as closing_time,auctionproducts.auction_name as auction_name ,auctionproducts.minimum_price as minimum_price, ifnull(max(bidshistory.bids),auctionproducts.minimum_price) as mx from auctionproducts
    left join bidshistory on auctionproducts.id = bidshistory.product_id left join (select product_id as prod_id,count(*) as numbid from bidsHistory group by bidsHistory.product_id) numbids on auctionproducts.id = numbids.prod_id 
    where CURRENT_TIMESTAMP() <= closing_time
    group by (auctionproducts.id)
    order by mx desc';
  }
  if($_POST['desc']=='closingtime'){
    $exct = 'select auctionproducts.id as id, ifnull(numbids.numbid,0) as bidnum,auctionproducts.closing_time as closing_time,auctionproducts.auction_name as auction_name ,auctionproducts.minimum_price as minimum_price, ifnull(max(bidshistory.bids),auctionproducts.minimum_price) as mx from auctionproducts
    left join bidshistory on auctionproducts.id = bidshistory.product_id left join (select product_id as prod_id,count(*) as numbid from bidsHistory group by bidsHistory.product_id) numbids on auctionproducts.id = numbids.prod_id 
    where CURRENT_TIMESTAMP() <= closing_time
    group by (auctionproducts.id)
    order by closing_time desc';
  }  
  if($_POST['desc']=='bidnum'){
    $exct = 'select auctionproducts.id as id, ifnull(numbids.numbid,0) as bidnum,auctionproducts.closing_time as closing_time,auctionproducts.auction_name as auction_name ,auctionproducts.minimum_price as minimum_price, ifnull(max(bidshistory.bids),auctionproducts.minimum_price) as mx from auctionproducts
    left join bidshistory on auctionproducts.id = bidshistory.product_id left join (select product_id as prod_id,count(*) as numbid from bidsHistory group by bidsHistory.product_id) numbids on auctionproducts.id = numbids.prod_id 
    where CURRENT_TIMESTAMP() <= closing_time
    group by (auctionproducts.id)
    order by bidnum desc';
  }
}

else{
  $exct='select auctionproducts.id as id, ifnull(numbids.numbid,0) as bidnum,auctionproducts.closing_time as closing_time,auctionproducts.auction_name as auction_name ,auctionproducts.minimum_price as minimum_price, ifnull(max(bidshistory.bids),auctionproducts.minimum_price) as mx from auctionproducts
  left join bidshistory on auctionproducts.id = bidshistory.product_id left join (select product_id as prod_id,count(*) as numbid from bidsHistory group by bidsHistory.product_id) numbids on auctionproducts.id = numbids.prod_id 
  where CURRENT_TIMESTAMP() <= closing_time
  group by (auctionproducts.id)';
}

$data = $pdo->query($exct);
foreach ($data as $row) {
    echo '<tr>';
    echo '<th scope="row">'.$row['id'].'</th>';
    echo '<td>'.$row['auction_name'].'</td>';
    echo '<td>'.$row['minimum_price'].'</td>';
    echo '<td>'.$row['mx'].'</td>';
    echo '<td>'.$row['bidnum'].'</td>';
    echo '<td>'.$row['closing_time'].'</td>';
    echo '<td>'.'<form action="auction.php" method="POST">'.'<input type="hidden" name="product_id" value="'.$row['id'].'">'.'<input type="submit" name="submit" value="Auction">'.'</form>'.'</td>';
  echo '</tr>';
    }
?>
  </tbody>
</table>

</body>
</html>