<?php
require_once __DIR__ . '/../views/client/partials/header.php';

$action = $_GET['action'] ?? '/';

match ($action) {
    '/'         => (new HomeController)->index(),
    'login'     => require __DIR__ . '/../views/client/auth/login.php',
    'register'  => require __DIR__ . '/../views/client/auth/register.php',
    'test-show' => (new TestController)->show(),
};
require_once __DIR__ . '/../views/client/partials/footer.php';