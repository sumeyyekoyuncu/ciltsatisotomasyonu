<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "satis";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Veritabanına bağlanılamadı: " . $conn->connect_error);
}

$ciltTipleri = array();
$sqlCiltTipleri = "SELECT * FROM cilttip";
$resultCiltTipleri = $conn->query($sqlCiltTipleri);
if ($resultCiltTipleri->num_rows > 0) {
    while ($row = $resultCiltTipleri->fetch_assoc()) {
        $ciltTipleri[$row["cilttipiID"]] = $row["cilttipiAdi"];
    }
}

$ciltSorunlari = array();
$sqlCiltSorunlari = "SELECT * FROM ciltsorun";
$resultCiltSorunlari = $conn->query($sqlCiltSorunlari);
if ($resultCiltSorunlari->num_rows > 0) {
    while ($row = $resultCiltSorunlari->fetch_assoc()) {
        $ciltSorunlari[$row["ciltsorunID"]] = $row["ciltsorunuAdi"];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $age = $_POST["age"];
    $gender = $_POST["gender"];
    $skinType = $_POST["skin_type"];
    $skinIssue = $_POST["skin_issue"];
    
    $stmt = $conn->prepare("INSERT INTO kullanici (kullaniciAdi, mail, sifre, yas, cins, cilttipiID, ciltsorunID) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssisis", $username, $email, $password, $age, $gender, $skinType, $skinIssue);
    $stmt->execute();
    $stmt->close();

    echo "<p>Kayıt başarıyla oluşturuldu!</p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <style>
        body {
    background-color: #ffe6f2;
    font-family: Arial, sans-serif;
    color: #333;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.admin-button {
        background-color: #ff6699;
        color: #fff;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
    }

    .admin-button:hover {
        background-color: #ff3366;
    }
.container {
    width: 80%;
    margin-top: 20px;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #ff6699;
}

form {
    width: 100%;
    max-width: 400px;
    margin: 0 auto;
}

label {
    display: block;
    margin-bottom: 5px;
    color: #ff6699;
}

input[type="text"],
input[type="email"],
input[type="password"],
select,
input[type="number"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

input[type="submit"] {
    width: 100%;
    padding: 10px;
    background-color: #ff6699;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #ff3366;
}

    </style>
</head>
<body>

<div class="container">
    <h1>Kayıt Ol</h1>
    <form action="register.php" method="post">
        <label for="username">Kullanıcı Adı:</label>
        <input type="text" id="username" name="username" required>

        <label for="email">E-posta:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Şifre:</label>
        <input type="password" id="password" name="password" required>

        <label for="age">Yaş:</label>
        <input type="number" id="age" name="age" required>

        <label for="gender">Cinsiyet:</label>
        <select id="gender" name="gender" required>
            <option value="Erkek">Erkek</option>
            <option value="Kadın">Kadın</option>
        </select>

        <label for="skin_type">Cilt Tipi:</label>
        <select id="skin_type" name="skin_type" required>
            <?php
            foreach ($ciltTipleri as $id => $adi) {
                echo "<option value='$id'>$adi</option>";
            }
            ?>
        </select>

        <label for="skin_issue">Cilt Sorunu:</label>
        <select id="skin_issue" name="skin_issue" required>
            <?php
            foreach ($ciltSorunlari as $id => $adi) {
                echo "<option value='$id'>$adi</option>";
            }
            ?>
        </select>

        <input type="submit" value="Kayıt Ol">

        <a href="admin_login.php" class="admin-button">Admin Girişi</a>
    </form>
</div>

</body>
</html>
