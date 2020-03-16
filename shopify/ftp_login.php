<?php

$ftp_server = "moogli.in";
$ftp_user = "moogli";
$ftp_pass = "Augurs@9848";

// set up a connection or die
$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server"); 

// try to login
if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
    echo "Connected as $ftp_user@$ftp_server\n";
} else {
    echo "Couldn't connect as $ftp_user\n";
}
ftp_pasv($conn_id, true);
// close the connection
ftp_close($conn_id);  
?>