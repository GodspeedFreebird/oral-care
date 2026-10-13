<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OAUTHC Dental Centre</title>
    <?php
        // Automatically determine the base URL (e.g. /FYP PROJECT DEVELOPMENT/public/)
        $base_url = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        if (substr($base_url, -1) !== '/') {
            $base_url .= '/';
        }
    ?>
    <base href="<?php echo htmlspecialchars($base_url, ENT_QUOTES, 'UTF-8'); ?>">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        :root {
            --bs-primary: #0056b3;
            --bs-primary-rgb: 0, 86, 179;
        }
        .bg-primary { background-color: var(--bs-primary) !important; }
        .text-primary { color: var(--bs-primary) !important; }
        .btn-primary { background-color: var(--bs-primary); border-color: var(--bs-primary); }
        .btn-primary:hover { background-color: #004494; border-color: #004494; }
    </style>
</head>
<body class="bg-white">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-0">
        <div class="container">
            <a class="navbar-brand fw-bold" href="./">OAUTHC Dental Centre</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <span class="nav-link text-light me-3">Welcome, <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?> (<?= htmlspecialchars($_SESSION['role'] ?? '') ?>)</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= htmlspecialchars($_SESSION['role']) ?>/dashboard">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">

