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
</style>
<body>
    
    
<?php

    $pdo = new PDO('mysql:host=localhost;dbname=anotherAssignment', 'customers', 'password');

    session_start();

    if (isset($_SESSION["id"])) {
        if (isset($_POST["submit"])) {
            $sql = "UPDATE customer SET lastName = :lastName, firstName = :firstName, address = :address, city = :city, country = :country WHERE id = :id";
            $stmt = $pdo->prepare($sql);

            $stmt->execute([":lastName" => $_POST["lastName"], ":firstName" => $_POST["firstName"], ":address" => $_POST["address"], ":city" => $_POST["city"], ":country" => $_POST["country"], ":id" => $_SESSION["id"]]);

            header('Location: customer-page.php');
            exit;
        }
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
    echo "<div>";
    echo "<form action='page.php'>";
    echo "<button type='submit' class='btn btn-primary'>";
    echo "page";
    echo "</button>";
    echo "</form>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</section>";

    
    echo "<section id='place'>";
    echo "<form action='#' method='POST'>";
    $sql = "SELECT * FROM customer WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":id" => $_SESSION["id"]]);


    $display = array("lastName", "firstName", "address", "city", "country");
    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
        foreach ($display as $value) {
            echo "<div class='form-group'>";
            echo "<input type='text' id="."'".$value."'"." "."name="."'".$value."'"." "."value="."'".$rows[$value]."'"." "."class='form-control'"." "."placeholder="."'".$value."'".">";
            echo "</div>";
        }
    }
    echo "<input type='submit' id='submit' name='submit' class='btn btn-primary'>";
    echo "</form>";
    echo "</section>";




?>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>