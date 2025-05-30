<?php
require_once __DIR__ . '/../views/client/partials/header.php';

$action = $_GET['action'] ?? '/';

match ($action) {
    '/'         => (new HomeController)->index(),
    'test-show' => (new TestController)->show(),
};
require_once __DIR__ . '/../views/client/partials/footer.php';