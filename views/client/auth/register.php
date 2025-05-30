
    <div class="container d-flex justify-content-center align-items-center vh-50">
        <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
            <h2 class="text-center mb-4">Register</h2>
            <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">UserName</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="UserName" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>
                <div class="mt-3 text-center">
                    <span>Already have an account?</span>
                    <a href="index.php?act=login" class="text-primary">Login</a>
                </div>
                <br>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
        </div>
    </div>
