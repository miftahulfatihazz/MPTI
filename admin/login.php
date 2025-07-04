<?php
// File: admin/login.php (Versi Desain Baru)
session_start();

// Jika admin sudah login, langsung arahkan ke dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Sinar Bahari</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fdfaf6; /* Warna latar belakang krem/putih lembut */
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .login-header {
            width: 100%;
            max-width: 450px;
            text-align: left;
            margin-bottom: 1rem;
        }
        .login-header a {
            text-decoration: none;
            color: #555;
            font-weight: 500;
        }
        .login-header a:hover {
            color: #000;
        }
        .login-card {
            width: 100%;
            max-width: 450px;
            border-radius: 0.75rem;
            border: 1px solid #eee;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.05);
            background-color: white;
        }
        .login-card .card-body {
            padding: 2.5rem;
        }
        .login-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background-color: #fd7e14;
            color: white;
            border-radius: 50%;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .form-control {
            padding: 0.75rem 1rem;
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .btn-login {
            background-color: #fd7e14;
            color: white;
            padding: 0.75rem;
            font-weight: 600;
            border: none;
        }
        .btn-login:hover {
            background-color: #e86a04;
            color: white;
        }
        .login-footer {
            width: 100%;
            max-width: 450px;
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.8rem;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Tombol Kembali ke Website -->
        <div class="login-header">
            <a href="../index.php">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Website
            </a>
        </div>

        <!-- Kartu Form Login -->
        <div class="login-card">
            <div class="card-body">
                <div class="text-center">
                    <div class="login-logo">B</div>
                    <h4 class="fw-bold">Admin Panel</h4>
                    <p class="text-muted">Masuk ke dashboard administrasi Bakso Premium</p>
                </div>
                
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger mt-3" role="alert">
                        Username atau Password salah!
                    </div>
                <?php endif; ?>

                <form action="proses_login.php" method="POST" class="mt-4">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                    </div>
                    <div class="d-grid mt-4">
                        <button class="btn btn-login" type="submit">Masuk</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer Keamanan -->
        <div class="login-footer">
            Akses admin dilindungi dengan keamanan tinggi. Jangan bagikan kredensial login Anda kepada siapa pun.
        </div>
    </div>
</body>
</html>