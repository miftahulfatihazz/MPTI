<?php
// File: admin/logout.php

session_start();

// Hapus semua variabel session
session_unset();

// Hancurkan session
session_destroy();

// Arahkan kembali ke halaman login
header("Location: login.php");
exit();
?>