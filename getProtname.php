<?php

// Protein verilerinin alındığı URL
$url = 'https://www.ebi.ac.uk/proteins/api/proteins?size=500&reviewed=true&organism=human&format=fasta';
#$url = "https://rest.uniprot.org/uniprotkb/search?query=reviewed:true+AND+organism_id:9606&format=fasta&size=500";

// Verileri almak için curl kullanımı
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
curl_close($ch);
$sequence = "";
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
$protein_names = array();

foreach ($combinedArray as $header => $combine) {
    $parcalanmis = explode('|', $header);
    $parcalanmis = explode(' ', $parcalanmis[2]);
    $name = [];
    foreach ($parcalanmis as $parca) {
        $OS = $parca[0] . $parca[1];
        if ($OS != "OS") {
            $name[] = $parca;
        } else {
            break;
        }
    }
    array_shift($name);
    $protein_name = implode(" ", $name);
    $proteins[$protein_name] = $combine;


}
/* return $proteins; */

//YAZDIRMAL İÇİN
 foreach($proteins as $key => $seq){
    echo $key . '<br>';
    echo $seq . '<br><br>';
} 
echo count($proteins);
