

<?php




$pdo = new PDO('mysql:host=localhost;dbname=anotherAssignment', 'customers','password');






if (isset($_POST["submit"])) {
    if ($_POST["another-password"] != $_POST["password"]) {

    } else {
        $sql = "SELECT * FROM customer";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $count = 0;
    $anotherAnothercount = 0;
    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $another = explode("-", $rows["id"])[1];
        if ($anotherAnothercount < intval($another)) {
            $anotherAnothercount = intval($another);
        }
        $count = $count + 1;
    }





    $anotherAnothercount = $anotherAnothercount + 1;


    $id = "CUS-";
    for ($i = 0; $i < 7-strlen(strval($anotherAnothercount)); ++$i) {
        $id = $id."0";
    }
    $id = $id.$anotherAnothercount;

    $anotherAnotheranother = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $sql = "INSERT INTO customer VALUES (:id, :firstName, :lastName, :address, :city, :country, :branch, :password, :image, :number, :email, :balance);";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":id" => $id, ":firstName" => $_POST["firstName"], ":lastName" => $_POST["lastName"], ":address" => "NULL", ":city" => "NULL", ":country" => "NULL", ":branch" => $_POST["branch"], ":password" => $anotherAnotheranother, ":image" => "NULL", ":number" => $_POST["another-number"], ":email" => $_POST["username"], ":balance" => 0.0]);

    

    $sql = "SELECT * FROM customer";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $anotherAnotheranotherAnothercount = 0;
    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $anotherAnotheranotherAnothercount = $anotherAnotheranotherAnothercount + 1;
    }


    if ($count == $anotherAnotheranotherAnothercount) {

    } else {
        header('Location: index.php');
        exit;
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
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>


<style>
   


    body{
        background-color: #f8f9fd;
        display:flex;
    }

    .page{

        display:inline-block;
        
        
    }

    #left {
        width: 33%;
        height: 100%;
        
        

    }

    


    #right {
        width: 33%;
        height: 100%;
        
    }




    #middle {
        width: 34%;
        height: 100%;
        
        padding: 174px 0px;
    }

    #logo-words {
        text-align: center;
        padding: 0px 152px;

    }

    

    .logo {
        background-color: purple;
        padding: 25px;

        border-radius: 104px;

    }
    .words{
        margin: 17px 0px;
        


    }

    


    .sections{
        
    }


    .place{
        width: 100%;
        display:flex;
        margin: 11px 0px;
        
    }
    .another-place {
        width:100%;
        display:flex;
        background-color: #f5f5f5;
        
        padding: 17px;
        border:none;
        border-radius: 3px;
        font-size: 17px;

    }




    .another-page {
        text-align: right;
        margin: 17px 0px;
    }



    #submit {
        background-color: purple;
        padding: 17px 53px;
        border: none;
        border-radius: 3px;
        font-family: arial;
        color: white;
        font-size: 17px;
    }



    .check {
        text-align: center;
        margin: 17px 0px;
    }

    .words {
        color: purple;
        font-family: arial;
        font-size: 17px;
        font-weight: bold;

    }

   
    #sheet {
        background-color: #ffffff;
        padding: 59px;
        box-shadow: 0 10px 34px -15px  grey;
        border-radius: 3px;
    }
    

</style>
<body>


<?php



echo "<div class='page' id='left'>";

echo "</div>";


echo "<div class='page' id='middle'>";


echo "<div id='sheet'>";

echo "<div id='logo-words'>";
echo "<div class='logo'>";
echo "<i class='fa fa-user-o' style='font-size:32px;'></i>";
echo "</div>";
echo "<div class='words'>";
echo "Checking";
echo "</div>";
echo "</div>";

echo "<div id='page-in'>";


echo "<form action='#' method='POST'>";


echo "<div class='sections'>";


echo "<label for='lastName' class='place'>";
echo "</label>";

echo "<div class='place'>";
echo "<input type='text' id='lastName' name='lastName' class='another-place' placeholder='Last Name'>";
echo "</div>";


echo "<label for='firstName' class='place'>";
echo "</label>";

echo "<div class='place'>";
echo "<input type='text' id='firstName' name='firstName' class='another-place' placeholder='First Name'>";
echo "</div>";



echo "<label for='username' class='place'>";
echo "</label>";


echo "<div class='place'>";
echo "<input type='text' id='username' name='username' class='another-place' placeholder='Name'/>";
echo "</div>";

echo "<label for='another-number' class='place'>";
echo "</label>";

echo "<div class='place'>";
echo "<input type='text' id='another-number' name='another-number' class='another-place' placeholder='Another number'>";
echo "</div>";


echo "<label for='branch' class='place'>";
echo "</label>";

echo "<div class='place'>";
echo "<select name='branch' id='branch'>";
$sql = "SELECT * FROM branch";
$stmt = $pdo->prepare($sql);
$stmt->execute();
while($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<option"." "."value="."'".$rows["id"]."'".">";
    echo $rows["name"];
    echo "</option>";
}
echo "</select>";
echo "</div>";



echo "<label for='password' class='place'>";
echo "</label>";



echo "<div class='place'>";


echo "<input type='password' id='password' name='password' class='another-place' placeholder='Password'/>";

echo "</div>";


echo "<label for='another-password' class='place'>";
echo "</label>";

echo "<div class='place'>";


echo "<input  type='password' id='another-password' name='another-password' class='another-place' placeholder='Password'>";

echo "</div>";


echo "</div>";



echo "<div class='check'>";


echo "<input type='submit' id='submit' name='submit' value='submit'>";
echo "</div>";


echo "</form>";

echo "</div>";


echo "<div id='foot'>";

echo "checking";

echo "</div>";


echo "</div>";


echo "</div>";


echo "<div class='page' id='right'>";

echo "</div>";

?>

    
</body>
</html>