<?php
session_start();
include("../seller/connect.php");

if (empty($_SESSION["user_id"])) {
    header("refresh:0;url=login.php");
    exit();
}

if (isset($_POST['markRead']) && $_POST['markRead'] == 'true') {
    $query = "UPDATE orders SET is_seen = 1 WHERE is_seen = 0 AND user_id = ?";
    if ($stmt = mysqli_prepare($db, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "Notifications marked as read successfully.";
    } else {
        echo "Error marking notifications as read: " . mysqli_error($db);
    }
    exit;
}

$query = "SELECT o.order_id, o.order_status, r.title AS restaurant_title, o.last_updated
          FROM orders o
          JOIN restaurant r ON o.order_belong = r.rs_id
          WHERE o.order_status IN (1, 2, 3)
          AND o.is_seen = 0
          AND o.user_id = ?";

if ($stmt = mysqli_prepare($db, $query)) {
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $notifications = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $formattedDate = date("F j, Y, g:i a", strtotime($row['last_updated'])); 
        $message = htmlspecialchars($row['restaurant_title'] . ": Order (Order ID: " . $row['order_id'] . ")");
        $notifications[] = [
            'message' => $message,
            'time' => "<div class='notification-last-updated'>Last updated: " . $formattedDate . "</div>"
        ];

    }
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="landing/style.css" />
    <link rel="icon" type="image/png" sizes="16x16" href="./../landing/logo.png" />
    <title>Your Website Title</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

    <header>
        <img src="landing/logo.png" alt="logo" />

        <ul>
            <li>
                <div class="notification-container">
                    <img src="landing/notification.png" alt="Notifications" class="notification-icon <?php echo count($notifications) > 0 ? 'has-notifications' : ''; ?>" />
                    <div class="notification-dropdown">
                        <div class="notification-top-bar">
                            <span class="notification-title">Notifications (<?php echo count($notifications); ?>)</span>
                            <span id="markAllAsRead" class="mark-all-as-read">Mark all as read</span>
                        </div>
                        <div class="notification-content">
                            <?php if (count($notifications) > 0): ?>
                            <?php foreach ($notifications as $notification): ?>
                            <div class="notification-item">
                                <?php echo $notification['message']; ?>
                                <div class="notification-time"><?php echo $notification['time']; ?></div>
                            </div>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <div class="no-notifications">No new notifications</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </li>
            <li <?php echo ($currentPage == 'home') ? 'class="active"' : ''; ?>><a href="index.php">Home</a></li>
            <li <?php echo ($currentPage == 'market') ? 'class="active"' : ''; ?>><a href="market.php">Market</a></li>
            <li <?php echo ($currentPage == 'merchants') ? 'class="active"' : ''; ?>><a href="merchants.php">Merchants</a></li>
            <li <?php echo ($currentPage == 'products') ? 'class="active"' : ''; ?>><a href="products.php">Products / Cart</a></li>

            <li>
                <div class="my_account_dropdown">
                    <button class="dropbtn">My Account</button>
                    <div class="dropdown-content">
                        <a href="your_account.php">My Account</a>
                        <a href="bindtg.php">Bind Telegram</a>
                        <a href="your_orders.php">Orders</a>
                        <?php if (isset($_SESSION['adm_id'])): ?>
                            <a href="seller/dashboard.php">Dashboard</a>
                        <?php endif; ?>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            </li>

        </ul>
    </header>

    <script>
        $(document).ready(function () {
            $('#markAllAsRead').click(function () {
                $.ajax({
                    type: "POST",
                    url: "includes/header.php",
                    data: { markRead: 'true' },
                    success: function (response) {
                        console.log(response);
                        $('.notification-icon').removeClass('has-notifications');
                        $('.notification-content').html('<div class="no-notifications">No new notifications</div>');
                    },
                    error: function (xhr, status, error) {
                        console.error("Error marking notifications as read:", status, error);
                    }
                });
            });
        });
    </script>

</body>
</html>
