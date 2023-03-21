<?php
// Uniprot ID
$accession = 'Q9H2X3';

// EBI REST API URL
$url = 'https://www.ebi.ac.uk/proteins/api/proteins/' . $accession . '.fasta';

// HTTP isteği gönderme
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Cevabı ekrana yazdırma
echo $response;
?>
