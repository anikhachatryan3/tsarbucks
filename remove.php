<?php
    session_start();
    
    $id = $_POST["id"];
    if($_SESSION["cart"][$id] > 0) {
        $_SESSION["cart"][$id]--;
    }
    if($_SESSION["cartTotal"] > 0) {
        $_SESSION["cartTotal"]--;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="refresh" content="0; url=cart.php"/>
    </head>
</html>