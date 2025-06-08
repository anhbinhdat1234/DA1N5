<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!empty($_SESSION['flash_error'])) {
    echo '<div class="container mt-3"><div class="alert alert-danger">'
         . htmlspecialchars($_SESSION['flash_error']) .
         '</div></div>';
    unset($_SESSION['flash_error']);
}

if (!empty($_SESSION['flash_success'])) {
    echo '<div class="container mt-3"><div class="alert alert-success">'
         . htmlspecialchars($_SESSION['flash_success']) .
         '</div></div>';
    unset($_SESSION['flash_success']);
}
