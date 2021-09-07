<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    











<style>



#navigation-bar {
    background-color: rgb(187, 87, 201);
    padding: 17px 17px;
}
</style>
<?php





$pdo = new PDO('mysql:host=localhost;dbname=anotherAssignment', 'customers', 'password');

session_start();


if (isset($_SESSION["id"])) {

} else {
    header('Location: index.php');
    exit;
}



echo "<section id='navigation-bar'>";


echo "<div class='row'>";


echo "<div class='col'>";

echo "<div>";
echo "<img src='' alt=''>";
echo "</div>";


echo "</div>";


echo "<div class='col' style='text-align:right;'>";

echo "<div class='row'>";
echo "<div class='col'>";
echo "<form action='product.php'>";
echo "<button type='submit' class='btn btn-primary'>";
echo "product";
echo "</button>";
echo "</form>";
echo "</div>";
echo "<div class='col'>";
echo "<form action='page.php'>";
echo "<button type='submit' class='btn btn-primary'>";
echo "page";
echo "</button>";
echo "</form>";
echo "</div>";
echo "</div>";


echo "</div>";



echo "</div>";




echo "</section>";



echo "<section id='place'>";



echo "<div class='row'>";


echo "<div class='col-5' style='color: #111111; padding: 17px 17px;'>";
echo "<img src='' alt=''>";
echo "</div>";


echo "<div class='col-7' style='color: #111111; border-left: solid; padding: 17px 17px;'>";


echo "<form action='customer-update.php'>";


$sql = "SELECT customer.lastName AS lastName, customer.firstName AS firstName, branch.name AS branchName, customer.address AS address, customer.city AS city, customer.country AS country FROM customer, branch WHERE customer.id = :id AND branch.id = customer.branchId";
$stmt = $pdo->prepare($sql);
$stmt->execute([":id" => $_SESSION["id"]]);


$display = array("lastName", "firstName", "branchName", "address", "city", "country");




while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
    
    foreach ($display as $value) {
        echo "<div class='row'>";  
        echo "<div class='col-4' style='text-align: right;'>";

        echo $value;

        echo "</div>";     
        echo "<div class='col-8'>";
        echo $rows[$value];
        echo "</div>";
        echo "</div>";
    }
}


echo "<div class='row'>";
echo "<div class='col'>";
echo "<button type='submit' class='btn btn-primary'>";
echo "Update";
echo "</button>";
echo "</div>";
echo "</div>";


echo "</form>";

echo "</div>";
echo "</div>";



echo "<div class='row'>";
echo "<div class='col-5'>";
echo "<button type='button' class='btn btn-primary' onclick='display()'>";
echo "list";
echo "</button>";
echo "</div>";
echo "<div class='col-7'>";
echo "<table id='another_place' class='table table-hover'>";
echo "<tbody>";
echo "<tr>";
echo "<td>";
echo "Name";
echo "</td>";
echo "<td>";
echo "Another";
echo "</td>";
echo "</tr>";
$sql = "SELECT auction_product.name AS name, another.price AS another_price FROM auction_product JOIN another ON another.auction_productId = auction_product.id WHERE another.customerId = :id AND another.price IS NOT NULL";

$stmt = $pdo->prepare($sql);

$stmt->execute([":id" => $_SESSION["id"]]);

while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>";
    echo $rows["name"];
    echo "</td>";
    echo "<td>";
    echo $rows["another_price"];
    echo "</td>";
    echo "</tr>";
}

echo "</tbody>";
echo "</table>";
echo "</div>";
echo "</div>";


echo "</section>";



?>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

<script>
    var another = false;
    var anotherAnotheranother = document.getElementById("another_place").style.display;
    function display() {
        if (!another) {
            document.getElementById("another_place").style.display = "none";
            another = true;
        } else {
            document.getElementById("another_place").style.display = anotherAnotheranother;
            another = false;

        }
    }
</script>

</html>