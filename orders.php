<?php
    session_start();
    // var_dump($_SESSION["userid"]);
    
    $conn = "mysql:host=127.0.0.1;port=8889;dbname=tsarbucks";

    try {
        $db = new PDO($conn, "root", "root", [
            PDO::ATTR_PERSISTENT => true
        ]); // use the proper root credentials
    }
    catch(PDOException $e) {
        die("Could not connect: " . $e->getMessage());
    }

    $stmt = $db->prepare("SELECT * FROM orders WHERE user_id = ?");
    if($stmt->execute([$_SESSION["userid"]])) {
        $result = $stmt->fetchAll();
        // var_dump($result);
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
        <div class="topnav row" style="background-color: #292b2c;">
            <a class="active col-1 h3" style="color: white; margin-left: 15px; margin-bottom: 20px; padding-left: 20px;">Tsarbucks</a>
            <a href="menu.php" class="h4" style="color: #949595; padding-top: 16px; padding-left: 40px;">Home</a>
            <a href="orders.php" class="h4" style="color: #949595; padding-top: 16px; padding-left: 40px;">Orders</a>
            <a class="col-2 h4" style="color: #949595; padding-top: 16px; padding-left: 90px;">
                <?php
                    echo "Hello, " . $_SESSION["name"];
                ?>
            </a>
            <a class="col-2 h4" style="text-align: right; color: #949595; padding-top: 16px; padding-left: 130px;">My Cart</a>
            <a href="logout.php" class="col-1 h4" style="text-align: right; color: #949595; padding-top: 16px; padding-left: 1px;">Logout</a>
        </div>
        <h1 style="padding-left: 10px;">My Orders</h1>
        <div class="container-fluid">
            <?php
                $numOrders = $result[count($result)-1]["order_id"];
                for($order = 1; $order <= $numOrders; $order++) {
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
            <!-- </div> -->
        </div>
    </body>
</html>
