<?php

// Endpoint URL'si
$url = "https://www.ebi.ac.uk/proteins/api/proteins?offset=10&size=1&reviewed=true&organism=human&format=fasta";

// API'yi çağırın ve sonuçları çekin
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// FASTA formatındaki yanıtı işleyin
$lines = explode("\n", $response);
$firstLine = $lines[0];
$accession = explode("|", $firstLine)[1];

// Accession numarasını görüntüleyin
echo "Protein Accession: " . $accession;
?>