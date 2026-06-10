<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .sidebar { min-height: 100vh; background: #3B4A1F; }
        .sidebar a { color: #cdd8a3; text-decoration: none; }
        .sidebar a:hover, .sidebar a.active { color: #fff; background: rgba(255,255,255,0.1); border-radius: 8px; }
        .sidebar .brand { color: #fff; font-size: 1.2rem; font-weight: 700; }
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); }
        .badge-pending    { background: #ffc107; color: #000; }
        .badge-diproses   { background: #0dcaf0; color: #000; }
        .badge-selesai    { background: #198754; color: #fff; }
        .badge-dibatalkan { background: #dc3545; color: #fff; }
    </style>
</head>
<body>
<div class="d-flex">
