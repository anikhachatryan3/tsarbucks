<?php
    session_start();
    
    $id = $_POST["id"];
    $_SESSION["cart"][$id]--;
    $_SESSION["cartTotal"]--;
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="refresh" content="0; url=cart.php"/>
    </head>
</html>