<?php
session_start();
session_unset(); // Menghapus semua session
session_destroy(); // Mengakhiri session

header("Location: ../../login.php");
exit;
?>
