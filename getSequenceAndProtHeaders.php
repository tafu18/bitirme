<?php
// API isteği için URL'nin oluşturulması
$url = 'https://www.ebi.ac.uk/proteins/api/proteins?&size=100&reviewed=true&organism=human&format=fasta';

// Verileri almak için curl kullanımı
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
curl_close($ch);

// Fasta formatındaki verileri başlık ve sekans dizilerine ayırma
$lines = explode("\n", $output);
$sequences = array();
$headers = array();
foreach ($lines as $line) {
    if (substr($line, 0, 1) == '>') {
        $headers[] = $line;
        if (!empty($sequence)) {
            $sequences[] = $sequence;
            $sequence = '';
        }
    } else {
        $sequence .= $line;
    }
}
$sequences[] = $sequence;

$combinedArray = array_combine($headers, $sequences); // array_combine fonksiyonuyla iki diziyi iç içe birleştiriyoruz

//Yazdırmak için
/* foreach($combinedArray as $key => $c_a){
    echo $key . '<br>';
    echo $c_a . '<br><br>';
} */

return $combinedArray;




?>
