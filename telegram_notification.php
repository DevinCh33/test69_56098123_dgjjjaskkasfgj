<?php

include 'connection/connect.php'; 

function sendTelegramNotification($chatId, $message)
{
    $token = "6861142064:AAGW10QBeruSdWOA5ZouHUMYyOp0kvQaUyY"; 
    $url = "https://api.telegram.org/bot$token/sendMessage";

    $data = array(
        'chat_id' => $chatId,
        'text' => $message,
    );

    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
        ),
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        // Handle error accordingly
        echo "There was an error sending the message.";
    } else {
        echo "Message sent successfully to $chatId.";
    }
}
?>
