<?php
/**
 * @return mysqli
 */
function accessConnection() {
    $connection = mysqli_connect("xxx.xx.xx.xxx", "admin", "examplepassword", "support");
    return $connection;
}
