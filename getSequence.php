<?php
// Fasta dosya yolu
$dosya_yolu = 'deneme.fasta';

// Dosyayı aç
$dosya = fopen($dosya_yolu, 'r');

// Sekans verilerini saklamak için boş dizi oluştur
$veriler = array();

// Dosyadan sırayla satırları oku
$baslik = '';
$veri = '';
while (!feof($dosya)) {
    $satir = fgets($dosya);
    
    // Satır boşsa atla
    if ($satir === false) {
        continue;
    }
    
    // Satır başlıksa, önceki veriyi diziye ekle ve yeni veri için değişkenleri sıfırla
    if (substr($satir, 0, 1) === '>') {
        if (!empty($veri)) {
            $veriler[] = array(
                'baslik' => $baslik,
                'veri' => trim($veri)
            );
            $veri = '';
        }
        $baslik = trim($satir);
    }
    // Satırdaki veriyi ekle
    else {
        $veri .= trim($satir);
    }
}

// Dosyadan son veriyi diziye ekle
if (!empty($veri)) {
    $veriler[] = array(
        'baslik' => $baslik,
        'veri' => trim($veri)
    );
}

// Dosyayı kapat
fclose($dosya);

// Elde edilen sekans verilerini yazdır
foreach ($veriler as $veri) {
    echo $veri['veri'] . "<br><br>";
}
