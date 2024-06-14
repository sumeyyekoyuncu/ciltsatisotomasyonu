<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "satis";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Veritabanına bağlanılamadı: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    $stmt = $conn->prepare("SELECT * FROM kullanici WHERE kullaniciAdi = ? AND sifre = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['isAdmin'] = true;
        header("Location: index-cilt.php");
    } else {
        echo "<p>Geçersiz kullanıcı adı veya şifre.</p>";
    }

    $stmt->close();
}


$conn->close();
?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Giriş Yap</title>
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
        input[type="password"] {
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
    <h1>Admin Giriş Yap</h1>
    <form action="admin_login.php" method="post">
        <label for="username">Kullanıcı Adı:</label>
        <input type="text" id="username" name="username" >

        <label for="password">Şifre:</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" value="Giriş Yap">
    </form>
</div>

</body>
</html>
