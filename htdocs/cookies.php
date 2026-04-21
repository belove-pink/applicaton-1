<?php
session_start();

if (isset($_POST['set_tema'])) {
    $tema = $_POST['tema'] === 'gelap' ? 'gelap' : 'terang';
    setcookie("tema_pilihan", $tema, time() + (7 * 24 * 3600), "/");
    header("Location: index.php"); exit;
}
if (isset($_POST['tambah_item'])) {
    $item = htmlspecialchars(trim($_POST['nama_item']));
    if (!empty($item)) {
        if (!isset($_SESSION['keranjang'])) $_SESSION['keranjang'] = [];
        $_SESSION['keranjang'][] = $item;
    }
    header("Location: index.php"); exit;
}
if (isset($_POST['hapus_item'])) {
    $i = (int)$_POST['index_item'];
    if (isset($_SESSION['keranjang'][$i])) array_splice($_SESSION['keranjang'], $i, 1);
    header("Location: index.php"); exit;
}
if (isset($_POST['kosongkan'])) {
    unset($_SESSION['keranjang']);
    header("Location: index.php"); exit;
}

$tema      = $_COOKIE['tema_pilihan'] ?? 'terang';
$keranjang = $_SESSION['keranjang'] ?? [];
$is_dark   = $tema === 'gelap';

$bg_page  = $is_dark ? '#2d1b2e' : '#fff0f6';
$bg_card  = $is_dark ? '#3d1f3e' : '#ffffff';
$bg_soft  = $is_dark ? '#4b1528' : '#fbeaf0';
$border   = $is_dark ? '#72243e' : '#f4c0d1';
$txt_main = $is_dark ? '#fce4f0' : '#72243e';
$txt_brand= $is_dark ? '#f4c0d1' : '#993556';
$txt_muted= $is_dark ? '#ed93b1' : '#d4537e';
$aksen    = $is_dark ? '#ed93b1' : '#d4537e';
$btn_bg   = $is_dark ? '#993556' : '#d4537e';
$btn_hover= $is_dark ? '#72243e' : '#993556';
$tema_next    = $is_dark ? 'terang' : 'gelap';
$tema_label   = $is_dark ? '🌙 Tema Gelap' : '☀️ Tema Terang';
$thumb_emoji  = $is_dark ? '🌙' : '☀️';
$thumb_style  = $is_dark ? 'transform:translateX(24px)' : '';
$lbl_t_on     = $is_dark ? '' : 'on';
$lbl_g_on     = $is_dark ? 'on' : '';
$emojis       = ['🍓','🧁','🍬','🎂','🧁','🍫','🍩','🍼'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Toko Mini ~</title>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Nunito', sans-serif;
      background: <?= $bg_page ?>;
      color: <?= $txt_main ?>;
      min-height: 100vh;
      padding: 1.5rem 1rem 3rem;
    }
    .wrap { max-width: 560px; margin: 0 auto; }

    .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.4rem; }
    .brand-name { font-size: 1.2rem; font-weight: 800; color: <?= $txt_brand ?>; }
    .brand-sub  { font-size: .72rem; font-weight: 600; color: <?= $txt_muted ?>; margin-top: 2px; }

    .toggle-wrap { display: flex; align-items: center; gap: 8px; cursor: pointer; }
    .toggle-emoji { font-size: 14px; font-weight: 700; color: #b4b2a9; }
    .toggle-emoji.on { color: <?= $aksen ?>; }
    .track {
      width: 52px; height: 28px; border-radius: 20px;
      background: <?= $bg_soft ?>; border: 2px solid <?= $border ?>;
      position: relative; flex-shrink: 0;
    }
    .thumb {
      position: absolute; top: 2px; left: 2px;
      width: 20px; height: 20px; border-radius: 50%;
      background: <?= $aksen ?>;
      display: flex; align-items: center; justify-content: center;
      font-size: 11px; pointer-events: none;
      <?= $thumb_style ?>;
    }

    .kartu {
      background: <?= $bg_card ?>; border: 2px solid <?= $border ?>;
      border-radius: 18px; padding: 1.15rem; margin-bottom: 1rem;
    }
    .kartu-header { display: flex; align-items: center; gap: 8px; margin-bottom: .2rem; }
    .k-icon {
      width: 30px; height: 30px; border-radius: 9px;
      background: <?= $bg_soft ?>; display: flex; align-items: center;
      justify-content: center; font-size: 15px; flex-shrink: 0;
    }
    .kartu-header h2 { font-size: .9rem; font-weight: 800; color: <?= $txt_brand ?>; }
    .sub { font-size: .72rem; font-weight: 600; color: <?= $txt_muted ?>; margin-bottom: .8rem; }
    .badge { font-size: .6rem; padding: 2px 7px; border-radius: 20px; font-weight: 700; }
    .bc { background: #fef3c7; color: #92400e; }
    .bs { background: #d1fae5; color: #065f46; }

    .tema-pill {
      background: <?= $bg_soft ?>; border-radius: 10px;
      padding: .55rem .85rem; border: 1.5px dashed <?= $border ?>;
    }
    .tema-pill .plabel { font-size: .82rem; font-weight: 700; color: <?= $txt_brand ?>; }

    .form-row { display: flex; gap: 8px; margin-bottom: .9rem; }
    .form-row input[type="text"] {
      flex: 1; padding: .5rem .8rem; border-radius: 11px;
      border: 2px solid <?= $border ?>; background: <?= $bg_soft ?>;
      color: <?= $txt_main ?>; font-family: inherit; font-size: .82rem;
      font-weight: 600; outline: none;
    }
    .form-row input[type="text"]:focus { border-color: <?= $aksen ?>; }
    .form-row input::placeholder { color: <?= $txt_muted ?>; font-weight: 400; }
    .btn-add {
      padding: .5rem 1rem; border-radius: 11px; border: none;
      background: <?= $btn_bg ?>; color: #fff;
      font-size: .82rem; font-weight: 700; cursor: pointer; font-family: inherit;
    }
    .btn-add:hover { background: <?= $btn_hover ?>; }
    .btn-add:active { transform: scale(.96); }

    .list { list-style: none; }
    .list li {
      display: flex; justify-content: space-between; align-items: center;
      padding: .45rem 0; border-bottom: 1.5px dashed <?= $border ?>;
      font-size: .87rem; font-weight: 700; color: <?= $txt_main ?>;
    }
    .list li:last-child { border-bottom: none; }
    .item-l { display: flex; align-items: center; gap: 7px; }
    .dot {
      width: 24px; height: 24px; border-radius: 7px;
      background: <?= $bg_soft ?>; display: flex; align-items: center;
      justify-content: center; font-size: 13px; flex-shrink: 0;
    }
    .btn-rm {
      background: transparent; border: 1.5px solid <?= $border ?>;
      color: <?= $aksen ?>; border-radius: 7px; padding: .18rem .55rem;
      font-size: .68rem; font-weight: 700; cursor: pointer; font-family: inherit;
    }
    .btn-rm:hover { background: <?= $bg_soft ?>; }

    .empty { text-align: center; padding: 1.5rem 1rem; }
    .empty .big { font-size: 2rem; display: block; margin-bottom: .4rem;
      animation: wiggle 2.5s ease-in-out infinite; }
    .empty p { font-size: .78rem; font-weight: 700; color: <?= $txt_muted ?>; }
    @keyframes wiggle { 0%,100%{transform:rotate(-6deg)} 50%{transform:rotate(6deg)} }

    .ftr {
      display: flex; justify-content: space-between; align-items: center;
      margin-top: .8rem; padding-top: .8rem; border-top: 1.5px dashed <?= $border ?>;
    }
    .ftr-total { font-size: .78rem; font-weight: 700; color: <?= $txt_brand ?>; }
    .ftr-total span { font-size: 1rem; color: <?= $aksen ?>; }
    .btn-clear {
      padding: .3rem .8rem; border-radius: 9px;
      border: 2px solid <?= $border ?>; background: transparent;
      color: <?= $aksen ?>; font-size: .7rem; font-weight: 700;
      cursor: pointer; font-family: inherit;
    }
    .btn-clear:hover { background: <?= $bg_soft ?>; }
    .track { 
        cursor: pointer;
        transition: 0.3s; 
    }
    .thumb {
        transition: 0.3s;
    }
  </style>
</head>
<body>
<div class="wrap">

  <form method="post" id="formTema" style="position:absolute;visibility:hidden">
    <input type="hidden" name="tema" value="<?= $tema_next ?>">
    <input type="hidden" name="set_tema" value="1">
  </form>

  <div class="topbar">
    <div>
      <div class="brand-name">Pink Shop~</div>
      <div class="brand-sub">Happy shopping</div>
    </div>
    <div class="toggle-wrap" onclick="toggleTheme()">
      <span class="toggle-emoji <?= $lbl_t_on ?>">☀️</span>
      <div class="track">
        <div class="thumb"><?= $thumb_emoji ?></div>
      </div>
      <span class="toggle-emoji <?= $lbl_g_on ?>">🌙</span>
    </div>
  </div>

  <div class="kartu">
    <div class="kartu-header">
      <div class="k-icon">୨ৎ˚⋆</div>
      <h2>Pilih Tema &nbsp;<span class="badge bc">♡ Cookie</span></h2>
    </div>
    <p class="sub">Tema tersimpan otomatis selama 7 hari</p>
    <div class="tema-pill">
      <div class="plabel"><?= $tema_label ?></div>
    </div>
  </div>

  <div class="kartu">
    <div class="kartu-header">
      <div class="k-icon">୨ৎ˚⋆</div>
      <h2>Keranjang Belanja &nbsp;<span class="badge bs">♡ Session</span></h2>
    </div>
    <p class="sub">Item tersimpan selama sesi aktif</p>
    <form method="post" class="form-row">
      <input type="text" name="nama_item" placeholder="Tambah item..." required>
      <button type="submit" name="tambah_item" class="btn-add">+ Tambah</button>
    </form>

    <?php if (empty($keranjang)): ?>
      <div class="empty">
        <span class="big">♡</span>
        <p>Keranjang kosong nih~</p>
      </div>
    <?php else: ?>
      <ul class="list">
        <?php foreach ($keranjang as $i => $item): ?>
        <li>
          <div class="item-l">
            <div class="dot"><?= $emojis[$i % count($emojis)] ?></div>
            <?= $item ?>
          </div>
          <form method="post">
            <input type="hidden" name="index_item" value="<?= $i ?>">
            <button type="submit" name="hapus_item" class="btn-rm">hapus</button>
          </form>
        </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

  </div>

</div>

<script>
function toggleTheme() {
    let current = "<?= $tema ?>";
    let next = current === "gelap" ? "terang" : "gelap";

    document.cookie = "tema_pilihan=" + next + "; path=/; max-age=604800";

    location.reload();
}
</script>

</body>
</html>