<?php
$showProfile = false;
$nama = '';
$alamat = '';
$email = '';
$biografi = '';
$namaFile = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $showProfile = true;

    $nama = $_POST['nama'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $email = $_POST['email'] ?? '';
    $biografi = $_POST['biografi'] ?? '';

    if (!empty($_FILES['foto']['name'])) {
        $namaFile = $_FILES['foto']['name'];
        $tmpName = $_FILES['foto']['tmp_name'];
        move_uploaded_file($tmpName, "uploads/" . $namaFile);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Form & Profil Biografi</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e9acbb, #ffffff);
            padding: 40px;
        }

        .container {
            background: white;
            padding: 35px;
            border-radius: 15px;
            width: 90%;
            max-width: 700px; /* DILEBARKAN */
            margin: auto;
            box-shadow: 0 8px 20px rgba(255, 105, 180, 0.2);
        }

        .foto-profile {
            text-align: center;
            margin-bottom: 20px;
        }

        .foto-profile img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #ffafcc;
        }

        h2 {
            text-align: center;
            color: #f3a2be;
        }

        label {
            font-weight: 600;
            color: #ffafcc;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            margin-bottom: 18px;
            border-radius: 8px;
            border: 1px solid #a2d2ff;
        }

        input[type="submit"], .btn {
            background-color: #c6d192;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            padding: 10px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
        }

        input[type="submit"]:hover, .btn:hover {
            background-color: #839838;
        }

        .profile {
            margin-top: 25px;
            padding: 20px;
            background-color: #fff0f6;
            border-radius: 12px;
            border: 1px solid #bcacdd;
        }

        img {
            margin-top: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        hr {
            border: none;
            height: 1px;
            background: #ffc1d6;
            margin: 25px 0;
        }
    </style>
</head>
<body>

<div class="container">

<?php if (!$showProfile) { ?>

    <h2>Form Biografi</h2>

    <form method="POST" enctype="multipart/form-data">

        <label>Nama:</label>
        <input type="text" name="nama" required>

        <label>Alamat:</label>
        <input type="text" name="alamat" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Biografi:</label>
        <textarea name="biografi" rows="4"></textarea>

        <label>Upload Foto (Opsional):</label>
        <input type="file" name="foto">

        <input type="submit" value="Kirim">
    </form>

<?php } else { ?>

    <h2>Profile</h2>

    <div class="profile">

    <?php if ($namaFile != '') { ?>
        <div class="foto-profile">
            <img src="uploads/<?php echo $namaFile; ?>">
        </div>
    <?php } ?>

        <p><b>Nama:</b> <?php echo $nama; ?></p>
        <p><b>Alamat:</b> <?php echo $alamat; ?></p>
        <p><b>Email:</b> <?php echo $email; ?></p>
        <p><b>Biografi:</b> <?php echo $biografi; ?></p>

    </div>

    <br>
    <button type="button" onclick="window.location.href='index.php'" class="btn">← Kembali ke Form</button>

<?php } ?>

</div>

</body>
</html>