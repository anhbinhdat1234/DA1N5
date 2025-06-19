<?php require_once PATH_VIEW_ADMIN . 'layout/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php require_once __DIR__ . '/layout/sidebar.php'; ?>
        

        <!-- Main content -->
        <main class="col-md-10 bg-light p-5">
            <h1 class="mb-4 text-primary text-center">
                <?= $title ?? 'Quản trị hệ thống' ?>
            </h1>

            <?php if (isset($view)) : ?>
                <?php require_once PATH_VIEW_ADMIN . $view . '.php'; ?>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php require_once PATH_VIEW_ADMIN . 'layout/footer.php'; ?>