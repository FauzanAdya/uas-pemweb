<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #3B4A1F; min-height: 100vh; display:flex; align-items:center; justify-content:center; }
        .card { border-radius: 16px; border: none; }
        .btn-login { background: #3B4A1F; color: #fff; border: none; }
        .btn-login:hover { background: #2a3516; color: #fff; }
    </style>
</head>
<body>
<div class="card shadow p-4" style="width:380px">
    <div class="text-center mb-4">
        <div style="font-size:2.5rem">🌸</div>
        <h4 class="fw-bold"><?= APP_NAME ?></h4>
        <small class="text-muted">Login Admin</small>
    </div>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <form method="POST" action="index.php?page=login&action=proses">
        <div class="mb-3">
            <label class="form-label fw-semibold">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
        </div>
        <button type="submit" class="btn btn-login w-100 fw-bold">Login</button>
    </form>
</div>
</body>
</html>
