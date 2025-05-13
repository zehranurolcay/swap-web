<?php
    session_start();

    $_SESSION['edit-item-id'] = $_GET['item_id'];

    if($_GET['temp'] == "true")
    {
        $_SESSION['edit-item'] = false;
        header("Location: /my-account#tab-my-item");
        exit;
    }
    else
    {
        $_SESSION['edit-item'] = true;
        header("Location: /my-account#tab-my-item");
        exit;
    }
    
?>