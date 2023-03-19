<?php

$accession = 'O14640'; 

// UniProt RESTful API endpoint URL
$url = "https://www.ebi.ac.uk/proteins/api/proteins/interaction/" . $accession;

// CURL kullanarak API isteği gönderme
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// API yanıtını işleme
$data = json_decode($response, true);

// Protein-protein etkileşimleri
$interactions = $data[0]['interactions'];
foreach ($interactions as $interaction) {
    print_r($interaction);
    echo '<br>';
}


?>
