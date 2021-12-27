<?php
    include("connectionData.txt");
    $conn = mysqli_connect($server, $user, $pass, $dbname, $port) or die('Error connecting to MySQL server.');

    function selectAllMaterials(){
        global $conn;
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
        $stmt->close();
    }

    function selectAllEmployees(){
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM employee;");
        $stmt->execute();
        $result = $stmt->get_result();

        print "<table>\n<tr>\n<th>SSN</th><th>fname</th><th>lname</th><th>wage</th></tr>";
        while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
            print "<tr>";
            print "<td>$row[ssn]</td><td>$row[fname]</td><td>$row[lname]</td><td>$row[wage]</td>";
            print "</tr>";
        }
        print "</table>";
        mysqli_free_result($result);
        $stmt->close();
    }

    function selectAllPurchasedMaterials(){
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM purchased_material;");
        $stmt->execute();
        $result = $stmt->get_result();

        print "<table>\n<tr>\n<th>material_id</th><th>po_id</th><th>quantity</th></tr>";
        while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
            print "<tr>";
            print "<td>$row[material_id]</td><td>$row[po_id]</td><td>$row[quantity]</td>";
            print "</tr>";
        }
        print "</table>";
        mysqli_free_result($result);
        $stmt->close();
    }

    function selectAllProductRequiresMaterial(){
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM product_requires_material;");
        $stmt->execute();
        $result = $stmt->get_result();

        print "<table>\n<tr>\n<th>product_id</th><th>material_id</th></tr>";
        while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
            print "<tr>";
            print "<td>$row[product_id]</td><td>$row[material_id]</td>";
            print "</tr>";
        }
        print "</table>";
        mysqli_free_result($result);
        $stmt->close();
    }

    function selectAllOrders(){
        global $conn;
        //$stmt = $conn->prepare("SELECT ?,?,?,? FROM stp.order;");
        $stmt = $conn->prepare("SELECT * FROM stp.order;");
        //$stmt->bind_param('isss',$order_id,$ship_by_date,$order_date,$customer_id);
        $stmt->execute();
        $result = $stmt->get_result();

        print "<table>\n<tr>\n<th>order_id</th><th>ship_by_date</th><th>order_date</th><th>customer_id</th></tr>";
        while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
            print "<tr>";
            print "<td>$row[order_id]</td><td>$row[ship_by_date]</td><td>$row[order_date]</td><td>$row[customer_id]</td>";
            print "</tr>";
        }
        print "</table>";
        mysqli_free_result($result);
        $stmt->close();
    }
    function selectAllOrderContainsProduct(){
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM order_contains_product;");
        $stmt->execute();
        $result = $stmt->get_result();

        print "<table>\n<tr>\n<th>order_id</th><th>product_id</th><th>quantity</th></tr>";
        while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
            print "<tr>";
            print "<td>$row[order_id]</td><td>$row[product_id]</td><td>$row[quantity]</td>";
            print "</tr>";
        }
        print "</table>";
        mysqli_free_result($result);
        $stmt->close();
    }

    function selectAllCustomers(){
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM customer;");
        $stmt->execute();
        $result = $stmt->get_result();

        print "<table>\n<tr>\n<th>customer_id</th><th>fname</th><th>lname</th><th>address</th><th>email</th></tr>";
        while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
            print "<tr>";
            print "<td>$row[customer_id]</td><td>$row[fname]</td><td>$row[lname]</td><td>$row[address]</td><td>$row[email]</td>";
            print "</tr>";
        }
        print "</table>";
        mysqli_free_result($result);
        $stmt->close();
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
    function selectAllSuppliers(){
        global $conn;
        $query2= "SELECT * FROM supplier";
        $result = mysqli_query($conn, $query2)
        or die(mysqli_error($conn));

        print "<table>\n<tr>\n<th>Supplier ID</th><th>Supplier Name</th><th>Email</th><th>Website</th></tr>";
        while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
            print "<tr>";
            print "<td>$row[supplier_id]</td><td>$row[name]</td><td>$row[email]</td><td>$row[website]</td>";
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
        $stmt->close();
    }
?>


