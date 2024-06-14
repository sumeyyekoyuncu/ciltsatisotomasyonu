CREATE TABLE urunler (
    urunID INT AUTO_INCREMENT PRIMARY KEY,
    urunAdi VARCHAR(255) NOT NULL,
    marka VARCHAR(255) NOT NULL,
    fiyat DECIMAL(10, 2) NOT NULL,
    urunTuruID INT,
    urunPuan DECIMAL(3, 2),
    gorseldosyasi VARCHAR(255),
    FOREIGN KEY (urunTuruID) REFERENCES uruntur(urunTuruID)
);

CREATE TABLE uruntur (
    urunTuruID INT AUTO_INCREMENT PRIMARY KEY,
    urunTuruAdi VARCHAR(255) NOT NULL
);

CREATE TABLE satiss (
    satisID INT AUTO_INCREMENT PRIMARY KEY,
    urunID INT,
    kullaniciID INT,
    satistarih DATETIME,
    miktar INT,
    FOREIGN KEY (urunID) REFERENCES urunler(urunID),
    FOREIGN KEY (kullaniciID) REFERENCES kullanici(kullaniciID)
);

CREATE TABLE kullanici (
    kullaniciID INT AUTO_INCREMENT PRIMARY KEY,
    kullaniciAdi VARCHAR(255) NOT NULL,
    mail VARCHAR(255) NOT NULL,
    sifre VARCHAR(255) NOT NULL,
    yas INT,
    cins VARCHAR(50),
    cilttipiID INT,
    ciltsorunID INT,
    isAdmin BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (cilttipiID) REFERENCES cilttip(cilttipiID),
    FOREIGN KEY (ciltsorunID) REFERENCES ciltsorun(ciltsorunID)
);

CREATE TABLE cilttip (
    cilttipiID INT AUTO_INCREMENT PRIMARY KEY,
    cilttipiAdi VARCHAR(255) NOT NULL
);

CREATE TABLE ciltsorun (
    ciltsorunID INT AUTO_INCREMENT PRIMARY KEY,
    ciltsorunuAdi VARCHAR(255) NOT NULL
);

CREATE TABLE sepet (
    id INT AUTO_INCREMENT PRIMARY KEY,
    urunID INT,
    urunAdi VARCHAR(255) NOT NULL,
    fiyat DECIMAL(10, 2) NOT NULL,
    miktar INT NOT NULL,
    FOREIGN KEY (urunID) REFERENCES urunler(urunID)
);
