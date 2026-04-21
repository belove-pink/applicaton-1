<?php
$email = $_POST['email'];
$password = $_POST['password'];

if ($email == "admin@gmail.com" && $password == "123") {
    echo "Login berhasil!";
} else {
    echo "Login gagal!";
}
?>