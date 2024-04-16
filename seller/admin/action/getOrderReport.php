<?php 
require_once 'core.php';

if($_POST) {
    $startDate = strtotime($_POST['startDate']);
    $start_date = date("Y/m/d", $startDate);

    $endDate = strtotime($_POST['endDate']);
    $end_date = date("Y/m/d", $endDate);

    $sql = "SELECT * FROM orders WHERE order_date >= '$start_date' AND order_date <= '$end_date' AND order_status = '3'";
    $query = $db->query($sql);
    $table = '<table border="1" cellspacing="0" cellpadding="10" style="width:100%; border-collapse: collapse; margin-top: 20px; font-family: Arial, sans-serif;">
        <tr>
            <th style="text-align: left; background-color: #f2f2f2;">No</th>
            <th style="text-align: left; background-color: #f2f2f2;">Order Date</th>
            <th style="text-align: left; background-color: #f2f2f2;">Product Name</th>
            <th style="text-align: left; background-color: #f2f2f2;">Quantity</th>
            <th style="text-align: left; background-color: #f2f2f2;">Price</th>
            <th style="text-align: left; background-color: #f2f2f2;">Total Price</th> <!-- New column header -->
        </tr>';

    $totalAmount = 0;
    $no = 1;
    while ($result = $query->fetch_assoc()) {
        $product = "SELECT oi.quantity,oi.price, p.product_name FROM order_item oi
                   INNER JOIN product p ON oi.product_id = p.product_id
                   WHERE order_id = '".$result['order_id']."'";
        $product1 = $db->query($product);

        while($product2 = $product1->fetch_assoc()){
            $totalPrice = $product2['quantity'] * $product2['price']; // Calculate total price for each product
            $table .= '<tr>
                <td style="border: 1px solid #ddd; padding: 10px;">'.$no.'</td>
                <td style="border: 1px solid #ddd; padding: 10px;">'.$result['order_date'].'</td>
                <td style="border: 1px solid #ddd; padding: 10px;">'.$product2['product_name'].'</td>
                <td style="border: 1px solid #ddd; padding: 10px;">'.$product2['quantity'].'</td>
                <td style="border: 1px solid #ddd; padding: 10px;">'.$product2['price'].'</td>
                <td style="border: 1px solid #ddd; padding: 10px;">'.$totalPrice.'</td> <!-- Display total price -->
            </tr>';    
            $totalAmount += $totalPrice;
            ++$no;
        }
        
    }
    $table .= '
    <tr>
        <td colspan="5" style="text-align: center; background-color: #f2f2f2; font-weight: bold;">Total Income</td>
        <td style="background-color: #f2f2f2; font-weight: bold;">RM '.$totalAmount.'</td>
    </tr>
</table>
'; 

echo $table;
}

?>
