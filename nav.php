<div class="topnav row" style="background-color: #292b2c;">
    <a class="active h3 col-1" style="color: white; margin-left: 15px; margin-bottom: 20px; padding-left: 20px;">Tsarbucks</a>
    <a href="menu.php" class="h4" style="color: #949595; padding-top: 16px; padding-left: 40px;">Home</a>
    <?php
        if($_SESSION["role"] == "customer") {
    ?>
            <a href="orders.php" class="h4" style="color: #949595; padding-top: 16px; padding-left: 40px;">Orders</a>
    <?php
        }
        if($_SESSION["role"] == "barista") {
    ?>
            <a href="pendingOrders.php" class="h4" style="color: #949595; padding-top: 16px; padding-left: 40px;">Pending Orders</a>
    <?php
        }
    ?>
    <a class="col-2 h4" style="color: #949595; padding-top: 16px; padding-left: 90px;">
        <?php
            echo "Hello, " . $_SESSION["name"];
        ?>
    </a>
    <?php
        if($_SESSION["role"] == "customer") {
    ?>
            <a href="cart.php" class="col-2 h4" style="text-align: right; color: #949595; padding-top: 16px; padding-left: 130px;">My Cart
        <?php
            if($_SESSION["cartTotal"] > 0) {
                echo " (" . $_SESSION["cartTotal"] . ")";
            }
        ?>
        </a>
    <?php
        }
    ?>
    <a href="logout.php" class="col-1 h4" style="text-align: right; color: #949595; padding-top: 16px; padding-left: 1px;">Logout</a>
</div>
