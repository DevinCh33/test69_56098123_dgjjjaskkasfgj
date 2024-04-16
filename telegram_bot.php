<?php
include 'connection/connect.php';

$content = file_get_contents("php://input");
$update = json_decode($content, true);

function sendMessage($chatId, $message, $keyboard = null)
{
    $token = "6861142064:AAGW10QBeruSdWOA5ZouHUMYyOp0kvQaUyY";
    $url = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chatId&text=" . urlencode($message);
    if ($keyboard) {
        $url .= "&reply_markup=" . urlencode(json_encode($keyboard));
    }
    file_get_contents($url);
}

function sendInlineKeyboard($chatId, $message, $keyboard)
{
    sendMessage($chatId, $message, $keyboard);
}

function updateNotificationsStatus($chatId, $status)
{
    global $db;
    $updateQuery = "UPDATE users SET notifications_enabled = ? WHERE chat_id = ?";
    if ($stmt = $db->prepare($updateQuery)) {
        $stmt->bind_param("ii", $status, $chatId);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function getUserDetails($chatId)
{
    global $db;
    $query = "SELECT username, fullName, email, phone FROM users WHERE chat_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $chatId);
    $stmt->execute();
    $stmt->bind_result($username, $fullName, $email, $phone);
    $stmt->fetch();
    $stmt->close();

    return [
        "Username: " . $username,
        "Full Name: " . $fullName,
        "Email: " . $email,
        "Phone: " . $phone
    ];
}

if (isset($update["message"])) {
    $chatId = $update["message"]["chat"]["id"];
    $receivedMessage = strtolower($update["message"]["text"]);

    if ($receivedMessage === "/start") {
        // Define the welcome message
        $welcomeMessage = "Welcome to LFSCBot!\nPlease click below to choose your identity:";

        // Define the inline keyboard options
        $keyboard = [
            "inline_keyboard" => [
                [
                    ["text" => "Customer", "callback_data" => "customer"]
                ],
                [
                    ["text" => "Seller", "callback_data" => "seller"]
                ]
            ]
        ];

        // Send the message with inline keyboard
        sendInlineKeyboard($chatId, $welcomeMessage, $keyboard);
    } elseif ($receivedMessage === "/help") {
        // Define the help message
        $helpMessage = "Here are some functions you can use:";

        // Define the inline keyboard options for functions
        $keyboard = [
            "inline_keyboard" => [
                [
                    ["text" => "Details", "callback_data" => "details"]
                ],
                [
                    ["text" => "Orders", "callback_data" => "orders"]
                ],
                [
                    ["text" => "Notifications", "callback_data" => "notifications"]
                ]
            ]
        ];

        // Send the message with inline keyboard for help
        sendInlineKeyboard($chatId, $helpMessage, $keyboard);
    } else {
        // Check for verification code
        $stmt = $db->prepare("SELECT userId FROM tg_verification WHERE code = ? AND expiration > NOW() LIMIT 1");
        $stmt->bind_param("s", $receivedMessage);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $userId = $row['userId'];
            $updateQuery = "UPDATE users SET chat_id = ? WHERE u_id = ?";
            if ($updateStmt = $db->prepare($updateQuery)) {
                $updateStmt->bind_param("ii", $chatId, $userId);
                $updateStmt->execute();
                if ($updateStmt->affected_rows > 0) {
                    sendMessage($chatId, "Your Telegram account has been successfully linked.\nHit /help to find out more about how to use me to my full potential.");
                } else {
                    sendMessage($chatId, "There was an error linking your account. Please try again.");
                }
            } else {
                sendMessage($chatId, "Error preparing to link your account.");
            }
        } else {
            sendMessage($chatId, "The code is invalid or has expired. Please try again or use /help for more commands.");
        }
    }
} elseif (isset($update["callback_query"])) {
    // Handle callback queries
    $callbackQuery = $update["callback_query"];
    $chatId = $callbackQuery["message"]["chat"]["id"];
    $data = $callbackQuery["data"];

    if ($data === "notifications") {
        // Define the message for notifications options
        $notificationsMessage = "Choose an option:";

        // Define the inline keyboard options for notifications
        $notificationsKeyboard = [
            "inline_keyboard" => [
                [
                    ["text" => "On", "callback_data" => "notifications_on"],
                    ["text" => "Off", "callback_data" => "notifications_off"]
                ],
                [
                    ["text" => "Back to /help", "callback_data" => "help"]
                ]
            ]
        ];

        // Send the message with inline keyboard for notifications options
        sendInlineKeyboard($chatId, $notificationsMessage, $notificationsKeyboard);
    } elseif ($data === "notifications_on") {
        if (updateNotificationsStatus($chatId, 1)) {
            $message = "Notifications have been turned ON. You will now receive updates.";
            $keyboard = [
                "inline_keyboard" => [
                    [
                        ["text" => "Back to /help", "callback_data" => "help"]
                    ]
                ]
            ];
            sendMessage($chatId, $message, $keyboard);
        } else {
            sendMessage($chatId, "Notifications have been set to ON. They will remain ON.");
        }
    } elseif ($data === "notifications_off") {
        if (updateNotificationsStatus($chatId, 0)) {
            $message = "Notifications have been turned OFF. You will no longer receive updates.";
            $keyboard = [
                "inline_keyboard" => [
                    [
                        ["text" => "Back to /help", "callback_data" => "help"]
                    ]
                ]
            ];
            sendMessage($chatId, $message, $keyboard);
        } else {
            sendMessage($chatId, "Notifications have been set to OFF. They will remain OFF.");
        }
    } elseif ($data === "help") {
        // Redirect to /help command
        $helpMessage = "Here are some functions you can use:";

        // Define the inline keyboard options for functions
        $keyboard = [
            "inline_keyboard" => [
                [
                    ["text" => "Details", "callback_data" => "details"]
                ],
                [
                    ["text" => "Orders", "callback_data" => "orders"]
                ],
                [
                    ["text" => "Notifications", "callback_data" => "notifications"]
                ]
            ]
        ];

        // Send the message with inline keyboard for help
        sendInlineKeyboard($chatId, $helpMessage, $keyboard);
    } elseif ($data === "details") {
        // Get user details
        $details = getUserDetails($chatId);

        // Prepare details message
        $detailsMessage = "Your details:\n" . implode("\n", $details);

        // Define the inline keyboard options for going back to /help
        $keyboard = [
            "inline_keyboard" => [
                [
                    ["text" => "Back to /help", "callback_data" => "help"]
                ]
            ]
        ];

        // Send user details message with inline keyboard for going back to /help
        sendInlineKeyboard($chatId, $detailsMessage, $keyboard);
    } elseif ($data === "orders") {
        // Handle orders action
        sendMessage($chatId, "Here are your orders.");
    } elseif ($data === "customer") {
        // Handle customer action
        sendMessage($chatId, "You selected Customer. Please enter the verification code.");
    } elseif ($data === "seller") {
        // Handle seller action
        sendMessage($chatId, "You selected Seller. Please enter the verification code.");
    }
}
?>
