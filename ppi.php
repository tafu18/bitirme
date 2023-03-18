<?php

$accession = 'O14640'; 

// Protein etkileşim verilerinin alındığı URL
$url = 'https://www.ebi.ac.uk/proteins/api/proteins/interaction/' . $accession;

// Verileri almak için curl kullanımı
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);

echo $reponse;