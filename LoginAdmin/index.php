<?php

session_start();

// Hapus session login lama
session_destroy();

// Arahkan ke halaman login
header('Location: ../index.php?page=login');
exit;