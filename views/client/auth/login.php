<body class="">
    <div class="container d-flex justify-content-center align-items-center" style="margin-top: 100px;">
        <div class="card shadow p-4" style="width: 500%; max-width: 400px;">
            <h2 class="text-center mb-4">Login</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST" action="<?= BASE_URL ?>?action=login">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        placeholder="Email"
                        required
                    >
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        placeholder="Password"
                        required
                    >
                </div>
                <div class="mt-3 text-center">
                    <span>You do not have an account?</span>
                    <a href="<?= BASE_URL ?>?action=register_form" class="text-primary">Register</a>
                </div>
                <br>
                <button type="submit" class="btn btn-success w-100">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
