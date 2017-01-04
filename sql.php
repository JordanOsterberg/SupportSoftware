<?php
/**
 * @return mysqli
 */
function accessConnection() {
    $connection = mysqli_connect("138.68.55.139", "admin", "examplepassword", "support");
    return $connection;
}