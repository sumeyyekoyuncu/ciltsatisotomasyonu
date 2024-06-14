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
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    session_destroy();
    header("Location: index-cilt.php");
    exit;
}
if (isset($_POST['sepeteEkle'])) {
    $urunID = $_POST['urunID'];
    $urunAdi = $_POST['urunAdi'];
    $fiyat = $_POST['fiyat'];
    
    $sql = "INSERT INTO sepet (urunID, urunAdi, fiyat, miktar) VALUES (?, ?, ?, 1)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isd", $urunID, $urunAdi, $fiyat);
    $stmt->execute();
    $stmt->close();
}

if (isset($_POST['sepettenCikar'])) {
    $urunID = $_POST['urunID'];
    
    $sql = "DELETE FROM sepet WHERE urunID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $urunID);
    $stmt->execute();
    $stmt->close();
}

if (isset($_POST['urunSil'])) {
    $urunID = $_POST['urunID'];
    
    $sql = "DELETE FROM urunler WHERE urunID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $urunID);
    $stmt->execute();
    $stmt->close();
}

$sql = "SELECT * FROM urunler";
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'ascendingPrice';
switch ($filter) {
    case 'ascendingPrice':
        $sql .= " ORDER BY fiyat ASC";
        break;
    case 'descendingPrice':
        $sql .= " ORDER BY fiyat DESC";
        break;
    case 'ascendingRating':
        $sql .= " ORDER BY urunPuan ASC";
        break;
    case 'descendingRating':
        $sql .= " ORDER BY urunPuan DESC";
        break;
    default:
        // Varsayılan sıralama (fiyata göre artan)
        $sql .= " ORDER BY fiyat ASC";
        break;
}


$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cilt Bakım Ürünleri Satış</title>
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

        .banner {
            width: 100%;
            max-height: 400px;
            overflow: hidden;
        }

        .banner img {
            width: 100%;
            height: auto;
            display: block;
        }

        .main-content {
            display: flex;
            width: 80%;
            margin: 20px 0;
        }

        .container {
            flex: 3;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar {
            flex: 1;
            padding: 20px;
            margin-left: 20px;
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
            flex-wrap: wrap;
            gap: 20px;
        }

        .product {
            flex: 0 1 calc(50% - 20px);
            box-sizing: border-box;
            border: 1px solid #ff99cc;
            border-radius: 10px;
            padding: 20px;
            display: flex;
            align-items: center;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.5s forwards;
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

        .product-details {
            text-align: center;
        }

        .add-to-cart {
            background-color: #ff6699;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        .add-to-cart:hover {
            background-color: #ff3366;
        }

        a {
            color: #ff6699;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            display: none;
        }

        .popup-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 80%;
            max-width: 400px;
        }

        .popup h2 {
            color: #ff6699;
        }

        .popup button {
            background-color: #ff6699;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .popup button:hover {
            background-color: #ff3366;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .product:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }
        .remove-from-cart {
            background-color: #ff0000; /* Kırmızı arka plan rengi */
            color: white; /* Beyaz metin rengi */
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        .remove-from-cart:hover {
            background-color: #cc0000; 
        }

        .remove-product {
            background-color: #333; /* Koyu arka plan rengi */
            color: white; /* Beyaz metin rengi */
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        .remove-product:hover {
            background-color: #000; 
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="index-cilt.php">Ana Sayfa</a>
    <a href="recommendations.php">Öneriler</a>
    <a href="sepetim.php">Sepetim</a>
    <a href="register.php">Kayıt Ol</a>
    <?php if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']): ?>
        <a href="index-cilt.php?logout=true">Çıkış Yap (Admin)</a>
    <?php endif; ?>
</div>

<div class="banner">
    <img src="img/girisfoto.jpg" alt="Banner Görseli">
</div>

<div class="main-content">
    <div class="container">
        <h1>Cilt Bakım Ürünleri Satış</h1>
        <p>En iyi cilt bakım ürünlerini burada bulabilirsiniz. Hemen alışverişe başlayın!</p>
        <div class="filter">
    <form method="GET">
        <label for="filter">Filtrele:</label>
        <select name="filter" id="filter">
            <option value="ascendingPrice">Fiyata Göre Artan</option>
            <option value="descendingPrice">Fiyata Göre Azalan</option>
            <option value="ascendingRating">Puana Göre Artan</option>
            <option value="descendingRating">Puana Göre Azalan</option>
        </select>
        <button type="submit">Filtrele</button>
    </form>
</div>

<div class="product-grid">
    <?php
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product'>";
            echo "<img src='img/" . $row["gorseldosyasi"] . "' alt='" . $row["urunAdi"] . "'>";
            echo "<div class='product-info'>";
            echo "<div class='product-title'>" . $row["urunAdi"] . "</div>";
            echo "<div class='product-title'>" . $row["marka"] . "</div>";
            echo "<div class='product-price'>₺" . $row["fiyat"] . "</div>";
            echo "<div class='product-price'>Ürün puanı: " . $row["urunPuan"] . "</div>";
           
            echo "<form method='post' action=''>";
            echo "<input type='hidden' name='urunID' value='" . $row["urunID"] . "'>";
            echo "<input type='hidden' name='urunAdi' value='" . $row["urunAdi"] . "'>";
            echo "<input type='hidden' name='fiyat' value='" . $row["fiyat"] . "'>";
            echo "<button type='submit' name='sepeteEkle' class='add-to-cart'>Sepete Ekle</button>";
            
            if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']) {
                echo "<button type='submit' name='urunSil' class='remove-product'>Ürünü Sil</button>";
                
            }
            echo "</form>";
            echo "</div>";
            echo "</div>";
            
        }
    } else {
        echo "Ürün bulunamadı.";
    }

    $conn->close();
    ?>
</div>

    </div>

    <div class="sidebar">
    <img src="img/discount.gif" alt="Sağ Tarafa GIF" style="width:100%;">

        <img src="img/gifserum.gif" alt="Sağ Tarafa GIF" style="width:100%;">

    </div>
</div>

<div class="popup" id="popup">
    <div class="popup-content">
        <h2>İlk Alışverişe Özel İndirim!</h2>
        <p>İlk alışverişinizde kullanmak için indirim kodunuz:</p>
        <h3>INDIRIM10</h3>
        <button onclick="closePopup()">Tamam</button>
    </div>
</div>

<script>
    window.onload = function() {
        document.getElementById('popup').style.display = 'flex';
    };

    function closePopup() {
        document.getElementById('popup').style.display = 'none';
    }
</script>

</body>
</html>
