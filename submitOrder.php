<?php
    session_start();
    // echo "OUT HERE";
    $conn = "mysql:host=127.0.0.1;port=8889;dbname=tsarbucks";

    try {
        $db = new PDO($conn, "root", "root", [
            PDO::ATTR_PERSISTENT => true
        ]); // use the proper root credentials
    }
    catch(PDOException $e) {
        die("Could not connect: " . $e->getMessage());
    }
    // echo "OVER HERE";
    // echo $_SESSION["cartTotal"];
    if($_SESSION["cartTotal"] > 0) {
        // echo "HERE";
        $stmt = $db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_id DESC");
        $orderID = 0;
        if($stmt->execute([$_SESSION["userid"]])) {
            $result = $stmt->fetchAll();
            $orderID = $result[0]["order_id"] + 1;
        }
        $stmt = $db->prepare("INSERT INTO orders (order_id, user_id, product_id, quantity, completed) VALUES (?, ?, ?, ?, ?)");

        // insert the new record into the drinks table using the data array
        foreach ($_SESSION["cart"] as $index => $value) {
            if($value > 0) {
                echo $orderId;
                if($stmt->execute([$orderID, $_SESSION["userid"], $index, $value, 0])) {
                    $_SESSION["cart"][$index] = 0;
                }
            }
        }
        $_SESSION["cartTotal"] = 0;
    }
    else {
        echo "Cart is empty; please add an item to cart.";
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="refresh" content="0; url=orders.php"/>
    </head>
</html>