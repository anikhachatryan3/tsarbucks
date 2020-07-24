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
        var_dump($result);
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
            <a class="active col-1 h3" style="color: white; margin-left: 15px; margin-bottom: 20px;">Tsarbucks</a>
            <a class="col-4 h4" style="color: #949595; padding-top: 16px; padding-left: 40px;">Home</a>
            <a class="col-2 h4" style="color: #949595; padding-top: 16px; padding-left: 90px;">
                <?php
                    echo "Hello, " . $_SESSION["name"];
                ?>
            </a>
            <a class="col-2 h4" style="text-align: right; color: #949595; padding-top: 16px; padding-left: 130px;">My Cart</a>
            <a href="logout.php" class="col-1 h4" style="text-align: right; color: #949595; padding-top: 16px; padding-left: 1px;">Logout</a>
        </div>
        <h1>My Orders</h1>
        <div class="container-fluid">
            <?php
                for($i = (count($result) - 1); $i >= 0; $i--) {
                    echo $result[$i]["order_id"];
                }
            ?>
        </div>
    </body>
</html>
