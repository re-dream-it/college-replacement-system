<?
session_start();
if (!isset($_SESSION['user_id'])) {
    echo("Error: unauthorized");
    exit;
}
?>