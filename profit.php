<html>
    <head>
        <title>CIS451 Final Project: stp</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php
            include("connectionData.txt");
            include("profitFunctions.php");
        ?>
        <h3>CIS451 Final Project - Kelemen Szimonisz | Profit Analysis</h3>
        <p><a href="index.html">Homepage</a></p>
        <hr>
        <div class="container">
            <div class="item">
                <h4>Profit Per Product:</h4>
                <?php
                    profitPerProduct();
                ?>
            </div>
            <div class="item">
                <h4>Profit Margin:</h4>
                <?php
                    profitMargin();
                ?>
            </div>
            <div class="item">
                <p><a href="txt_files/profit.txt" >Contents</a> of this page.</p>
                <p><a href="txt_files/profitFunctions.txt" >Contents</a> of the PHP functions that get called.</p>
                <p><a href="index.html">Homepage</a></p>
            </div>
        </div> <!--- container --->
    </body>
</html>
