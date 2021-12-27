<?php
    include("connectionData.txt");
    $conn = mysqli_connect($server, $user, $pass, $dbname, $port) or die('Error connecting to MySQL server.');
    
    function requiredMaterials(){
        global $conn;
        $productid= $_POST['p_id'];
        $productid= mysqli_real_escape_string($conn, $productid);
        $query = "SELECT material.material_id,material.description,CONCAT('$',material.cost) as cost,material.inventory_count
                  FROM product_requires_material JOIN material USING(material_id)
                  WHERE product_id=".$productid.";";
        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        print "<table>\n<tr>\n<th>Material ID</th><th>Description</th><th>Cost</th><th>Inventory</th></tr>";
        while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
            print "<tr>";
            print "<td>$row[material_id]</td><td>$row[description]</td><td>$row[cost]</td><td>$row[inventory_count]</td>";
            print "</tr>";
        }
        print "</table>";
        mysqli_free_result($result);
    }

    function countProducableProducts(){
        global $conn;
$query2 = "SELECT product_id,description,inventory_count,CONCAT('$',price) as price,type
             FROM product
             WHERE product_id NOT IN 
            (SELECT  product.product_id
	        FROM product 
	        JOIN product_requires_material USING(product_id)
	        JOIN material USING(material_id)
                WHERE material.inventory_count = 0
	        ORDER BY product.product_id);";
        //$query2= "SELECT DISTINCT product.product_id,product.description,product.inventory_count,CONCAT('$',product.price) as price,product.type
        //          FROM product 
        //            JOIN product_requires_material USING(product_id)
        //            JOIN material USING(material_id)
        //          WHERE material.inventory_count > 0 ORDER BY product.product_id";
        $result = mysqli_query($conn, $query2) or die(mysqli_error($conn));
        print "<table>\n<tr>\n<th>Product ID</th><th>Description</th><th>Inventory</th><th>Price</th><th>Type</th><tr>";
        while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
            print "<tr>";
            print "<td>$row[product_id]</td><td>$row[description]</td><td>$row[inventory_count]</td><td>$row[price]</td><td>$row[type]</td>";
            print "</tr>";
        }
        print "</table>";
        mysqli_free_result($result);
    }

    function countOOSProducts(){
        global $conn;
        $query2 = "SELECT DISTINCT product.product_id,product.description,product.inventory_count,CONCAT('$',product.price) as price, product.type
                    FROM product 
                    JOIN product_requires_material USING(product_id)
                    JOIN material USING(material_id)
                    WHERE product.inventory_count = 0";
        //$query2= "SELECT product.product_id,product.description,product.inventory_count,CONCAT('$',product.price) as price,product.type,material.material_id,material.description as materialDescription, material.inventory_count AS materialInventory
        //          FROM product 
        //            JOIN product_requires_material USING(product_id)
        //            JOIN material USING(material_id)
        //          WHERE product.inventory_count = 0";
        $result = mysqli_query($conn, $query2) or die(mysqli_error($conn));
        print "<table>\n<tr>\n<th>Product ID</th><th>Description</th><th>Inventory</th><th>Price</th><th>Type</th></tr>";
        while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
            print "<tr>";
            print "<td>$row[product_id]</td><td>$row[description]</td><td>$row[inventory_count]</td><td>$row[price]</td><td>$row[type]</td>";
            print "</tr>";
        }
        print "</table>";
        mysqli_free_result($result);
    }
    function selectAllProducts(){
        global $conn;
        $query2= "SELECT product_id,description,CONCAT('$',price) as price,type,inventory_count from product";
        $result = mysqli_query($conn, $query2)
        or die(mysqli_error($conn));

        print "<table>\n<tr>\n<th>Product ID</th><th>Description</th><th>Inventory</th><th>Price</th><th>Type</th></tr>";
        while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
            print "<tr>";
            print "<td>$row[product_id]</td><td>$row[description]</td><td>$row[inventory_count]</td><td>$row[price]</td><td>$row[type]</td>";
            print "</tr>";
        }
        print "</table>";
        mysqli_free_result($result);
    }

    function selectAllMaterials(){
        global $conn;
        
        $query3= "SELECT material_id,description,CONCAT('$',cost) as cost, inventory_count from material;";
        $result = mysqli_query($conn, $query3)
        or die(mysqli_error($conn));

        print "<table>\n<tr>\n<th>Material ID</th><th>Description</th><th>Cost</th><th>Inventory</th></tr>";
        while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
            print "<tr>";
            print "<td>$row[material_id]</td><td>$row[description]</td><td>$row[cost]</td><td>$row[inventory_count]</td>";
            print "</tr>";
        }
        print "</table>";
        mysqli_free_result($result);
    }
    function countOOSMaterials(){
        global $conn;
        
        $query3= "SELECT material_id,description,CONCAT('$',cost) as cost, inventory_count from material WHERE inventory_count=0;";
        $result = mysqli_query($conn, $query3)
        or die(mysqli_error($conn));

        print "<table>\n<tr>\n<th>Material ID</th><th>Description</th><th>Cost</th><th>Inventory</th></tr>";
        while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
            print "<tr>";
            print "<td>$row[material_id]</td><td>$row[description]</td><td>$row[cost]</td><td>$row[inventory_count]</td>";
            print "</tr>";
        }
        print "</table>";
        mysqli_free_result($result);
    }

    function produceProduct(){
        global $conn;
        $productid= $_POST['p_id'];
        $inventory= $_POST['p_invcount'];

        // this is a small attempt to avoid SQL injection
        // better to use prepared statements
        $productid= mysqli_real_escape_string($conn, $productid);
        $inventory= mysqli_real_escape_string($conn, $inventory);

        $requiredMaterialQuery = "SELECT material_id,material.inventory_count FROM product_requires_material JOIN material USING(material_id) WHERE product_id=".$productid.";";
        $result = mysqli_query($conn, $requiredMaterialQuery) or die(mysqli_error($conn));
        $data = $result->fetch_all(MYSQLI_ASSOC);

        //$requiredMaterialQuery = "SELECT inventory_count FROM product_requires_material JOIN material USING(material_id) WHERE product_id=".$productid.";";
        //$result = mysqli_query($conn, $requiredMaterialQuery) or die(mysqli_error($conn));
        //$requiredMaterialsInventoryArray = mysqli_fetch_array($result);
        
        //print count($requiredMaterialsInventoryArray);
        //foreach ($requiredMaterialsInventoryArray as &$materialInventory){
        foreach($data as $row){
            $materialIDs[] = $row;
            print " ";
            //print $requiredMaterial['inventory_count'];
            if($row['inventory_count'] - $inventory < 0){
                print "NOT ENOUGH MATERIAL INVENTORY TO CREATE PRODUCT OF DESIRED QUANTITY!";
                return;
            }
        }
        $query = "UPDATE product SET inventory_count=inventory_count +".$inventory." WHERE product_id=".$productid.";";
        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        foreach($data as $row){
            print $row['material_id'];
            $requiredMaterialID = $row['material_id'];
            $query = "UPDATE material SET inventory_count=inventory_count -".$inventory." WHERE material_id=".$requiredMaterialID.";";
            $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        }
        echo "<meta http-equiv='refresh' content='0'>";
        //unset($materialInventory);
        //$query = "UPDATE product SET inventory_count=inventory_count +".$inventory." WHERE product_id=".$productid.";";
        //$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        //$query = "UPDATE material SET inventory_count=inventory_count -".$inventory." WHERE material_id=".$requiredMaterialID.";";
        //$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        // Referesh the page to reflect changes
        //echo "<meta http-equiv='refresh' content='0'>";
        //mysqli_free_result($result);
    }
    function insertProductAndProductRequiresMaterial(){
        global $conn;
        $description= $_POST['p_descr'];
        $price= $_POST['p_price'];
        $type= $_POST['p_type'];
        $materialids= $_POST['m_id'];


        // this is a small attempt to avoid SQL injection
        // better to use prepared statements
        $description = mysqli_real_escape_string($conn, $description);
        $price= mysqli_real_escape_string($conn, $price);
        $type= mysqli_real_escape_string($conn, $type);
        $materialids= mysqli_real_escape_string($conn, $materialids);

        $materialArray = explode(",", $materialids);
        $query1 = "START TRANSACTION; ";
        $result1 = mysqli_query($conn, $query1) or die(mysqli_error($conn));

        $query2 = "INSERT INTO product (description,inventory_count,price,type) VALUES(";
        //insert with 0 inventory
        $query2 = $query2."'".$description."','0','".$price."','".$type."'); ";
        $result2 = mysqli_query($conn, $query2) or die(mysqli_error($conn));
        foreach ($materialArray as &$materialid){
            $query3 = "INSERT INTO product_requires_material VALUES(LAST_INSERT_ID(),";
            //$query3 = $query3."'".$materialid."');";
            $query3 = $query3."'".$materialid."');";
            $result3 = mysqli_query($conn, $query3) or die(mysqli_error($conn));
        }

        $query4 = "COMMIT;";
        $result4 = mysqli_query($conn, $query4) or die(mysqli_error($conn));

    }
   function deleteProductAndProductRequiresMaterial(){
        global $conn;
        $productid= $_POST['p_id'];
        $productid= mysqli_real_escape_string($conn, $productid);
        $query1= "DELETE FROM product_requires_material WHERE product_id=";
        $query1 = $query1.$productid;
        $result1 = mysqli_query($conn, $query1) or die(mysqli_error($conn));

        $query2= "DELETE FROM product WHERE product_id=";
        $query2 = $query2.$productid;
        $result2 = mysqli_query($conn, $query2) or die(mysqli_error($conn));
    }



?>


