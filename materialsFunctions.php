<?php
    include("connectionData.txt");
    $conn = mysqli_connect($server, $user, $pass, $dbname, $port) or die('Error connecting to MySQL server.');

    function selectAllMaterials(){
        global $conn;
        //$query= "SELECT material_id,description,CONCAT('$',cost) as cost,inventory_count from material;";
        //$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        $stmt = $conn->prepare("SELECT material_id,description,CONCAT('$',cost) as cost,inventory_count from material;");
        $stmt->execute();
        $result = $stmt->get_result();

        print "<table>\n<tr>\n<th>Material ID</th><th>Description</th><th>Cost</th><th>Inventory</th></tr>";
        while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
            print "<tr>";
            print "<td>$row[material_id]</td><td>$row[description]</td><td>$row[cost]</td><td>$row[inventory_count]</td>";
            print "</tr>";
        }
        print "</table>";
        mysqli_free_result($result);
    }

    function selectAllPurchaseOrders(){
        global $conn;
        $stmt = $conn->prepare("SELECT po_id, po_date, employee.lname, supplier.name, material_id, 
                                    quantity, CONCAT('$',quantity*cost) AS totalCost, received
                                FROM purchase_order
	                         JOIN employee ON purchase_order.employee_ssn=employee.ssn
                                 JOIN supplier USING(supplier_id)
                                 JOIN purchased_material USING(po_id)
                                 JOIN material USING(material_id) ORDER BY po_id DESC");
        $stmt->execute();
        $result = $stmt->get_result();

        print "<table>\n<tr>\n<th>PO#</th><th>Date</th><th>Employee</th><th>Supplier</th><th>Purchased Material ID</th><th>Quantity</th><th>Total Cost</th><th>Received</th>";
        while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
            print "<tr>";
            if ($row['received'] !=1){
                $received="False"; 
            }
            else{
                $received="True";
            }
            print "<td>$row[po_id]</td><td>$row[po_date]</td><td>$row[lname]<td>$row[name]</td><td>$row[material_id]</td><td>$row[quantity]<td>$row[totalCost]</td><td>$received</td>";
            print "</tr>";
        }
        print "</table>";
        mysqli_free_result($result);
    }

    function insertPurchaseOrderExistingMaterial(){
        global $conn;

        $podate= $_POST['po_date'];
        $supplier= $_POST['po_supplier'];
        $employeeid = $_POST['emp_id'];
        $pmid= $_POST['pm_id'];
        $pmquantity= $_POST['pm_quantity'];

        $materialdescr= $_POST['m_descr'];
        $materialcost= $_POST['m_cost'];
        $materialinvcount = $_POST['m_invcount'];
        $materialNewOrExisting = $_POST['materialNewOrExisting'];

        if($materialNewOrExisting == "existingMaterial"){

            $stmt1 = $conn->prepare("INSERT INTO purchase_order(po_date,employee_ssn,supplier_id,received) 
                                    SELECT ?, ? ,(SELECT supplier_id 
                                                  FROM supplier 
                                                  WHERE supplier.name = ?),0;");
            $stmt1->bind_param('sis',$podate,$employeeid,$supplier);
            $stmt2 = $conn->prepare("INSERT INTO purchased_material(material_id,po_id,quantity) VALUES (? ,LAST_INSERT_ID(), ?);");
            $stmt2->bind_param('ii',$pmid,$pmquantity);
            $conn->begin_transaction(); 
            try{
                $stmt1->execute();
                $stmt2->execute();
                $conn->commit();
            }
            catch(Exception $e){
                $conn->rollBack();
                echo "SQL error = {$e->getMessage()}";
            }
        }
        else if($materialNewOrExisting == "newMaterial"){
            $query1 = "START TRANSACTION; ";
            $result1 = mysqli_query($conn, $query1) or die(mysqli_error($conn));

            $query2 = "INSERT INTO purchase_order(po_date,employee_ssn,supplier_id,received)
                       SELECT ";
            $query2 = $query2."'".$podate."','".$employeeid."',(SELECT supplier_id FROM supplier WHERE supplier.name = '".$supplier."'),0;";
            $result2 = mysqli_query($conn, $query2) or die(mysqli_error($conn));
            
            $poidquery = "SELECT LAST_INSERT_ID()";
            $poidResult = mysqli_query($conn, $poidquery) or die(mysqli_error($conn));
            $poid = mysqli_fetch_array($poidResult)[0];

            // purchased materials are NOT added to inventory count after a PO is created
            $insertMaterialQuery = "INSERT INTO material(description,cost,inventory_count) VALUES('".$materialdescr."',".$materialcost.",0);";
            $insertMaterialResult = mysqli_query($conn, $insertMaterialQuery) or die(mysqli_error($conn));

            $newMaterialIDquery = "SELECT LAST_INSERT_ID();";
            $newMaterialIDResult = mysqli_query($conn, $newMaterialIDquery) or die(mysqli_error($conn));
            $newMaterialID = mysqli_fetch_array($newMaterialIDResult)[0];

            $query3 = "INSERT INTO purchased_material(material_id,po_id,quantity) VALUES (";
            $query3 = $query3.$newMaterialID.",".$poid.",".$materialinvcount.");";
            $result3 = mysqli_query($conn, $query3) or die(mysqli_error($conn));

            $query4 = "COMMIT;";
            $result4 = mysqli_query($conn, $query4) or die(mysqli_error($conn));
        }
        else{
            echo "Nope";
        }
    }

    function deleteMaterial(){
        global $conn;
        $materialid= $_POST['m_id'];
        $materialid = mysqli_real_escape_string($conn, $materialid);

        $query = "SELECT COUNT(*) AS dependentProducts FROM product_requires_material WHERE material_id=".$materialid.";";
        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        $dependentProductCount = mysqli_fetch_array($result)['dependentProducts'];
        if ($dependentProductCount == 0){
            $query = "DELETE FROM purchased_material WHERE material_id=";
            $query = $query1.$materialid;
            $result = mysqli_query($conn, $query1) or die(mysqli_error($conn));
            mysqli_free_result($result);

            $query = "DELETE FROM material WHERE material_id=";
            $query = $query2.$materialid;
            $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
            mysqli_free_result($result);
            // refresh the page
            echo "<meta http-equiv='refresh' content='0'>";
        }
        else{
            print "CANNOT DELETE THIS MATERIAL. A PRODUCT DEPENDS UPON IT.";
        }

    }
    function receiveInventory(){
        global $conn;
        $poid= $_POST['received_poid'];
        $poid= mysqli_real_escape_string($conn, $poid);
    
        $query = "SELECT received FROM purchase_order WHERE po_id=".$poid.";";
        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        $received= mysqli_fetch_array($result)['received'];

        if($received == 0){
            $query = "UPDATE purchase_order SET received=1 WHERE po_id=".$poid.";";
            $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        
            $query = "SELECT material_id,quantity FROM purchased_material WHERE po_id=".$poid.";";
            $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
            $materialid_array = mysqli_fetch_array($result);
            $materialid = $materialid_array['material_id'];
           
            $received_quantity = $materialid_array['quantity'];

            $query = "UPDATE material SET inventory_count = inventory_count + ".$received_quantity." WHERE material_id=".$materialid.";";
            $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
        }
        else{
            print "<p>";
            print "Already received this PO.";
            print "</p>";
        }
    }

?>


