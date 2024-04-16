<?php

require_once 'connect.php'; // Make sure the path to connect.php is correct
require_once '../telegram_notification.php'; // Adjust the path as necessary

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id']) && isset($_POST['orderStatus'])) {
    $order_id = mysqli_real_escape_string($db, $_POST['order_id']);
    $orderStatus = mysqli_real_escape_string($db, $_POST['orderStatus']);
    echo "Current order status: $orderStatus\n";

    $sql = "UPDATE orders SET order_status = ? WHERE order_id = ?";
    if ($stmt = mysqli_prepare($db, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $orderStatus, $order_id);

        if (mysqli_stmt_execute($stmt)) {
            echo "Order status updated successfully.\n";

            // Fetch additional details including restaurant title
            $query = "SELECT o.user_id, r.title AS restaurant_title FROM orders o JOIN restaurant r ON o.order_belong = r.rs_id WHERE o.order_id = ?";
            if ($detailStmt = mysqli_prepare($db, $query)) {
                mysqli_stmt_bind_param($detailStmt, "i", $order_id);
                mysqli_stmt_execute($detailStmt);
                $result = mysqli_stmt_get_result($detailStmt);

                if ($row = mysqli_fetch_assoc($result)) {
                    $userId = $row['user_id'];
                    $restaurantTitle = $row['restaurant_title']; // Restaurant title

                    // Fetch chat_id and notifications_enabled status using user_id
                    $chatIdQuery = "SELECT chat_id, notifications_enabled FROM users WHERE u_id = ? LIMIT 1";
                    if ($chatIdStmt = mysqli_prepare($db, $chatIdQuery)) {
                        mysqli_stmt_bind_param($chatIdStmt, "i", $userId);
                        mysqli_stmt_execute($chatIdStmt);
                        $chatIdResult = mysqli_stmt_get_result($chatIdStmt);

                        if ($chatRow = mysqli_fetch_assoc($chatIdResult)) {
                            $chatId = $chatRow['chat_id'];
                            $notificationsEnabled = $chatRow['notifications_enabled'];

                            // Construct the message based on order status and include restaurant title and order ID
                            $message = "";
                            switch ($orderStatus) {
                                case 1:
                                    $message = $restaurantTitle . ": Seller is preparing your order (Order ID: " . $order_id . ").";
                                    break;
                                case 2:
                                    $message = $restaurantTitle . ": Seller is delivering your order (Order ID: " . $order_id . ").";
                                    break;
                                case 3:
                                    $message = $restaurantTitle . ": Order (Order ID: " . $order_id . ") completed.";
                                    break;
                            }

                            // Send notification if enabled
                            if ($notificationsEnabled == 1 && !empty($message)) {
                                sendTelegramNotification($chatId, $message);
                            } else {
                                echo "Notifications are disabled for user with chat ID: $chatId.\n";
                            }
                            mysqli_stmt_close($chatIdStmt);
                        }
                    }
                    mysqli_stmt_close($detailStmt);
                }
            }
        } else {
            echo "Error updating record: " . mysqli_error($db) . "\n";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing query: " . mysqli_error($db) . "\n";
    }
    mysqli_close($db);
} else {
    echo "Error: Invalid request.\n";
}

?>
