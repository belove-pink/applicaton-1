<?php
session_start();
require_once 'Database.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

// fetch() untuk ambil data user saat ini
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name      = trim($_POST['name'] ?? '');
    $biography = trim($_POST['biography'] ?? '');
    $password  = $_POST['password'] ?? '';

    if (empty($name)) $errors[] = "Nama wajib diisi.";

    if (empty($errors)) {
        if (!empty($password)) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET name=?, biography=?, password=? WHERE id=?");
            $stmt->execute([$name, $biography, $hashed, $_SESSION['user_id']]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET name=?, biography=? WHERE id=?");
            $stmt->execute([$name, $biography, $_SESSION['user_id']]);
        }
        $_SESSION['user_name'] = $name;
        $success = "Profil berhasil diperbarui!";

        // Refresh data user
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - MyApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Nunito:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --brown-dark: #3E2010; --brown-mid: #7B4A2D; --brown-light: #C49A6C;
            --pink-main: #E8829A; --pink-light: #F7C5D0; --pink-pale: #FDF0F3; --cream: #FDF6EE;
        }
        body { background: linear-gradient(135deg, var(--cream), var(--pink-pale)); min-height: 100vh; font-family: 'Nunito', sans-serif; display: flex; align-items: center; justify-content: center; padding: 30px 0; }
        .card-wrap { background: #fff; border-radius: 24px; box-shadow: 0 10px 40px rgba(62,32,16,.13); overflow: hidden; max-width: 480px; width: 100%; }
        .card-top { background: linear-gradient(135deg, var(--brown-dark), var(--brown-mid)); padding: 32px 36px 24px; text-align: center; }
        .card-top .icon { font-size: 2.4rem; color: var(--pink-main); margin-bottom: 8px; }
        .card-top h2 { font-family: 'Playfair Display', serif; color: var(--pink-light); font-size: 1.9rem; margin: 0; }
        .card-top p { color: var(--brown-light); font-size: .88rem; margin: 6px 0 0; }
        .card-body-inner { padding: 32px 36px; }
        .form-label { font-weight: 600; color: var(--brown-dark); font-size: .9rem; }
        .form-control { border-radius: 12px; border: 1.5px solid #e0d0c5; padding: 10px 14px; font-family: 'Nunito', sans-serif; transition: border-color .25s; }
        .form-control:focus { border-color: var(--pink-main); box-shadow: 0 0 0 3px rgba(232,130,154,.18); }
        .input-group-text { background: var(--pink-pale); border: 1.5px solid #e0d0c5; border-right: none; color: var(--brown-mid); }
        .input-group .form-control { border-radius: 0 12px 12px 0 !important; }
        .input-group .input-group-text { border-radius: 12px 0 0 12px; }
        .btn-main { background: linear-gradient(135deg, var(--pink-main), #d4607a); color: #fff; border: none; border-radius: 12px; padding: 12px; font-weight: 700; font-size: 1rem; width: 100%; transition: transform .2s, box-shadow .2s; }
        .btn-main:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(232,130,154,.4); color: #fff; }
        .btn-back { display: block; text-align: center; font-size: .9rem; color: var(--brown-mid); margin-top: 14px; text-decoration: none; font-weight: 600; }
        .btn-back:hover { color: var(--pink-main); }
        .email-info { background: var(--pink-pale); border-radius: 10px; padding: 10px 14px; font-size: .88rem; color: var(--brown-mid); }
    </style>
</head>
<body>
<div class="container">
  <div class="d-flex justify-content-center">
    <div class="card-wrap">
      <div class="card-top">
        <div class="icon"><i class="bi bi-pencil-square"></i></div>
        <h2>Edit Profil</h2>
        <p>Perbarui informasi akunmu</p>
      </div>
      <div class="card-body-inner">
        <?php if (!empty($errors)): ?>
          <div class="alert alert-danger rounded-3">
            <ul class="mb-0 ps-3"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
          </div>
        <?php endif; ?>
        <?php if ($success): ?>
          <div class="alert alert-success rounded-3"><i class="bi bi-check-circle me-2"></i><?= $success ?></div>
        <?php endif; ?>
        <form method="POST">
          <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
              <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <div class="email-info"><i class="bi bi-envelope me-2"></i><?= htmlspecialchars($user['email']) ?> <span class="text-muted">(tidak dapat diubah)</span></div>
          </div>
          <div class="mb-3">
            <label class="form-label">Password Baru <span class="text-muted fw-normal">(kosongkan jika tidak ingin ubah)</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-lock"></i></span>
              <input type="password" name="password" class="form-control" placeholder="Password baru...">
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Biografi <span class="text-muted fw-normal">(opsional)</span></label>
            <textarea name="biography" class="form-control" rows="3"><?= htmlspecialchars($user['biography'] ?? '') ?></textarea>
          </div>
          <button type="submit" class="btn-main"><i class="bi bi-save me-2"></i>Simpan Perubahan</button>
        </form>
        <a href="profil.php" class="btn-back"><i class="bi bi-arrow-left me-1"></i>Kembali ke Profil</a>
      </div>
    </div>
  </div>
</div>
</body>
</html>
