<?php
session_start();
require_once 'Database.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

// fetchAll() untuk ambil semua user
$stmt = $pdo->query("SELECT id, name, email, biography, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar User - MyApp</title>
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
        .page-header { background: linear-gradient(135deg, var(--brown-dark), var(--brown-mid)); padding: 40px 0; text-align: center; }
        .page-header h2 { font-family: 'Playfair Display', serif; color: var(--pink-light); margin: 0; font-size: 1.8rem; }
        .page-header p { color: var(--brown-light); margin: 6px 0 0; font-size: .9rem; }
        .badge-count { background: var(--pink-main); color: #fff; border-radius: 20px; padding: 4px 14px; font-size: .85rem; font-weight: 700; }
        .user-card { background: #fff; border-radius: 16px; padding: 20px; box-shadow: 0 4px 16px rgba(62,32,16,.08); border: 1.5px solid transparent; transition: transform .2s, border-color .2s, box-shadow .2s; }
        .user-card:hover { transform: translateY(-3px); border-color: var(--pink-light); box-shadow: 0 8px 24px rgba(232,130,154,.18); }
        .user-card.is-me { border-color: var(--pink-main); background: var(--pink-pale); }
        .avatar-sm { width: 52px; height: 52px; background: linear-gradient(135deg, var(--pink-main), #d4607a); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.3rem; flex-shrink: 0; }
        .avatar-sm.other { background: linear-gradient(135deg, var(--brown-mid), var(--brown-dark)); }
        .user-name { font-weight: 700; color: var(--brown-dark); font-size: 1rem; margin: 0; }
        .user-email { color: var(--brown-light); font-size: .85rem; margin: 2px 0 0; }
        .user-bio { color: #777; font-size: .85rem; margin-top: 10px; line-height: 1.5; }
        .badge-me { background: var(--pink-main); color: #fff; border-radius: 20px; padding: 2px 10px; font-size: .75rem; font-weight: 700; }
        .joined-text { font-size: .78rem; color: var(--brown-light); }
    </style>
</head>
<body>
<nav class="navbar navbar-custom">
  <div class="container d-flex justify-content-between align-items-center">
    <a class="navbar-brand-text text-decoration-none" href="#"><i class="bi bi-flower1 me-2" style="color:var(--pink-main)"></i>MyApp</a>
    <div class="d-flex gap-2 align-items-center">
      <a href="profil.php" class="nav-link-custom"><i class="bi bi-person me-1"></i>Profil</a>
      <a href="users.php" class="nav-link-custom active"><i class="bi bi-people me-1"></i>Users</a>
      <a href="logout.php" class="btn-logout"><i class="bi bi-box-arrow-right me-1"></i>Logout</a>
    </div>
  </div>
</nav>
<div class="page-header">
  <h2><i class="bi bi-people-fill me-2"></i>Daftar Semua User</h2>
  <p>Total <span class="badge-count"><?= count($users) ?> user</span> terdaftar</p>
</div>
<div class="container" style="max-width:720px; padding: 30px 16px 50px;">
  <div class="d-flex flex-column gap-3">
    <?php foreach ($users as $u): ?>
      <?php $isMe = ($u['id'] == $_SESSION['user_id']); ?>
      <div class="user-card <?= $isMe ? 'is-me' : '' ?>">
        <div class="d-flex gap-3 align-items-center">
          <div class="avatar-sm <?= $isMe ? '' : 'other' ?>"><i class="bi bi-person-fill"></i></div>
          <div class="flex-grow-1">
            <div class="d-flex align-items-center gap-2 flex-wrap">
              <p class="user-name"><?= htmlspecialchars($u['name']) ?></p>
              <?php if ($isMe): ?><span class="badge-me">Kamu</span><?php endif; ?>
            </div>
            <div class="user-email"><i class="bi bi-envelope me-1"></i><?= htmlspecialchars($u['email']) ?></div>
          </div>
          <div class="joined-text text-end"><i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($u['created_at'])) ?></div>
        </div>
        <?php if (!empty($u['biography'])): ?>
          <div class="user-bio"><i class="bi bi-quote me-1" style="color:var(--pink-main)"></i><?= htmlspecialchars($u['biography']) ?></div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
    <?php if (empty($users)): ?>
      <div class="text-center text-muted py-5">
        <i class="bi bi-people" style="font-size:3rem; color:var(--brown-light)"></i>
        <p class="mt-3">Belum ada user terdaftar.</p>
      </div>
    <?php endif; ?>
  </div>
</div>
</body>
</html>