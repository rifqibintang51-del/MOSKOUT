<?php
$ch = curl_init('http://127.0.0.1:8000/api/titik-risiko');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPGET => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_HTTPHEADER => ['Accept: application/json'],
]);
$r = curl_exec($ch);
$info = curl_getinfo($ch);
$err = curl_error($ch);
curl_close($ch);

echo 'HTTP Code: ' . $info['http_code'] . "\n";
echo 'Error: ' . ($err ?: 'none') . "\n";
if ($r) {
    $d = json_decode($r, true);
    echo 'Success: ' . ($d['success'] ? 'true' : 'false') . "\n";
    echo 'Items: ' . count($d['data'] ?? []) . "\n";
}
