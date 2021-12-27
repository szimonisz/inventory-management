<?php
    include("connectionData.txt");
    $conn = mysqli_connect($server, $user, $pass, $dbname, $port) or die('Error connecting to MySQL server.');

    function profitPerProduct(){
        global $conn;
        $query = "SELECT product_id,product.description,product.type,CONCAT('$',product.price) AS price,CONCAT('$',SUM(material.cost)) as totalCost, CONCAT('$',product.price-SUM(material.cost)) AS profit
                  FROM product
	            JOIN product_requires_material USING(product_id)
	            JOIN material USING(material_id)
                  GROUP BY product_id";
        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

        print "<table>\n<tr>\n<th>Product ID</th><th>Description</th><th>Type</th><th>Price</th><th>Cost of Materials</th><th>Profit</th></tr>";
        while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
            print "<tr>";
            print "<td>$row[product_id]</td><td>$row[description]</td><td>$row[type]</td><td>$row[price]</td><td>$row[totalCost]</td><td>$row[profit]</td>";
            print "</tr>";
        }
        print "</table>";
        mysqli_free_result($result);
    }

    function profitMargin(){
        global $conn;
        $query = "SELECT CONCAT('$',totalRevenue) as totalRevenue,CONCAT('$',totalExpenses) as totalExpenses,CONCAT(ROUND(((totalRevenue - totalExpenses) / NULLIF(totalRevenue,0)),3),'%') as profitMargin
                  FROM
                  (SELECT SUM(totalCost) as totalExpenses, SUM(price) as totalRevenue
                  FROM(
	            SELECT product_id,product.description,product.price as price,SUM(material.cost) as totalCost
	            FROM product
		     JOIN product_requires_material USING(product_id)
		     JOIN material USING(material_id)
	          GROUP BY product_id) as sub)as sub2;";

        $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

        print "<table>\n<tr>\n<th>Total Potential Revenue</th><th>Total Expenses</th><th>Profit Margin</th></tr>";
        while($row = mysqli_fetch_array($result, MYSQLI_BOTH)){
            //$profitmargin = (($row['totalRevenue'] - $row['totalExpenses']) / $row['totalRevenue']) * 100;
            print "<tr>";
            //print "<td>$row[totalRevenue]</td><td>$row[totalExpenses]</td><td>$profitmargin</td>";
            print "<td>$row[totalRevenue]</td><td>$row[totalExpenses]</td><td>$row[profitMargin]</td>";
            print "</tr>";
        }
        print "</table>";
        mysqli_free_result($result);
    }

?>


