<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>














<style>
    #navigation-bar {
        background-color: rgb(187, 87, 201);
        padding: 17px 17px;
    }

    
    





























































































    #places {
        color: rgb(104, 104, 104);
        font-size: 34px;
        border-bottom: rgb(104, 104, 104) solid;
    }

    



    .another-place {
        color: #111111;
        padding: 17px 23px;
    }
</style>
<body>













<?php


$pdo = new PDO('mysql:host=localhost;dbname=anotherAssignment', 'customers', 'password');

session_start();


echo "<section id='navigation-bar'>";
echo "<div class='row'>";

echo "<div class='col'>";
echo "<div>";
echo "<img src='' alt=''>";
echo "</div>";
echo "</div>";
echo "<div class='col' style='text-align:right;'>";


if (isset($_SESSION["id"])) {
    
    $sql = "SELECT MAX(another.price) AS another_price, another.customerId AS customerId, another.auction_productId AS another_productId, auction_product.customerId AS another_customerId 
    FROM auction_product 
    JOIN another ON another.auction_productId = auction_product.id  
    WHERE close = 0 AND closing_time < CURRENT_TIMESTAMP AND another.price IS NOT NULL GROUP BY another.auction_productId";

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $places = array();
    $anotherNumber = 0;

    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $anotherPlace = array();
        $anotherPlace["customerId"] = $rows["customerId"];
        $anotherPlace["another_productId"] = $rows["another_productId"];
        $anotherPlace["another_price"] = $rows["another_price"];
        $anotherPlace["another_customerId"] = $rows["another_customerId"];
        $places[$anotherNumber] = $anotherPlace;
        $anotherNumber = $anotherNumber + 1;
    }

    foreach ($places as $value) {
        $sql = "UPDATE auction_product SET close = 1 WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":id" => $value["another_productId"]]);

        $sql = "UPDATE customer SET balance = balance - :another WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":another" => floatval($value["another_price"]), ":id" => $value["customerId"]]);

        $sql = "UPDATE customer SET balance = balance + :another WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":another" => floatval($value["another_price"]), ":id" => $value["another_customerId"]]);
    }

    
    $sql = "SELECT CONCAT(firstName, ' ', lastName) AS name FROM customer WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":id" => $_SESSION["id"]]);
    
    echo "<div class='dropdown'>";
    


    echo "<button class='btn btn-primary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>";
    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $rows["name"];
    }
    echo "</button>";

    echo "<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>";
    
    echo "<a href='customer-page.php' class='dropdown-item'>";
    echo "customer page";
    echo "</a>";
    echo "<a href='index.php' class='dropdown-item'>";
    echo "log out";
    echo "</a>";
    echo "</div>";

    echo "</div>";
} else {
    echo "<div>";
    echo "<form action='index.php'>";
    echo "<button type='submit' class='btn btn-primary'>";
    echo "sign in";
    echo "</button>";
    echo "</form>";
    echo "</div>";

}
echo "</div>";
echo "</div>";
echo "</section>";


echo "<section id='place'>";
echo "<div class='another-place'>";
echo "<div id='places'>";
echo "Another another another";
echo "</div>";






echo "<div class='row'>";
$anotherDisplay = array("closing", "another", "total");
foreach ($anotherDisplay as $value) {
    echo "<div class='col'>";
    echo "<form action='#' method='POST'>";
    
    echo "<input type='hidden' name='sort' value='".$value."'>";
    echo "<input type='submit' name='submit' class='btn btn-primary' value='".$value."'>";
    echo "</form>";
    echo "</div>";
}
echo "</div>";

if (isset($_POST["submit"])) {
    echo "<div class='row'>";
    echo "<div class='col'>";
    echo "<form action='#' method='POST'>";
    echo "<input type='hidden' name='sort' value='".$_POST["sort"]."'>";
    echo "<input type='submit' name='submit' class='btn btn-primary' value='DESC'>";
    echo "</form>";
    echo "</div>";
    echo "<div class='col'>";
    echo "<form action='#' method='POST'>";
    echo "<input type='hidden' name='sort' value='".$_POST["sort"]."'>";
    echo "<input type='submit' name='submit' class='btn btn-primary' value='ASC'>";
    echo "</form>";
    echo "</div>";
    echo "</div>";
    if ("DESC" == $_POST["submit"]) {
        $sql = "SELECT * FROM auction_product";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $count = 0;

        while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $count = $count + 1;
        }



        if ("closing" == $_POST["sort"]) {
            $sql = "SELECT auction_product.name AS name, auction_product.description AS description, auction_product.id AS another_productId FROM auction_product ORDER BY auction_product.closing_time DESC";
        } 
        else if ("total" == $_POST["sort"]) {
            
            $sql = "SELECT auction_product.name AS name, auction_product.description AS description, auction_product.id AS another_productId, IFNULL(another_another_another_another.another_count, 0) AS another_count FROM auction_product LEFT JOIN (SELECT COUNT(*) AS another_count, another.auction_productId AS another_productId FROM another WHERE another.price IS NOT NULL GROUP BY another.auction_productId) another_another_another_another ON another_another_another_another.another_productId = auction_product.id ORDER BY another_count DESC";
        } else {
            $sql = "SELECT auction_product.name AS name, auction_product.description AS description, auction_product.id AS another_productId, IFNULL(another_another_another_another.another_price, auction_product.minimum_price) AS another_price FROM auction_product LEFT JOIN (SELECT MAX(another.price) AS another_price, another.auction_productId AS another_productId FROM another WHERE another.price IS NOT NULL GROUP BY another.auction_productId) another_another_another_another ON another_another_another_another.another_productId = auction_product.id ORDER BY another_price DESC";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $anotherAnotheranother = 0;


$anotherAnotheranotherAnother = 0;

$anotherAnothercount = 0;


$results = array();
$result = array();

while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $result[$anotherAnotheranother] = $rows;
    if (3 == $anotherAnotheranother) {
        $results[$anotherAnotheranotherAnother] = $result;
        $anotherAnotheranotherAnother = $anotherAnotheranotherAnother + 1;
        $result = array();
        $anotherAnotheranother = 0;
    }
    if ($anotherAnothercount == ($count - 1)) {
        $results[$anotherAnotheranotherAnother] = $result;
    }
    $anotherAnotheranother = $anotherAnotheranother + 1;
    $anotherAnothercount = $anotherAnothercount + 1;

}




foreach ($results as $value) {
    echo "<div class='row'>";
    foreach ($value as $anotherValue) {
        echo "<div class='col'>";
        echo "<div class='card' style='width:18rem;'>";
        echo "<img class='card-img-top' src='' alt=''>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'>";
        echo $anotherValue["name"];
        echo "</h5>";
        echo "<p class='card-text'>";
        echo $anotherValue["description"];
        echo "</p>";
        echo "<form action='auction_product.php' method='POST'>";
        

        echo "<input type='hidden' name='another_productId' value='".$anotherValue["another_productId"]."'>";
        echo "<input type='submit' name='submit' class='btn btn-primary' value='submit'>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
    echo "</div>";
}


    }
    else if ("ASC" == $_POST["submit"]) {
        $sql = "SELECT * FROM auction_product";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $count = 0;

        while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $count = $count + 1;
        }


        if ("closing" == $_POST["sort"]) {
            $sql = "SELECT auction_product.name AS name, auction_product.description AS description, auction_product.id AS another_productId FROM auction_product ORDER BY auction_product.closing_time ASC";
        } 
        else if ("total" == $_POST["sort"]) {
            
            $sql = "SELECT auction_product.name AS name, auction_product.description AS description, auction_product.id AS another_productId, IFNULL(another_another_another_another.another_count, 0) AS another_count FROM auction_product LEFT JOIN (SELECT COUNT(*) AS another_count, another.auction_productId AS another_productId FROM another WHERE another.price IS NOT NULL GROUP BY another.auction_productId) another_another_another_another ON another_another_another_another.another_productId = auction_product.id ORDER BY another_count ASC";
        } else {
            $sql = "SELECT auction_product.name AS name, auction_product.description AS description, auction_product.id AS another_productId, IFNULL(another_another_another_another.another_price, auction_product.minimum_price) AS another_price FROM auction_product LEFT JOIN (SELECT MAX(another.price) AS another_price, another.auction_productId AS another_productId FROM another WHERE another.price IS NOT NULL GROUP BY another.auction_productId) another_another_another_another ON another_another_another_another.another_productId = auction_product.id ORDER BY another_price ASC";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $anotherAnotheranother = 0;


$anotherAnotheranotherAnother = 0;

$anotherAnothercount = 0;


$results = array();
$result = array();

while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $result[$anotherAnotheranother] = $rows;
    if (3 == $anotherAnotheranother) {
        $results[$anotherAnotheranotherAnother] = $result;
        $anotherAnotheranotherAnother = $anotherAnotheranotherAnother + 1;
        $result = array();
        $anotherAnotheranother = 0;
    }
    if ($anotherAnothercount == ($count - 1)) {
        $results[$anotherAnotheranotherAnother] = $result;
    }
    $anotherAnotheranother = $anotherAnotheranother + 1;
    $anotherAnothercount = $anotherAnothercount + 1;

}


foreach ($results as $value) {
    echo "<div class='row'>";
    foreach ($value as $anotherValue) {
        echo "<div class='col'>";
        echo "<div class='card' style='width:18rem;'>";
        echo "<img class='card-img-top' src='' alt=''>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'>";
        echo $anotherValue["name"];
        echo "</h5>";
        echo "<p class='card-text'>";
        echo $anotherValue["description"];
        echo "</p>";
        echo "<form action='auction_product.php' method='POST'>";
        

        echo "<input type='hidden' name='another_productId' value='".$anotherValue["another_productId"]."'>";
        echo "<input type='submit' name='submit' class='btn btn-primary' value='submit'>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
    echo "</div>";
}

    } else {

    }
}


echo "</div>";
echo "</section>";


?>
    

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>