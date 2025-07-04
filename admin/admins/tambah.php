<!DOCTYPE html>
<html lang="id"><head><title>Tambah Admin</title></head>
<body>
    <h1>Tambah Admin Baru</h1>
    <form action="proses_tambah.php" method="POST">
        <p><label>Username: <input type="text" name="username" required></label></p>
        <p><label>Password: <input type="password" name="password" required></label></p>
        <p><label>Konfirmasi Password: <input type="password" name="confirm_password" required></label></p>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>