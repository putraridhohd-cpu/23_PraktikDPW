<?php
$page_title = "Login Petugas";
include __DIR__ . '/includes/header.php';
?>

<section>
    <h2>Login Petugas</h2>
    <form method="post" action="proses_login.php">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Masuk</button>
        </div>
    </form>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>