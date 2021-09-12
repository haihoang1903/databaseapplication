<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=web', 'customer', 'pass123');
require 'vendor/autoload.php'; // include Composer's autoloader
$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->web->auctionProducts;
if(isset($_POST['submit'])){
    $_SESSION['product_id'] = $_POST['product_id'];
    echo $_POST['product_id'];
}
if(isset($_GET['button'])){
    $exct = "select id from auctionproducts where closing_time<current_timestamp();";
    $test = $pdo->prepare($exct);
    $test->execute();
    $tf = 0;
    while ($row = $test->fetch(PDO::FETCH_ASSOC)) {
    if($row['id']==$_SESSION['product_id']){
        $tf = 1;
        break;
    }else{
        $tf = 0;
    }
}
    if($tf==0){
        $check = 0;
        if(is_float(floatval($_GET['bid'])) && $_GET['bid'] > 0 ) {

        } else {
            $check = 1;
        }
        if($check == 0){
            $execute = "call check_set_bid(:cid,:pid,:bid)";
            $test = $pdo->prepare($execute);
            $test->bindParam(':cid', $_SESSION['id']);
            $test->bindParam(':pid',$_SESSION['product_id']);
            $new_bid = (double)$_GET['bid'];
            $test->bindParam(':bid',$new_bid);
            $test->execute();
        }
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
    <div class="row">
        <div class="col">
            <img src="https://www.lifewire.com/thmb/T7S1BFqJYcEsWQm6GO-ihViyh5E=/1172x781/filters:no_upscale():max_bytes(150000):strip_icc()/shopgoodwill-auction-site-8894d07d668e4dc3891de3dda3a0edf4.png" alt="" style="width: 600px">

        </div>
        <div class="col">
            <?php
                $select = 'select auctionproducts.id as id, auctionproducts.auction_name as auction_name ,auctionproducts.minimum_price as minimum_price, ifnull(max(bidshistory.bids),auctionproducts.minimum_price) as mx from auctionproducts
                left join bidshistory on auctionproducts.id = bidshistory.product_id
                where id=:id
                group by (auctionproducts.id);';
                $execute = $pdo->prepare($select);
                $execute->bindParam(':id', $_SESSION['product_id']);
                $execute->execute();
                while ($row = $execute->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div style="font-size: 40px;margin-bottom: 50px">'.$row['auction_name'].'</div>';
                    echo '<div style="background-color: gray;padding: 20px;border-style: solid;border-width: thin">'.'Current Price: '.$row['mx'].'</div>';
                    echo '<div style="background-color: gray;padding: 20px;border-style: solid;border-width: thin">'.'Minimum Bid: '.$row['minimum_price'].'</div>';
                }
                $search =  $collection->find(['id' => $_SESSION['product_id']]);
                foreach ($search as $data) {
                foreach ($data as $key=>$value){
                    if($key != '_id' && $key !='id'){
                        echo '<div style="background-color: gray;padding: 20px;border-style: solid;border-width: thin">'.$key." ".$value.'</div>';
            }
        }
    }
            ?>
            <div style="background-color: gray;padding: 20px;border-style: solid;border-width: thin">
                <form action="">Enter your maximum bid:
                    <input type="text" name="bid">
                    <button type="submit" name="button">Place Bid</button>
                </form>
                <button type="submit" name="submit"><a href="index.php">Back</a></button>
            </div>
        </div>
    </div>
</body>
</html>