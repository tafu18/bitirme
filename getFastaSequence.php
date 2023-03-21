<?php

// Protein verilerinin alındığı URL
$url = 'https://www.ebi.ac.uk/proteins/api/proteins?&size=10&reviewed=true&organism=human&format=fasta';

// Protein verilerini al
$data = file_get_contents($url);

// Veri satırlarına ayır
$lines = explode("\n", $data);

// Her bir proteini işle
$current_sequence = '';
foreach ($lines as $line) {
    // Başlık satırı
    if (substr($line, 0, 1) === '>') {
        // Eski sekansı yazdır
        if (!empty($current_sequence)) {
            $arraySequence [] = $current_sequence . PHP_EOL;
        }
        // Yeni proteini başlat
        $current_sequence = '';
    } else {
        // Sekans verilerini ekle
        $current_sequence .= trim($line);
    }
}
// Son proteini yazdır
if (!empty($current_sequence)) {
    $arraySequence [] = $current_sequence . PHP_EOL;
}

foreach($arraySequence as $key => $sequence){
    echo $key + 1 . '=> ' . $sequence;
    echo "<br><br><br>";
}
