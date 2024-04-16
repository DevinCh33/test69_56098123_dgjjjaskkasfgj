<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_POST['cart']))
    {
        $valid = True;

        /* foreach ($_POST['cart'] as $item)
        {
            if ((float)$item['price'] != (float)$_SESSION['prices'][$item['price_id']])
            {
                $valid = False;
                break;
            }
        }*/

        if ($valid) // If prices were not altered
        {
            $_SESSION['cart'] = $_POST['cart'];
        }
    }

    else
    {
        // In the case of empty cart
        unset($_SESSION['cart']);
    }
}

elseif(isset($_SESSION['cart'])) 
{
    $cart = $_SESSION['cart'];

    // Return cart as JSON response
    header('Content-Type: application/json');

    // Make sure numbers remain numbers in JSON
    echo json_encode($cart, JSON_NUMERIC_CHECK);
}
