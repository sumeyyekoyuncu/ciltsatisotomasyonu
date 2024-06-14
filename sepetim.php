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

// Sepetten ürün çıkarma işlemi
if (isset($_POST['sepettenCikar'])) {
    $urunID = $_POST['urunID'];
    $sql = "DELETE FROM sepet WHERE urunID = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Sorgu hazırlamada hata: " . $conn->error);
    }
    $stmt->bind_param("i", $urunID);
    $stmt->execute();
    $stmt->close();
}

// Satın alma işlemi
if (isset($_POST['satinAl'])) {
    $kullaniciID = 1; // Kullanıcı ID'sini sabit olarak ayarlıyoruz
    $sql = "SELECT * FROM sepet";
    $result = $conn->query($sql);

    if (!$result) {
        die("Sorgu çalıştırmada hata: " . $conn->error);
    }

    while ($row = $result->fetch_assoc()) {
        $urunID = $row['urunID'];
        $miktar = $row['miktar'];
        $satistarih = date('Y-m-d H:i:s'); // Satış tarihini alıyoruz

        $satisSql = "INSERT INTO satiss (urunID, kullaniciID, satistarih, miktar) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($satisSql);
        if (!$stmt) {
            die("Sorgu hazırlamada hata: " . $conn->error);
        }
        $stmt->bind_param("iisi", $urunID, $kullaniciID, $satistarih, $miktar);
       
        $stmt->close();
    }

    // Sepeti temizleme
    $conn->query("TRUNCATE TABLE sepet");

    echo "<script>alert('Siparişiniz başarıyla oluşturuldu!');</script>";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepetim</title>
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

        .navbar {
            background-color: #ff6699;
            padding: 10px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            width: 100%;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .navbar a:hover {
            background-color: #ff3366;
        }

        .container {
            width: 80%;
            margin: 20px 0;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #ff6699;
        }

        .product-grid {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .product {
            display: flex;
            align-items: center;
            border: 1px solid #ff99cc;
            border-radius: 10px;
            padding: 20px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .product img {
            max-width: 150px;
            border-radius: 10px;
        }

        .product-info {
            flex-grow: 1;
            margin-left: 20px;
        }

        .product-title {
            font-size: 1.5em;
            color: #ff3366;
        }

        .product-price {
            font-size: 1.2em;
            color: #ff6699;
        }

        .remove-from-cart {
            background-color: #ff0000;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        .remove-from-cart:hover {
            background-color: #cc0000;
        }

        .purchase-button {
            background-color: #ff6699;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2em;
            display: block;
            margin: 20px auto;
        }

        .purchase-button:hover {
            background-color: #ff3366;
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="index-cilt.php">Ana Sayfa</a>
    <a href="recommendations.php">Öneriler</a>
    <a href="sepetim.php">Sepetim</a>
    <a href="register.php">Kayıt Ol</a>
</div>

<div class="container">
    <h1>Sepetim</h1>
    <div class="product-grid">
        <?php
        $sql = "SELECT sepet.*, urunler.marka, urunler.gorseldosyasi as foto FROM sepet JOIN urunler ON sepet.urunID = urunler.urunID";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='product'>";
                echo "<img src='img/" . $row["foto"] . "' alt='" . $row["urunAdi"] . "'>";
                echo "<div class='product-info'>";
                echo "<div class='product-title'>" . $row["urunAdi"] . "</div>";
                echo "<div class='product-title'>" . $row["marka"] . "</div>";
                echo "<div class='product-price'>₺" . $row["fiyat"] . "</div>";
                echo "<form method='post' action=''>";
                echo "<input type='hidden' name='urunID' value='" . $row["urunID"] . "'>";
                echo "<button type='submit' name='sepettenCikar' class='remove-from-cart'>Kaldır</button>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "Sepetiniz boş.";
        }
        ?>
    </div>
    <form method="post" action="">
        <button type="submit" name="satinAl" class="purchase-button">Satın Al</button>
    </form>
</div>

</body>
</html>
