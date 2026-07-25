<?php
$dir = __DIR__ . '/public/images/products';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

foreach ($images as $filename => $url) {
    $path = $dir . '/' . $filename;
    echo "Downloading $filename... ";
    
    // Using cURL for standard robust download
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $data !== false) {
        file_put_contents($path, $data);
        echo "OK\n";
    } else {
        echo "FAILED (HTTP: $httpCode)\n";
    }
}

echo "Done downloading product images.\n";
