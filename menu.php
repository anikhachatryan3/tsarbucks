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

    $stmt = $db->prepare("SELECT * FROM products");
    if($stmt->execute()) {
        $result = $stmt->fetchAll();
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
        
        <h1 style="padding-bottom: 10px; padding-left: 16px;"> Menu </h1>
        <div class="container-fluid">
            <div class="row border">
                <h3 class="col-5">Product Name</h3>
                <h3 class="col-2">Price</h3>
                <h3 class="col-2">Size (oz)</h3>
            </div>
            <?php
                for($i = 0; $i < count($result); $i++) {
                    
            ?>
            <div class="row border border-top-0" style="font-size: 19px; padding: 8px;">
                <div class="col-5">
                    <?php
                        echo $result[$i]['display_name'];
                    ?>
                </div>
                <div class="col-2">
                    <?php
                        echo '$'.$result[$i]['price'];
                    ?>
                </div>
                <div class="col-2 text-center" style="padding-right: 130px">
                    <?php
                        echo $result[$i]['size'];
                    ?>
                </div>
                <form method="POST" action="add.php">
                    <input type="hidden" name="id" value="<?php echo $result[$i]["product_id"]; ?>">
                    <input type="submit" name="submit" value="Add to Cart" class="btn btn-primary">
                </form>
            </div>
            <?php
                }
            ?>
        </div>

        <footer>
            <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
        </footer>

    </body>
</html>
