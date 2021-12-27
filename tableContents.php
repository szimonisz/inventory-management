<html>
    <head>
        <title>CIS451 Final Project: stp</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php
            include("connectionData.txt");
            include("tableContentsFunctions.php");
        ?>
        <h3>CIS451 Final Project - Kelemen Szimonisz | Contents of Each Table</h3>
        <p><a href="index.html">Homepage</a></p>
        <hr>
        <div class="container">
            <div class="item">
                <h4>Employee Table Contents:</h4>
                <?php
                    selectAllEmployees();
                ?>
            </div>
            <div class="item">
                <h4>Supplier Table Contents:</h4>
                <?php
                    selectAllSuppliers();
                ?>
            </div>
            <div class="item">
                <h4>Purchase Order Table Contents:</h4>
                <?php
                    selectAllPurchaseOrders();
                ?>
            </div>
            <div class="item">
                <h4>Material Table Contents:</h4>
                <?php
                    selectAllMaterials();
                ?>
            </div>
            <div class="item">
                <h4>Purchased Material Table Contents:</h4>
                <?php
                    selectAllPurchasedMaterials();
                ?>
            </div>
            <div class="item">
                <h4>Product Table Contents:</h4>
                <?php
                    selectAllProducts();
                ?>
            </div>
            <div class="item">
                <h4>Product_Requires_Material Table Contents:</h4>
                <?php
                    selectAllProductRequiresMaterial();
                ?>
            </div>
            <div class="item">
                <h4>Customer Table Contents:</h4>
                <?php
                    selectAllCustomers();
                ?>
            </div>
            <div class="item">
                <h4>Order Table Contents:</h4>
                <?php
                    selectAllOrders();
                ?>
            </div>
            <div class="item">
                <h4>Order_Contains_Product Table Contents:</h4>
                <?php
                    selectAllOrderContainsProduct();
                ?>
            </div>
            <div class="item">
                <p><a href="txt_files/tableContents.txt" >Contents</a> of this page.</p>
                <p><a href="txt_files/tableContentsFunctions.txt" >Contents</a> of the PHP functions that get called.</p>
                <p><a href="index.html">Homepage</a></p>
            </div>
        </div> <!--- container --->
    </body>
</html>
