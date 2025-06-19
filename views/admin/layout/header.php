<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Quản trị' ?> | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/admin/css/admin.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= BASE_URL_ADMIN ?>">👑 Admin Panel</a>
        <div class="d-flex">
            <span class="navbar-text me-3">👤 <?= $_SESSION['user']['name'] ?? 'Admin' ?></span>
            <a href="<?= BASE_URL ?>?action=logout" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
