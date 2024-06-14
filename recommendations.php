<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Öneriler</title>
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

        select {
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
        #sss {
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    margin-top: 20px;
}

#sss h2 {
    color: #ff69b4;
    border-bottom: 2px solid #ff69b4;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

#sss ul {
    list-style-type: none;
    padding: 0;
}

#sss li {
    margin-bottom: 20px;
}

#sss h3 {
    color: #333;
}

#sss p {
    color: #666;
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
    <h1>Cilt Bakım Önerileri</h1>
    <form action="recommendations.php" method="post">
        <label for="skin_type">Cilt Tipi:</label>
        <select id="skin_type" name="skin_type" required>
            <option value="Normal">Normal</option>
            <option value="Kuru">Kuru</option>
            <option value="Yağlı">Yağlı</option>
            <option value="Karma">Karma</option>
        </select>

        <label for="skin_issues">Cilt Sorunları:</label>
        <select id="skin_issues" name="skin_issues[]" multiple required>
            <option value="Sivilce">Sivilce</option>
            <option value="Kırışıklık">Kırışıklık</option>
            <option value="Koyu lekeler">Koyu lekeler</option>
            <option value="Gözenekler">Geniş gözenekler</option>
        </select>

        <input type="submit" value="Öneri Getir">
    </form>
</div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ciltTipi = $_POST["skin_type"];
    $ciltSorunlari = $_POST["skin_issues"];

    $oneriler = array(
        "Sivilce" => array(
            "Normal" => "Çay ağacı yağı içeren ürünler kullanabilirsiniz.",
            "Kuru" => "Nemlendirici ve çay ağacı yağı içeren ürünler kullanabilirsiniz.",
            "Yağlı" => "Salisilik asit içeren ürünler kullanabilirsiniz.",
            "Karma" => "Çay ağacı yağı ve salisilik asit içeren ürünler kullanabilirsiniz."
        ),
        "Kırışıklık" => array(
            "Normal" => "Retinol ve hyaluronik asit içeren ürünler kullanabilirsiniz.",
            "Kuru" => "Yoğun nemlendiriciler ve hyaluronik asit içeren ürünler kullanabilirsiniz.",
            "Yağlı" => "Retinol ve hafif nemlendiriciler kullanabilirsiniz.",
            "Karma" => "Retinol ve hyaluronik asit içeren ürünler kullanabilirsiniz."
        ),
        "Koyu lekeler" => array(
            "Normal" => "C vitamini ve niacinamide içeren ürünler kullanabilirsiniz.",
            "Kuru" => "C vitamini ve yoğun nemlendiriciler kullanabilirsiniz.",
            "Yağlı" => "Niacinamide ve salisilik asit içeren ürünler kullanabilirsiniz.",
            "Karma" => "C vitamini ve niacinamide içeren ürünler kullanabilirsiniz."
        ),
        "Gözenekler" => array(
            "Normal" => "AHA/BHA içeren ürünler kullanabilirsiniz.",
            "Kuru" => "Hafif peeling etkisi olan ürünler kullanabilirsiniz.",
            "Yağlı" => "Kil maskeleri ve BHA içeren ürünler kullanabilirsiniz.",
            "Karma" => "AHA/BHA ve kil maskeleri kullanabilirsiniz."
        )
    );

    echo "<div class='container'>";
    echo "<h2>Öneriler:</h2>";
    foreach ($ciltSorunlari as $sorun) {
        if (isset($oneriler[$sorun][$ciltTipi])) {
            echo "<h3>$sorun:</h3>";
            echo "<p>" . $oneriler[$sorun][$ciltTipi] . "</p>";
        } else {
            echo "<p>Bu cilt sorunu için öneri bulunamadı.</p>";
        }
    }
    echo "</div>";
}
?>
<div id="sss">
    <h2>Sıkça Sorulan Sorular</h2>
    <ul>
        <li>
            <h3>1. Kırışıklıkların nedeni nedir?</h3>
            <p>Kırışıklıkların çoğu zaman yaşlanma sürecinin bir sonucu olarak ortaya çıkar. Ciltteki kolajen ve elastin üretiminin azalması, güneşe maruz kalma, sigara içme, stres ve diğer çevresel faktörler kırışıklıkların oluşumunu hızlandırabilir.</p>
        </li>
        <li>
            <h3>2. Akneleri nasıl önleyebilirim?</h3>
            <p>Aknelerin önlenmesi için düzenli cilt temizliği çok önemlidir. Ayrıca, yağlı veya akneye eğilimli ciltler için uygun ürünler kullanmak, sağlıklı bir diyet benimsemek ve stresten kaçınmak da yardımcı olabilir.</p>
        </li>
        <li>
            <h3>3. Hassas ciltler için hangi ürünler önerilir?</h3>
            <p>Hassas ciltler için parfümsüz, alkol içermeyen ve hipoalerjenik ürünler tercih edilmelidir. Ayrıca, cildi yatıştıran ve nemlendiren içeriklere sahip ürünler kullanmak da önemlidir.</p>
        </li>
        <li>
            <h3>4. Lekeleri azaltmak için ne yapabilirim?</h3>
            <p>Lekelerin azaltılması için güneş koruyucu kullanmak, cilt beyazlatıcı ürünler ve aydınlatıcı bakım ürünleri tercih etmek, düzenli olarak cilt bakımı yapmak ve sağlıklı bir yaşam tarzı benimsemek faydalı olabilir.</p>
        </li>
        <li>
            <h3>5. Solgun ciltler için hangi ürünler önerilir?</h3>
            <p>Solgun ciltler için aydınlatıcı ve canlandırıcı içeriklere sahip ürünler tercih edilmelidir. C vitamini serumları, bronzerlar ve nemlendiriciler solgun cildi canlandırabilir.</p>
        </li>
    </ul>
</div>

</body>
</html>
