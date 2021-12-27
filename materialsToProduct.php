<html>
    <head>
        <title>CIS451 Final Project: stp</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php
            include("materialsToProductFunctions.php");
        ?>
        <h3>CIS451 Final Project - Kelemen Szimonisz | Convert Materials to Products</h3>
        <p><a href="index.html">Homepage</a></p>
        <hr>
        <div class="container">
            <div class="item">
                <h4>Products in Database:</h4>
                <?php
                    selectAllProducts();
                ?>
            </div>
            <div class="item">
                <h4>Materials in Database:</h4>
                <?php
                    selectAllMaterials();
                ?>
            </div>
            <div class="item">
                <h4>Products that can be produced with current material inventory:</h4>
                <?php
                   countProducableProducts(); 
                ?>
            </div>
            <div class="item">
                <h4>What products are out of stock?</h4>
                <?php
                   countOOSProducts(); 
                ?>
            </div>
            <div class="item">
                <h4>What materials are out of stock?</h4>
                <?php
                   countOOSMaterials(); 
                ?>
            </div>
            <div class="item">
                <h4>What materials are required to make a specific product?</h4>
                <form action="#" method="POST">
                    <label for="p_id">Product ID:</label>
                    <input id="p_id" name= "p_id" type="text"> <br>
                    <input type="submit" value="submit" name="submitButton2" id="submitButton2">
                    <input type="reset" value="erase">
                </form>
                <?php
                    if(isset($_POST['submitButton2'])) {
                        requiredMaterials(); 
                    }
                ?>
            </div>
            <div class="item">
                <h4>Produce a product from existing materials:</h4>
                <form action="#" method="POST">
                    <label for="p_id">Product ID:</label>
                    <input id="p_id" name= "p_id" type="text"> <br>
                    <label for="p_invcount">Quantity:</label>
                    <input id="p_invcount" name= "p_invcount" type="text"> <br>

                    <input type="submit" value="submit" name="submitButton" id="submitButton">
                    <input type="reset" value="erase">
                </form>
                <?php
                    if(isset($_POST['submitButton'])) {
                        $productProduced = produceProduct();
                    }
                ?>
            </div>
            <div class="item">
                <h4>Add a new product to the database:</h4>
                <form action="#" method="POST">
                    <label for="p_descr">Product Description:</label>
                    <input id="p_descr" name= "p_descr" type="text"> <br>
                    <label for="p_price">Price:</label>
                    <input id="p_price" name= "p_price" type="text"> <br>

                    <label>Type:</label><br>
                    <input type="radio" id="sticker" name="p_type" value="Sticker">
                    <label for="sticker">Sticker</label><br>

                    <input type="radio" id="card" name="p_type" value="Card">
                    <label for="card">Card</label><br>
                    <input type="radio" id="magnet" name="p_type" value="Magnet">
                    <label for="magnet">Magnet</label><br>
                    <input type="radio" id="tshirt" name="p_type" value="T-Shirt">
                    <label for="tshirt">T-Shirt</label><br>

                    <p>What materials are required to make this product? (Separate with commas)<p>
                    <label for="m_id">Material ID:</label>
                    <input id="m_id" name= "m_id" type="text"> <br>

                    <input type="submit" value="submit" name="submitButton6" id="submitButton">
                    <input type="reset" value="erase">
                </form>
                <?php
                    if(isset($_POST['submitButton6'])) {
                        insertProductAndProductRequiresMaterial();
                        // refresh the page to update the products list
                        echo "<meta http-equiv='refresh' content='0'>";
                    }
                ?>
                <hr>
                <h4>Remove a product from the database:</h4>
                <form action="#" method="POST">
                    <label for="p_id">Product ID:</label>
                    <input id="p_id" name= "p_id" type="text"> <br>
                    <input type="submit" value="submit" name="submitButton7">
                    <input type="reset" value="erase">
                </form>
                <?php
                    if(isset($_POST['submitButton7'])) {
                        deleteProductAndProductRequiresMaterial();
                        // refresh the page to update the products list
                        echo "<meta http-equiv='refresh' content='0'>";
                    }
                ?>
            </div>

            <div class="item">
                <p><a href="txt_files/materialsToProduct.txt" >Contents</a> of this page.</p>
                <p><a href="txt_files/materialsToProductFunctions.txt" >Contents</a> of the PHP functions that get called.</p>
                <p><a href="index.html">Homepage</a></p>
            </div>
        </div> <!--- container --->
    </body>
</html>
