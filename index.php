<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>




<?php



$pdo = new PDO('mysql:host=localhost;dbname=anotherAssignment', 'root', 'rm!t8597');







session_start();

$_SESSION = array();


try {
    

    $sql = "CREATE TABLE branch (
        id CHAR(8) PRIMARY KEY,
        name VARCHAR(255) unique,
        address VARCHAR(255),
        number CHAR(10)
    ) ENGINE=InnoDB;";

    



    $stmt = $pdo->prepare($sql);


    $stmt->execute();

    echo "Table is created";

    


} catch(PDOException $e) {
    echo "Can't create table";
}


try {

    $sql = "CREATE TABLE customer (
        id CHAR(11) PRIMARY KEY,
        firstName VARCHAR(255),
        lastName VARCHAR(255),
        address VARCHAR(255),
        city VARCHAR(255),
        country VARCHAR(255),

        branchId CHAR(8),
        password VARCHAR(255),
        image VARCHAR(255),
    
        number CHAR(10) unique,
        email VARCHAR(255) unique,
        balance FLOAT,

        FOREIGN KEY (branchId) REFERENCES branch(id)
        
    ) ENGINE=InnoDB;"; 

    $stmt = $pdo->prepare($sql);

    $stmt->execute();
    echo "Table is created";

    
} catch(PDOexception $e) {
    echo "Can't create table";
}




try {
    $sql = "CREATE TABLE auction_product (
        id CHAR(11) PRIMARY KEY,
        name VARCHAR(255),
        description VARCHAR(255),
        customerId CHAR(11),
        minimum_price FLOAT,
        close INT,
        closing_time DATE,
        time DATE,

        FOREIGN KEY (customerId) REFERENCES customer(id)

    ) ENGINE=InnoDB;";

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    echo "Table is created";




} catch(PDOException $e) {
    echo "Can't create table";
}




try {
    $sql = "CREATE TABLE another(
        customerId CHAR(11),
        auction_productId CHAR(11),
        price FLOAT,


        PRIMARY KEY (customerId, auction_productId),
        FOREIGN KEY (customerId) REFERENCES customer(id),
        FOREIGN KEY (auction_productId) REFERENCES auction_product(id)
    ) ENGINE=InnoDB;";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    echo "Table is created";

} catch(PDOException $e) {


    echo "Can't create table";
}


try {
    $sql = "CREATE FUNCTION find_another(another_id CHAR(11)) 
    RETURNS FLOAT
    READS SQL DATA
    NOT DETERMINISTIC
    BEGIN
        DECLARE another_another_another_another FLOAT;
        SET another_another_another_another = 0;
        SELECT minimum_price INTO another_another_another_another FROM auction_product WHERE id = another_id;
        RETURN another_another_another_another;
    END;";

    $stmt = $pdo->prepare($sql);

    $stmt->execute();
    echo "Function is created";



} catch(PDOException $e) {

    echo "Can't create function";

}




try {
    $sql = "CREATE FUNCTION find_another_another(another_id CHAR(11)) RETURNS FLOAT
    READS SQL DATA
    NOT DETERMINISTIC
    BEGIN
        DECLARE another_another_another_another FLOAT;
        SET another_another_another_another = 0;
        SELECT MAX(another.price) INTO another_another_another_another FROM another WHERE another.price IS NOT NULL AND another.auction_productId = another_id;
        RETURN another_another_another_another;
    END;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    echo "Function is created";

} catch(PDOException $e) {

    echo "Can't create function";

}








try {
    $sql = "CREATE TRIGGER before_insert_another BEFORE INSERT ON another
    FOR EACH ROW 
    BEGIN 
        DECLARE another_another_another INT;
        DECLARE another_another_another_another_another FLOAT;
        SET another_another_another = 0;
        SET another_another_another_another_another = 0;
        SELECT COUNT(*) INTO another_another_another FROM another WHERE another.auction_productId = NEW.auction_productId AND another.price IS NOT NULL;
        SELECT balance INTO another_another_another_another_another FROM customer WHERE id = NEW.customerId;
        IF 0 = another_another_another THEN
            IF NEW.price >= find_another(NEW.auction_productId) THEN 
                IF NEW.price <= another_another_another_another_another THEN SET NEW.price = NEW.price;
                ELSE SET NEW.price = NULL;
                END IF;
            ELSE SET NEW.price = NULL;
            END IF;
        ELSE 
            IF NEW.price > find_another_another(NEW.auction_productId) THEN 
                IF NEW.price <= another_another_another_another_another THEN SET NEW.price = NEW.price;
                ELSE SET NEW.price = NULL;
                END IF;
            ELSE SET NEW.price = NULL;
            END IF;
        END IF;
    END;";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    echo "Trigger is created";
} catch(PDOException $e) {

    echo "Can't create trigger";

}


try {
    $sql = "CREATE TRIGGER before_update_another BEFORE UPDATE ON another
    FOR EACH ROW
    BEGIN
        DECLARE another_another_another INT;
        DECLARE another_another_another_another_another FLOAT;
        SET another_another_another = 0;
        SET another_another_another_another_another = 0;
        SELECT COUNT(*) INTO another_another_another FROM another WHERE another.auction_productId = OLD.auction_productId AND another.price IS NOT NULL;
        SELECT balance INTO another_another_another_another_another FROM customer WHERE id = OLD.customerId;
        IF 0 = another_another_another THEN
            IF NEW.price >= find_another(OLD.auction_productId) THEN 
                IF NEW.price <= another_another_another_another_another THEN SET NEW.price = NEW.price;
                ELSE SET NEW.price = OLD.price;
                END IF;
            ELSE SET NEW.price = OLD.price;
            END IF;
        ELSE 
            IF NEW.price > find_another_another(OLD.auction_productId) THEN 
                IF NEW.price <= another_another_another_another_another THEN SET NEW.price = NEW.price;
                ELSE SET NEW.price = OLD.price;
                END IF;
            ELSE SET NEW.price = OLD.price;
            END IF;
        END IF;
    END;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    echo "Trigger is created";
} catch(PDOException $e) {

    echo "Can't create trigger";

}

try {
    $sql = "CREATE PROCEDURE insert_update_another(IN another_customerId CHAR(11), IN another_productId CHAR(11), IN another_price FLOAT)
    BEGIN
        DECLARE another_another_another_another INT;
        SET another_another_another_another = 0;
        SELECT COUNT(*) INTO another_another_another_another FROM another WHERE customerId = another_customerId AND another.auction_productId = another_productId;
        IF 0 = another_another_another_another THEN INSERT INTO another VALUES (another_customerId, another_productId, another_price);
        ELSE UPDATE another SET price = another_price WHERE customerId = another_customerId AND another.auction_productId = another_productId;
        END IF;
    END;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    echo "Procedure is created";

    































    $sql = "CREATE ROLE admin_role";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sql = "GRANT ALL ON anotherAssignment.customer TO admin_role";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sql = "CREATE USER 'admin'@'localhost' IDENTIFIED BY '123'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sql = "GRANT admin_role TO 'admin'@'localhost'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sql = "SET DEFAULT ROLE admin_role TO 'admin'@'localhost'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sql = "INSERT INTO customer VALUES (:id, :firstName, :lastName, :address, :city, :country, :branchId, :password, :image, :number, :email, :balance)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":id" => "CUS-0000000", ":firstName" => "unknown", ":lastName" => "unknown", ":address" => "NULL", ":city" => "NULL", ":country" => "NULL", ":branchId" => NULL, ":password" => password_hash("rm!t8597", PASSWORD_DEFAULT), ":image" => "NULL", ":number" => "0127836123", ":email" => "admin@gmail.com", ":balance" => 0.0]);



    $sql = "CREATE ROLE customer_role";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sql = "GRANT SELECT ON anotherAssignment.branch TO customer_role";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sql = "GRANT ALL ON anotherAssignment.customer TO customer_role";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sql = "GRANT ALL ON anotherAssignment.auction_product TO customer_role";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sql = "GRANT ALL ON anotherAssignment.another TO customer_role";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sql = "GRANT EXECUTE ON PROCEDURE anotherAssignment.insert_update_another TO customer_role";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sql = "GRANT EXECUTE ON FUNCTION anotherAssignment.find_another TO customer_role";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sql = "GRANT EXECUTE ON FUNCTION anotherAssignment.find_another_another TO customer_role";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();


    $sql = "CREATE USER 'customers'@'localhost' IDENTIFIED BY 'password'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sql = "GRANT customer_role TO 'customers'@'localhost'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sql = "SET DEFAULT ROLE customer_role TO 'customers'@'localhost'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sql = "INSERT INTO branch VALUES ('BRH-0000', 'unknown', 'unknown', '0123768425')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sql = "INSERT INTO branch VALUES ('BRH-0001', 'anotherUnknown', 'unknown', '0123768426')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $sql = "INSERT INTO branch VALUES ('BRH-0002', 'anotherAnotherunknown', 'unknown', '0123768427')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();


} catch(PDOException $e) {

    echo "Can't create procedure";

}















$pdo = new PDO('mysql:host=localhost;dbname=anotherAssignment', 'customers', 'password');

if (isset($_POST["submit"])) {

    $sql = "SELECT * FROM customer;";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
        
        if ($_POST["username"] == $rows["number"] || $_POST["username"] == $rows["email"]) {
            if (password_verify($_POST["password"], $rows["password"])) {
                $_SESSION["id"] = $rows["id"];
                if ($_POST["username"] == "0127836123" || $_POST["username"] == "admin@gmail.com") {
                    header('Location: another-page.php');
                    exit;
                } else {
                    header('Location: page.php');
                    exit;
                }

                
            }
        }
    }
}


?>


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


echo "<label for='username' class='place'>";
echo "</label>";

echo "<div class='place'>";
echo "<input type='text' id='username' name='username' class='another-place' placeholder='Name'/>";
echo "</div>";



echo "<label for='password' class='place'>";
echo "</label>";



echo "<div class='place'>";


echo "<input type='password' id='password' name='password' class='another-place' placeholder='Password'/>";

echo "</div>";

echo "</div>";

echo "<div class='another-page'>";

echo "<a href='register.php' style='text-decoration: none; color: purple; font-family: arial'>Sign Up</a>";


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