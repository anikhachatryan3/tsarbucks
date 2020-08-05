<?php
    session_start();
    
    $conn = "mysql:host=127.0.0.1;port=8889;dbname=tsarbucks";

    try {
        $db = new PDO($conn, "root", "root", [
            PDO::ATTR_PERSISTENT => true
        ]); // use the proper root credentials
    }
    catch(PDOException $e) {
        die("Could not connect: " . $e->getMessage());
    }

    $stmt = $db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_id DESC");
    if($stmt->execute([$_SESSION["userid"]])) {
        $result = $stmt->fetchAll();
    }
    else {
        var_dump("Unable to execute");
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

        <script type="text/javascript" src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    </head>
    <body>
        <?php
            include "nav.php";
        ?>
        <h1 style="padding-left: 10px;">My Orders</h1>
        <div class="container-fluid">
            <?php
                $numOrders = $result[0]["order_id"];
                for($order = $numOrders; $order >= 1; $order--) {
                    $totalPrice = 0;
                    $totalSize = 0;
            ?>
                <h2>Order
                    <?php
                    echo $order;
                    ?>
                </h2>
                <div class="row border h3">
                    <div class="col-5">Product Name</div>
                    <div class="col-2" style="padding-right: 100px;">Size</div>
                    <div class="col-2" style="padding-right: 100px;">Quantity</div>
                    <div class="col-2" style="padding-right: 100px;">Price</div>
                    <div class="col-1">Status</div>
                </div>
                <?php
                    for($i = 0; $i < count($result); $i++) {
                        if($result[$i]["order_id"] == $order) {
                            $stmt2 = $db->prepare("SELECT * FROM products WHERE product_id = ?");
                            if($stmt2->execute([$result[$i]["product_id"]])) {
                                $result2 = $stmt2->fetchAll();
                            }
                        $totalPrice += $result2[0]["price"];
                        $totalSize += $result2[0]["size"];
                ?>
                <div class="row border" style="font-size: 18px;">
                    <div class="col-5">
                    <?php
                        echo $result2[0]["display_name"];
                    ?>
                    </div>
                    <div class="col-2" style="padding-right: 100px;">
                    <?php
                        echo $result2[0]["size"];
                    ?>
                    </div>
                    <div class="col-2" style="padding-right: 100px;">
                    <?php
                        echo $result[$i]["quantity"];
                    ?>
                    </div>
                    <div class="col-2" style="padding-right: 100px;">
                    <?php
                        echo "$" . $result2[0]["price"];
                    ?>
                    </div>
                    <div class="col-1">
                            <?php
                                if($result[$i]["completed"] == 1) {
                            ?>
                                    <div style="color: green;">Complete</div>
                            <?php
                                }
                                else {
                            ?>
                                    <div style="color: grey;">Pending</div>
                            <?php
                                }
                            ?>
                    </div>
                </div>
                    <?php
                        }
                    }
                    ?>
                    <div class="text-right" style="font-size: 18px; font-weight: 500; padding-top: 10px;">
                        <?php
                            echo "Total Price: $" . number_format((float)$totalPrice, 2, '.', '');
                        ?>
                    </div>
                    <div class="text-right" style="font-size: 18px; font-weight: 500;">
                        <?php
                            echo "Total Size: " . $totalSize . " oz";
                        ?>
                    </div>
                    <?php
                }
            ?>
        </div>
    </body>
</html>
