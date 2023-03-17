<?php
ini_set('memory_limit', '-1');
set_time_limit(0);

function getSimilarrity($sequence, $sequence_part)
{

    $sequenceLength = strlen($sequence);
    $sequencePartLength = strlen($sequence_part);
    $errorLimit = (int)(($sequencePartLength * 5) / 100); // hata payı
    $counter = 0;


    for ($i = 0; $i <= $sequenceLength - $sequencePartLength; $i++) {
        $tempSequencePart = substr($sequence, $i, $sequencePartLength);
        $errors = 0;
        for ($j = 0; $j < $sequencePartLength; $j++) {
            if ($sequence_part[$j] !== $tempSequencePart[$j]) {
                $errors++;
                if ($errors > $errorLimit) {
                    break;
                }
            }
        }
        if ($errors <= $errorLimit) {
            $counter++;
/*             echo "Kısa string, uzun string içinde bulundu: " . $tempSequencePart . "\n"; */
        }
    }
    if ($counter > 0) {
        return true;
    } else {
        return false;
    }
}

/* $sequence_part = "PGQRVTISCTGSSSNIGAGYVVHWYQQLPGTAPKLLIYGNSNRPSGVPDQFSGSK"; */
$sequence_part = "IVMT";
$sequence_part_lenght = strlen($sequence_part);
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$prot_names = [];
$offset = 0;

/* while ($offset <= 100) { */
    $url = "https://www.ebi.ac.uk/proteins/api/proteins?offset=0&size=100&reviewed=true&organism=human&format=json";

/*     echo 'URL: ' . $url . '<br>'; */
    curl_setopt($ch, CURLOPT_URL, $url);
    $result = curl_exec($ch);
    $proteins = json_decode($result, false);
    curl_close($ch);

    foreach ($proteins as $protein) {
        $sequence = $protein->sequence->sequence;

        if (getSimilarrity($sequence, $sequence_part)) {
            //YOKSA EKLESİN VARSA EKLEMESİN. BELKİ SORGUDA HATA YAPABİLİRİM.
            $prot_names[] = $protein->protein->recommendedName->fullName->value;
/*             echo 'eşleşme var.'; */
        }
    }
/*     $offset = $offset + 10;
    echo 'offset: ' . $offset . '<br>';
} */
echo "<br><br><br><br><br>";
if (count($prot_names) > 0) {
    print_r($prot_names);
    echo "<br>";
}

/*
 *
 *  Bu kod, uzun string içindeki tüm olası kısa string kombinasyonlarını 
 *  kontrol eder ve en fazla 5 karakter hataya izin verir. 
 *  İlk önce, strlen() fonksiyonlarıyla uzun ve kısa stringlerin 
 *  uzunlukları hesaplanır ve $limit değişkenine hataya izin verilecek
 *  maksimum sayı atanır. Daha sonra, for döngüsü ile uzun string
 *  içindeki tüm olası kısa string kombinasyonları kontrol edilir. 
 *  Her bir olası kısa string için, bir iç for döngüsü kısa stringin 
 *  her bir karakterini, uzun string içindeki aynı konumdaki karakterlerle 
 *  karşılaştırır ve hataları sayar. Eğer hata sayısı $limit değerini aşarsa, 
 *  bu kombinasyon atlanır. Aksi takdirde, "Kısa string, uzun string içinde 
 *  bulundu:" mesajı ve bulunan kısa string yazdırılır.
 *  
 */