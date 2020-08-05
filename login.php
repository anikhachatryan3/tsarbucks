<?php
    session_start();

    if(isset($_SESSION["username"])) {
        header("Location: http://localhost:8888/menu.php");
    }
    else {
        if(!empty($_POST)) {
            $conn = "mysql:host=127.0.0.1;port=8889;dbname=tsarbucks";

            try {
                $db = new PDO($conn, "root", "root", [
                    PDO::ATTR_PERSISTENT => true
                ]); // use the proper root credentials
            }
            catch(PDOException $e) {
                die("Could not connect: " . $e->getMessage());
            }

            $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
            if($stmt->execute([$_POST["uname"]])) {
                $result = $stmt->fetchAll();
                if(count($result) && ($_POST["upass"] == $result[0]["password"])) {
                    $_SESSION["name"] = $result[0]["display_name"];
                    $_SESSION["username"] = $result[0]["username"];
                    $_SESSION["userid"] = $result[0]["user_id"];
                    $_SESSION["cart"] = array();
                    $_SESSION["cartSize"] = 0;
                    $_SESSION["cartTotal"] = 0;

                    $products = $db->prepare("SELECT * FROM products WHERE product_id");
                    if($products->execute()) {
                        $items = $products->fetchAll();
                        $_SESSION["cartSize"] = count($items);
                        for($i = 0; $i < count($items); $i++) {
                            $_SESSION["cart"][$i] = 0;
                        }
                    }
                    $stmt = $db->prepare("SELECT * FROM user_roles WHERE user_id = ?");
                    if($stmt->execute([$_SESSION["userid"]])) {
                        $result = $stmt->fetchAll();
                        $_SESSION["role"] = $result[0]["role"];
                    }

                    header("Location: http://localhost:8888/menu.php");
                }
                else {
                    $error = "Invalid credentials";
                }
            }
        }
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
    <div class="topnav row" style="background-color: #292b2c;">
        <a class="active col-1 h3" style="color: white; margin-left: 15px; margin-bottom: 20px;">Tsarbucks</a>
        <a class="col-9 h4" style="color: #949595; padding-top: 16px; padding-left: 40px;">Home</a>
        <a class="col-1 h4" style="text-align: right; color: white; padding-top: 16px; padding-left: 130px;">Login</a>
    </div>
    <body>
        <h1> Login </h1>
        <div class="container">
            <form method="POST" action="login.php">
                <div style="text-align: center;">
                    <label for="uname">Username</label>
                    <input type="text" style="width: 450px;" placeholder="Username" name="uname" required>
                </div>
                <div style="text-align: center;">
                    <label for="upass">Password</label>
                    <input type="password" style="width: 450px;" placeholder="Password" name="upass" required>
                </div>
                <div style="padding-left: 365px;">
                    <input type="submit" value="Login">
                </div>
                <div class="text-danger" style="padding-left: 500px; padding-top: 40px; font-size: 17px;">
                    <?php
                        echo $error;
                    ?>
                </div>
            </form>
        </div>
    </body>
</html>
