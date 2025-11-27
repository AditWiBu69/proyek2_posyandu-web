<?php
session_start();
session_destroy();
header("Location: login.php"); // Atau index.php
exit();
?>