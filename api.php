<?php

$url = 'https://www.trthaber.com/';
$html = file_get_contents($url);

if ($html === false) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Web sayfası alınamadı'], JSON_PRETTY_PRINT);
    exit;
}

$doc = new DOMDocument();
libxml_use_internal_errors(true); 
$doc->loadHTML($html);
libxml_clear_errors();

$xpath = new DOMXPath($doc);

// Verileri içeren düğümleri bul
$nodes = $xpath->query('//*[contains(@class, "homepage-economy-bar")]//span');

$results = [];
$labels = ['BIST', 'EURO', 'DOLAR', 'ALTIN'];
$currentLabel = null;

foreach ($nodes as $node) {
    $textContent = trim($node->textContent);
    $class = $node->getAttribute('class');

    if (in_array($textContent, $labels)) {
        $currentLabel = $textContent;
        $results[$currentLabel] = [
            'value' => '',
            'change' => '',
            'direction' => ''
        ];
    } elseif ($currentLabel) {
        if (preg_match('/^[-+%0-9.,\s]+$/', $textContent)) {
            if (strpos($textContent, '%') !== false) {
                $results[$currentLabel]['change'] = $textContent;
                if (strpos($class, 'up') !== false) {
                    $results[$currentLabel]['direction'] = 'up';
                } elseif (strpos($class, 'down') !== false) {
                    $results[$currentLabel]['direction'] = 'down';
                } else {
                    $results[$currentLabel]['direction'] = 'neutral'; // Yön yoksa nötr olarak ayarla
                }
            } else {
                $results[$currentLabel]['value'] = $textContent;
            }
        }
    }
}

// Boş ya da eksik veri kontrolü
foreach ($labels as $label) {
    if (!isset($results[$label])) {
        $results[$label] = [
            'value' => 'N/A',
            'change' => 'N/A',
            'direction' => 'neutral'
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($results, JSON_PRETTY_PRINT);

?>