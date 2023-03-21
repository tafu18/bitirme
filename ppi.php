<?php

function extractTxt(array $datas)
{
    // Dosya adı ve yolunu belirle
    $filename = 'interactions.txt';

    // Dosyayı aç ve verileri dosyaya yaz
    if ($handle = fopen($filename, 'w')) {
        foreach ($datas as $line) {
            fwrite($handle, $line . PHP_EOL);
        }
        // Dosyayı kapat
        fclose($handle);
    }
}

$accession = 'Q9BYF1'; 

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
/* print_r($data); */
// Protein-protein etkileşimleri
$interactions = $data[0]['interactions'];
foreach ($interactions as $key => $interaction) {
    $interactionsTxt = 
    '<b>' . $key + 1 . '</b><br>' . 
    "Accession1: " . $interaction['accession1'] . " - " . "Accession2: " . $interaction['accession2'] . "<br>" . 
    "Interactor1: " . $interaction['interactor1'] . " - " . "Interactor2: " . $interaction['interactor2'] . "<br>" . 
    "---------------------------------------------------------------------------- <br>";
    echo $interactionsTxt;

    $saveTxt = 
    $key + 1 . ' ' .  
    "  Accession1: " . $interaction['accession1'] . " - " . "Accession2: " . $interaction['accession2'] . '  =>' .
    "  Interactor1: " . $interaction['interactor1'] . " - " . "Interactor2: " . $interaction['interactor2'];

    $interactionsArray [] = $saveTxt;
    
    
}


extractTxt($interactionsArray);


?>
