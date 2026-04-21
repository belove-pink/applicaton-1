<?php
session_start();
require_once 'Database.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

// fetch() untuk ambil satu user
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - MyApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Nunito:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --brown-dark: #3E2010; --brown-mid: #7B4A2D; --brown-light: #C49A6C;
            --pink-main: #E8829A; --pink-light: #F7C5D0; --pink-pale: #FDF0F3; --cream: #FDF6EE;
        }
        body { background: var(--cream); font-family: 'Nunito', sans-serif; min-height: 100vh; }
        .navbar-custom { background: linear-gradient(135deg, var(--brown-dark), var(--brown-mid)); padding: 14px 0; box-shadow: 0 4px 15px rgba(62,32,16,.2); }
        .navbar-brand-text { font-family: 'Playfair Display', serif; color: var(--pink-light) !important; font-size: 1.4rem; }
        .nav-link-custom { color: var(--brown-light) !important; font-weight: 600; font-size: .9rem; border-radius: 8px; padding: 6px 14px !important; transition: all .2s; }
        .nav-link-custom:hover, .nav-link-custom.active { background: rgba(232,130,154,.2); color: var(--pink-light) !important; }
        .btn-logout { background: rgba(232,130,154,.2); color: var(--pink-light) !important; border: 1px solid rgba(232,130,154,.4); border-radius: 8px; padding: 6px 14px; font-weight: 600; font-size: .9rem; text-decoration: none; transition: all .2s; }
        .btn-logout:hover { background: var(--pink-main); color: #fff !important; }
        .profile-header { background: linear-gradient(135deg, var(--brown-dark), var(--brown-mid)); padding: 50px 0 30px; text-align: center; }
        .avatar-lg { width: 90px; height: 90px; background: linear-gradient(135deg, var(--pink-main), #d4607a); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 2.5rem; margin: 0 auto 16px; border: 4px solid rgba(255,255,255,.2); }
        .profile-name { font-family: 'Playfair Display', serif; color: var(--pink-light); font-size: 1.8rem; margin: 0; }
        .profile-email { color: var(--brown-light); font-size: .9rem; margin: 4px 0 0; }
        .info-card { background: #fff; border-radius: 20px; box-shadow: 0 4px 20px rgba(62,32,16,.1); padding: 28px; margin-top: -20px; }
        .info-label { font-size: .8rem; font-weight: 700; color: var(--brown-light); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; }
        .info-value { font-size: 1rem; color: var(--brown-dark); font-weight: 600; }
        .btn-edit { background: linear-gradient(135deg, var(--pink-main), #d4607a); color: #fff; border: none; border-radius: 12px; padding: 10px 28px; font-weight: 700; text-decoration: none; transition: transform .2s, box-shadow .2s; display: inline-block; }
        .btn-edit:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(232,130,154,.4); color: #fff; }
        .divider { border-color: #f0e6e0; }
    </style>
</head>
<body>
<nav class="navbar navbar-custom">
  <div class="container d-flex justify-content-between align-items-center">
    <a class="navbar-brand-text text-decoration-none" href="#"><i class="bi bi-flower1 me-2" style="color:var(--pink-main)"></i>MyApp</a>
    <div class="d-flex gap-2 align-items-center">
      <a href="profil.php" class="nav-link-custom active"><i class="bi bi-person me-1"></i>Profil</a>
      <a href="users.php" class="nav-link-custom"><i class="bi bi-people me-1"></i>Users</a>
      <a href="logout.php" class="btn-logout"><i class="bi bi-box-arrow-right me-1"></i>Logout</a>
    </div>
  </div>
</nav>

<div class="profile-header">
  <div class="avatar-lg"><i class="bi bi-person-fill"></i></div>
  <h2 class="profile-name"><?= htmlspecialchars($user['name']) ?></h2>
  <p class="profile-email"><i class="bi bi-envelope me-1"></i><?= htmlspecialchars($user['email']) ?></p>
</div>

<div class="container" style="max-width: 560px; padding: 0 16px 50px;">
  <div class="info-card">
    <div class="row g-4">
      <div class="col-12">
        <div class="info-label"><i class="bi bi-person me-1"></i>Nama Lengkap</div>
        <div class="info-value"><?= htmlspecialchars($user['name']) ?></div>
      </div>
      <hr class="divider my-0">
      <div class="col-12">
        <div class="info-label"><i class="bi bi-envelope me-1"></i>Email</div>
        <div class="info-value"><?= htmlspecialchars($user['email']) ?></div>
      </div>
      <hr class="divider my-0">
      <div class="col-12">
        <div class="info-label"><i class="bi bi-calendar3 me-1"></i>Bergabung</div>
        <div class="info-value"><?= date('d F Y', strtotime($user['created_at'])) ?></div>
      </div>
      <hr class="divider my-0">
      <div class="col-12">
        <div class="info-label"><i class="bi bi-journal-text me-1"></i>Biografi</div>
        <div class="info-value" style="font-weight:400; color:#555; line-height:1.6;">
          <?= !empty($user['biography']) ? htmlspecialchars($user['biography']) : '<span class="text-muted fst-italic">Belum ada biografi.</span>' ?>
        </div>
      </div>
    </div>
    <div class="text-center mt-4">
      <a href="edit_profile.php" class="btn-edit"><i class="bi bi-pencil-square me-2"></i>Edit Profil</a>
    </div>
  </div>
</div>
</body>
</html>
