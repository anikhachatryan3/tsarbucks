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
        <h1 style="padding-left: 10px;">My Cart</h1>
        <div class="container-fluid">
        <div style="font-size: 18px; padding: 10px;">
        <?php
            if($_SESSION["cartTotal"] == 0) {
                echo "Cart is empty; please add items to your cart.";
            }
            else {
        ?>
        </div>
            <div class="row border h3">
                <div class="col-5">Product Name</div>
                <div class="col-2" style="padding-right: 100px;">Price</div>
                <div class="col-2" style="padding-right: 100px;">Quantity</div>
                <div class="col-2" style="padding-right: 100px;">Size</div>
            </div>
            <?php
            $totalPrice = 0;
            $totalSize = 0;
            foreach ($_SESSION["cart"] as $index => $value) {
                if($value > 0) {
                    $stmt = $db->prepare("SELECT * FROM products WHERE product_id = ?");
                    if($stmt->execute([$index])) {
                        $result = $stmt->fetchAll();
                    }
                    $totalPrice += $result[0]["price"] * $value;
                    $totalSize += $result[0]["size"] * $value;
            ?>
            <div class="row border" style="font-size: 18px;">
                <div class="col-5">
                    <?php
                            echo $result[0]["display_name"];
                    ?>
                </div>
                <div class="col-2" style="padding-left: 18px;">
                    <?php
                            echo "$" . $result[0]["price"];
                    ?>
                </div>
                <div class="col-2" style="padding-right: 100px;">
                    <?php
                            echo $value;
                    ?>
                </div>
                <div class="col-1 text-center" style="padding-right: 60px;">
                    <?php
                            echo $result[0]["size"];
                    ?>
                </div>
                <div class="col-1">
                    <form method="POST" action="remove.php">
                        <input type="hidden" name="id" value="<?php echo $result[0]["product_id"]; ?>">
                        <input class="btn btn-danger" type="submit" name="submit" value="Remove From Cart">
                    </form>
                </div>
            </div>
            <?php
                    }
                }
            ?>
            <div style="font-size: 18px; font-weight: 500; padding-top: 10px;">
                <?php
                    echo "Total Cost: $" . number_format((float)$totalPrice, 2, '.', '');
                ?>
            </div>
            <div style="font-size: 18px; font-weight: 500; padding-bottom: 10px; padding-top: 10px;">
                    <?php
                        echo "Total Size: " . $totalSize . " ounces";
                    ?>
            </div>
            <form method="POST" action="submitOrder.php">
                <input class="btn btn-primary" type="submit" name="submit" value="Submit Order">
            </form>
        <?php
            }
        ?>
        </div>
    </body>
</html>
