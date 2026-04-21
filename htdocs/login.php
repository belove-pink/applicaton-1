<?php
session_start();
require_once 'Database.php';
if (isset($_SESSION['user_id'])) { header("Location: profil.php"); exit(); }

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Email dan password wajib diisi!";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: profil.php");
            exit();
        } else {
            $error = "Email atau password salah. Silakan coba lagi!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MyApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Nunito:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --brown-dark: #3E2010; --brown-mid: #7B4A2D; --brown-light: #C49A6C;
            --pink-main: #E8829A; --pink-light: #F7C5D0; --pink-pale: #FDF0F3; --cream: #FDF6EE;
        }
        body { background: linear-gradient(135deg, var(--cream), var(--pink-pale)); min-height: 100vh; font-family: 'Nunito', sans-serif; display: flex; align-items: center; justify-content: center; }
        .card-wrap { background: #fff; border-radius: 24px; box-shadow: 0 10px 40px rgba(62,32,16,.13); overflow: hidden; max-width: 440px; width: 100%; }
        .card-top { background: linear-gradient(135deg, var(--brown-dark), var(--brown-mid)); padding: 36px 36px 28px; text-align: center; }
        .card-top .icon { font-size: 2.6rem; color: var(--pink-main); margin-bottom: 10px; }
        .card-top h2 { font-family: 'Playfair Display', serif; color: var(--pink-light); font-size: 2rem; margin: 0; }
        .card-top p { color: var(--brown-light); font-size: .88rem; margin: 6px 0 0; }
        .card-body-inner { padding: 36px; }
        .form-label { font-weight: 600; color: var(--brown-dark); font-size: .9rem; }
        .form-control { border-radius: 12px; border: 1.5px solid #e0d0c5; padding: 10px 14px; font-family: 'Nunito', sans-serif; transition: border-color .25s; }
        .form-control:focus { border-color: var(--pink-main); box-shadow: 0 0 0 3px rgba(232,130,154,.18); }
        .input-group-text { background: var(--pink-pale); border: 1.5px solid #e0d0c5; border-right: none; color: var(--brown-mid); }
        .input-group .form-control { border-radius: 0 12px 12px 0 !important; }
        .input-group .input-group-text { border-radius: 12px 0 0 12px; }
        .btn-main { background: linear-gradient(135deg, var(--pink-main), #d4607a); color: #fff; border: none; border-radius: 12px; padding: 12px; font-weight: 700; font-size: 1rem; width: 100%; transition: transform .2s, box-shadow .2s; }
        .btn-main:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(232,130,154,.4); color: #fff; }
        .bottom-link { text-align: center; font-size: .9rem; color: var(--brown-mid); margin-top: 16px; }
        .bottom-link a { color: var(--pink-main); font-weight: 700; text-decoration: none; }
    </style>
</head>
<body>
<div class="container">
  <div class="d-flex justify-content-center">
    <div class="card-wrap">
      <div class="card-top">
        <div class="icon"><i class="bi bi-box-arrow-in-right"></i></div>
        <h2>Selamat Datang</h2>
        <p>Login untuk melanjutkan</p>
      </div>
      <div class="card-body-inner">
        <?php if ($error): ?>
          <div class="alert alert-danger d-flex align-items-center gap-2 rounded-3">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span><?= htmlspecialchars($error) ?></span>
          </div>
        <?php endif; ?>
        <form method="POST">
          <div class="mb-3">
            <label class="form-label">Email</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-envelope"></i></span>
              <input type="email" name="email" class="form-control" placeholder="email@contoh.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Password</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-lock"></i></span>
              <input type="password" name="password" class="form-control" placeholder="Password kamu">
            </div>
          </div>
          <button type="submit" class="btn-main"><i class="bi bi-box-arrow-in-right me-2"></i>Login</button>
        </form>
        <div class="bottom-link">Belum punya akun? <a href="register.php">Daftar di sini</a></div>
      </div>
    </div>
  </div>
</div>
</body>
</html>