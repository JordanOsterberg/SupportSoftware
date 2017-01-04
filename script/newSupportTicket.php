<?php
require "../sql.php";

$connection = accessConnection();

if (isset($_POST['email']) && isset($_POST['name']) && isset($_POST['issueType']) && isset($_POST['issueDesc'])) {
    $email = strtolower($_POST['email']);
    $name = $_POST['name'];
    $issueType = $_POST['issueType'];
    $issueDesc = $_POST['issueDesc'];
    $ip = $_SERVER['REMOTE_ADDR'];

    if ($email == "" || $name == "" || $issueType == "" || $issueDesc == "") {
        echo "404";
        return;
    }

    if (strlen($issueDesc) < 50) {
        echo "3";
        return;
    } else if (strlen($issueDesc) > 2000) {
        echo "5";
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "4";
        return;
    }

    $prepared = $connection->prepare("SELECT * FROM tickets WHERE (email = ? OR ip = ?) AND ticketCreatedOn < CURRENT_TIMESTAMP - INTERVAL 1 DAY");
    $prepared->bind_param("ss", $email, $ip);
    $prepared->execute();
    $result = $prepared->fetch();
    $prepared->close();

    if (!$result) {
        // Create this ticket
        $prepared = $connection->prepare(
            "INSERT INTO tickets (email, name, ip, issueType, issueDesc) VALUES (?, ?, ?, ?, ?)"
        );
        $prepared->bind_param("sssss", $email, $name, $ip, $issueType, $issueDesc);
        $prepared->execute();
        $prepared->close();
        $connection->close();

        // Maybe you want to send an email to this person, saying 'ticket created' etc etc or maybe send an internal message to employees

        echo "1";
    } else {
        // Do not, they already have a ticket within the past 1 day
        echo "2";
    }
} else {
    echo "404";
}