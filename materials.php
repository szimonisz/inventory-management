<?php include('materialsFunctions.php'); ?>
<html>
    <head>
        <title>CIS451 Final Project: stp</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h3>CIS451 Final Project - Kelemen Szimonisz | Materials and Purchase Orders</h3>
        <p><a href="index.html">Homepage</a></p>
        <hr>
        <div class="container">
            <div class="item">
                <h4>Materials in Database:</h4>
                <?php selectAllMaterials(); ?>
            </div>
            <div class="item">
                <h4>Purchase Orders:</h4>
                <?php selectAllPurchaseOrders(); ?>
            </div>
            <div class="item">
                <h4>New Purchase Order (Purchase one material per PO):</h4>
                <form action="#" method="POST">
                    <label for="emp_id">Employee ID:</label>
                    <input id="emp_id" name= "emp_id" type="text" value="123456789"> <br>
                    <label for="po_date">PO Date:</label>
                    <input id="po_date" name= "po_date" type="text" value="2021-12-05"> <br>
                    <label for="po_supplier">Supplier:</label>
                    <input id="po_supplier" name= "po_supplier" type="text" value="Target"> <br>
                
                    <hr>
                    <p>OPTION A: Purchase an existing material<p>
                    <label for="existingMaterial">Existing Material:</label>
                    <input type ="radio" name="materialNewOrExisting" value="existingMaterial" id="existingMaterial" checked/> <br>

                    <label for="pm_id">Purchased Material ID:</label>
                    <input id="pm_id" name= "pm_id" type="text"> <br>
                    <label for="pm_quantity">Quantity of Material:</label>
                    <input id="pm_quantity" name= "pm_quantity" type="text"> <br>
                    <hr>

                    <p>OPTION B: Purchase a new material<p>
                    <label for="newMaterial">New Material:</label>
                    <input type ="radio" name="materialNewOrExisting" value="newMaterial" id="newMaterial"/><br>

                    <label for="m_descr">Material Description:</label>
                    <input id="m_descr" name= "m_descr" type="text"> <br>
                    <label for="m_cost">Material Cost:</label>
                    <input id="m_cost" name= "m_cost" type="text"> <br>
                    <label for="m_cost">Material Quantity:</label>
                    <input id="m_invcount" name= "m_invcount" type="text"> <br>
                
                    <input type="submit" value="submit" name="submitButton">
                    <input type="reset" value="erase">
                </form>
                <?php
                    if(isset($_POST['submitButton'])) {
                        insertPurchaseOrderExistingMaterial();
                        // refresh the page
                        echo "<meta http-equiv='refresh' content='0'>";
                    }
                ?>
                <hr>
                <h4>Remove a material from the database:</h4>
                <form action="#" method="POST">
                    <label for="m_id">Material ID:</label>
                    <input id="m_id" name= "m_id" type="text"> <br>
                    <input type="submit" value="submit" name="submitButton2">
                    <input type="reset" value="erase">
                </form>
                <?php
                    if(isset($_POST['submitButton2'])) {
                        deleteMaterial();
                    }
                ?>
                <hr>
                <h4>Receive Inventory:</h4>
                <form action="#" method="POST">
                    <label for="received_poid">PO#:</label>
                    <input id="received_poid" name= "received_poid" type="text"> <br>
                    <input type="submit" value="submit" name="submitButton3">
                    <input type="reset" value="erase">
                </form>
                <?php
                    if(isset($_POST['submitButton3'])) {
                        receiveInventory();
                        // refresh the page
                        echo "<meta http-equiv='refresh' content='0'>";
                    }
                ?>
                
            </div>
            <div class="item">
                <p><a href="txt_files/materials.txt" >Contents</a> of this page.</p>
                <p><a href="txt_files/materialsFunctions.txt" >Contents</a> of the PHP functions that get called.</p>
                <p><a href="index.html">Homepage</a></p>
            </div>
        </div> <!--- container --->
    </body>
</html>
