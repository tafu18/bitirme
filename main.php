<?php
ini_set('memory_limit', '-1');
set_time_limit(0);

function getProtein($offset, $size, $organism)
{
    // Protein verilerinin alındığı URL
    $url = "https://www.ebi.ac.uk/proteins/api/proteins?offset=" . $offset . "&size=" . $size . "&reviewed=true&organism=" . $organism . "&format=fasta";

    // Verileri almak için curl kullanımı
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

function getSequenceProtName(string $proteinsFasta)
{

    $sequence = "";
    // Fasta formatındaki verileri başlık ve sekans dizilerine ayırma
    $lines = explode("\n", $proteinsFasta);
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

    //YAZDIRMAL İÇİN
    /*  foreach($proteins as $key => $seq){
    echo $key . '<br>';
    echo $seq . '<br><br>';
} 
 */
    return $proteins;
}
//Deneme amaçlı
//print_r(getSequenceProtName(getProtein(0, 100, 'human')));

function getSimilarrity($sequence, $sequence_part)
{
    $sequenceLength = strlen($sequence);
    $sequencePartLength = strlen($sequence_part);
    $errorLimit = (int)(($sequencePartLength * 5) / 100); // hata payı
    $counter = 0;
    $temp = [];

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
            //echo "Kısa string, uzun string içinde bulundu: " . $tempSequencePart . "\n";
            $temp[] = $tempSequencePart;
        }
    }
    return $temp;
}

function extractTxt(array $datas)
{
    // Dosya adı ve yolunu belirle
    $filename = 'similarity.txt';

    // Dosyayı aç ve verileri dosyaya yaz
    if ($handle = fopen($filename, 'w')) {
        foreach ($datas as $line) {
            fwrite($handle, $line . PHP_EOL);
        }
        // Dosyayı kapat
        fclose($handle);
    }
}

function getBlast()
{
    $proteinsFasta = getProtein(0, 100, 'human');
    $sequences = getSequenceProtName($proteinsFasta);

    $sequence_part = "IVMT";
    $prot_names = [];
    $blast_seq = [];

    foreach ($sequences as $key => $sequence) {
        $blast = getSimilarrity($sequence, $sequence_part);
        if ($blast) {
            //YOKSA EKLESİN VARSA EKLEMESİN. BELKİ SORGUDA HATA YAPABİLİRİM.
            $prot_names[] = $key;
            array_push($blast_seq, $blast[0]);
        }
    }

    return $blast_seq;
}

$blast = getBlast();

extractTxt($blast);








?>



































<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="list-group">
                    <?php foreach ($blast as $seq) { ?>
                        <button type="button" class="list-group-item list-group-item-action word-break text-center" style="overflow-wrap: break-word;"><?= $seq ?></button>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>