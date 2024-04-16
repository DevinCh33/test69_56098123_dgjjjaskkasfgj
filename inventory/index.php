<?php require_once 'includes/header.php'; ?>

<?php
$sql = "SELECT * FROM product WHERE status = 1 AND owner = '".$_SESSION['adm_id']."'";
$query = $db->query($sql);
$countProduct = $query->num_rows;

$orderSql = "SELECT * FROM orders WHERE order_status = 3";
$orderQuery = $db->query($orderSql);
$countOrder = $orderQuery->num_rows;

$totalRevenue = 0;
while ($orderResult = $orderQuery->fetch_assoc()) {
	(int)$totalRevenue += ($orderResult['quantity']*$orderResult['price']);
}

$lowStockSql = "SELECT * FROM product WHERE quantity <= 3 AND status = 1 AND owner = '".$_SESSION['adm_id']."'";
$lowStockQuery = $db->query($lowStockSql);
$countLowStock = $lowStockQuery->num_rows;

$userwisesql = "SELECT * FROM orders WHERE orders.order_belong = '".$_SESSION['store']."' AND orders.order_status < 3";
$userwiseQuery = $db->query($userwisesql);
$userwiseOrder = $userwiseQuery->num_rows;

?>

<style type="text/css">
	.ui-datepicker-calendar {
		display: none;
	}
</style>

<!-- fullCalendar 2.2.5-->
<link rel="stylesheet" href="assets/plugins/fullcalendar/fullcalendar.min.css">
<link rel="stylesheet" href="assets/plugins/fullcalendar/fullcalendar.print.css" media="print">

<div class="row">
	<?php if(isset($_SESSION['adm_id'])) { ?>
	<div class="col-md-4">
		<div class="panel panel-success">
			<div class="panel-heading">
				
				<a href="product.php" style="text-decoration:none;color:black;">
					Total Products
					<span class="badge pull pull-right"><?php echo $countProduct; ?></span>	
				</a>
			</div> <!--/panel-hdeaing-->
		</div> <!--/panel-->
	</div> <!--/col-md-4-->
	
	<div class="col-md-4">
		<div class="panel panel-danger">
			<div class="panel-heading">
				<a href="product.php" style="text-decoration:none;color:black;">
					Low Stock
					<span class="badge pull pull-right"><?php echo $countLowStock; ?></span>	
				</a>
			</div> <!--/panel-hdeaing-->
		</div> <!--/panel-->
	</div> <!--/col-md-4-->
	
	<?php } ?>  
		<div class="col-md-4">
			<div class="panel panel-info">
				<div class="panel-heading">
					<a href="orders.php?o=manord" style="text-decoration:none;color:black;">
						Total Orders
						<span class="badge pull pull-right"><?php echo $countOrder; ?></span>
					</a>
				</div> <!--/panel-heading-->
			</div> <!--/panel-->
		</div> <!--/col-md-4-->

	<div class="col-md-4">
		<div class="card">
		  <div class="cardHeader">
		    <h1><?php echo date('d'); ?></h1>
		  </div>

		  <div class="cardContainer">
		    <p><?php echo date('l') .' '.date('d').', '.date('Y'); ?></p>
		  </div>
		</div> 
		<br/>

		
	</div>
	
	<?php  if(isset($_SESSION['adm_id'])) { ?>
	<div class="col-md-8">
		<div class="panel panel-default">
			<div class="panel-heading"> <i class="glyphicon glyphicon-calendar"></i> Customer Orders</div>
			<div class="panel-body">
				<table class="table" id="productTable">
			  	<thead>
			  		<tr>			  			
			  			<th style="width:40%;">Name</th>
			  			<th style="width:20%;">Amount</th>
						<th style="width:20%;">Item Quantity</th>
						<th style="width:20%;">Status</th>
			  		</tr>
			  	</thead>
			  	<tbody>
					<?php 
						if($userwiseQuery->num_rows >0){			   
							while ($orderResult = $userwiseQuery->fetch_assoc()) { ?>
						<tr>
							<?php
								
								$userCheck = "SELECT orders.*, users.* FROM orders INNER JOIN users ON orders.user_id = users.u_id  WHERE orders.order_id = '".$orderResult['order_id']."'";
								$quanRes = $db->query($userCheck);
								$quanRes1 = $quanRes->fetch_assoc();
	
								$quantityCheck = "SELECT * FROM order_item WHERE order_id = '".$orderResult['order_id']."'";
								$quantityRes = $db->query($quantityCheck);
							?>
							<td><?php echo $quanRes1['username'];?></td>
							<td><?php echo $quanRes1['total_amount'];?></td>
							<td><?php echo $quantityRes->num_rows;?></td>
							
							<td><?php
									if($quanRes1['order_status'] == '1')
										$res = "Dispatch";
									else if($quanRes1['order_status'] == '2')
										$res = "In Process";
									else if($quanRes1['order_status'] == '3')
										$res = "Delivered";
									else if($quanRes1['order_status'] == '4')
										$res = "Rejected";
									echo $res;
								
								?></td>
						</tr>
					<?php } ?>
				</tbody>
				</table>
				<!--<div id="calendar"></div>-->
			</div>	
		</div>
	</div> 
	<?php  
		}
		else
			echo "<td colspan='4' style='text-align: center;'>No Orders Today!!</td>";
	}
	?>
</div> <!--/row-->

<!-- fullCalendar 2.2.5 -->
<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/plugins/fullcalendar/fullcalendar.min.js"></script>
<script type="text/javascript">
	$(function () {
			// top bar active
	$('#navDashboard').addClass('active');

      //Date for the calendar events (dummy data)
      var date = new Date();
      var d = date.getDate(),
      m = date.getMonth(),
      y = date.getFullYear();

      $('#calendar').fullCalendar({
        header: {
          left: '',
          center: 'title'
        },
        buttonText: {
          today: 'today',
          month: 'month'          
        }        
      });
    });
</script>
<?php require_once 'includes/footer.php'; ?>
