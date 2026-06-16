<?php
function isLoggedIn() {
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}

function cekLogin() {
    if (!isLoggedIn()) {
        header('Location: index.php?page=login');
        exit;
    }
}

function getNamaAdmin() {
    return $_SESSION['admin_nama'] ?? 'Admin';
}
