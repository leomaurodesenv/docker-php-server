<h4>Attempting MySQL connection from php...</h4>
<p>

<?php
$host_sql = 'db';
$user_sql = 'root';
$pass_sql = 'password';
$db_sql = 'app_development';

$conn = new mysqli($host_sql,$user_sql,$pass_sql,$db_sql);
// Check connection
if($conn->connect_error) {
    echo 'Failed to connect to MySQL: '.$conn->connect_error;
}
else {
    echo 'Success: '.$conn->host_info;
}
?>

</p>
