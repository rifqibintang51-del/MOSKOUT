<?php
$opts = ['http'=>['method'=>'GET','header'=>'Accept: application/json','timeout'=>5]];
$ctx = stream_context_create($opts);

$tests = [
    'GET /api/titik-risiko' => '/titik-risiko',
    'GET /api/titik-risiko/1/pemeriksaan' => '/titik-risiko/1/pemeriksaan',
    'GET /api/titik-risiko/level/rendah' => '/titik-risiko/level/rendah',
    'GET /api/titik-risiko/level/tinggi' => '/titik-risiko/level/tinggi',
];

$base = 'http://127.0.0.1:8000/api';

$allOk = true;
foreach ($tests as $label => $endpoint) {
    $r = @file_get_contents($base . $endpoint, false, $ctx);
    if ($r === false) {
        echo "FAIL: $label (no response)\n";
        $allOk = false;
        continue;
    }
    $d = json_decode($r, true);
    $ok = ($d['success'] ?? false) ? 'OK' : 'FAIL';
    $cnt = count($d['data'] ?? []);
    echo "$ok: $label ($cnt items)\n";
    if (!$d['success']) $allOk = false;
}

// POST validation test
$postCtx = stream_context_create([
    'http'=>['method'=>'POST', 'header'=>"Content-Type: application/json\r\nAccept: application/json",
             'content'=>json_encode([]), 'timeout'=>5]
]);
$r2 = @file_get_contents($base . '/pemeriksaan-risiko', false, $postCtx);
$d2 = json_decode($r2, true);
echo ($d2['success'] ? 'FAIL' : 'OK') . ": POST empty data (validation blocked)\n";
if ($d2['success']) $allOk = false;

// POST valid data
$postCtx2 = stream_context_create([
    'http'=>['method'=>'POST', 'header'=>"Content-Type: application/json\r\nAccept: application/json",
             'content'=>json_encode([
                 'titik_risiko_id'=>3, 'petugas_id'=>201,
                 'tanggal_pemeriksaan'=>date('Y-m-d'),
                 'ditemukan_jentik'=>'tidak', 'kondisi_lingkungan'=>'Bersih dan rapi.',
                 'tindakan_dilakukan'=>'Edukasi warga.', 'status_akhir'=>'aman'
             ]), 'timeout'=>5]
]);
$r3 = @file_get_contents($base . '/pemeriksaan-risiko', false, $postCtx2);
$d3 = json_decode($r3, true);
echo ($d3['success'] ? 'OK' : 'FAIL') . ": POST valid data (created ID: {$d3['data']['id']})\n";
if (!$d3['success']) $allOk = false;

echo "\n" . ($allOk ? "ALL TESTS PASSED" : "SOME TESTS FAILED") . "\n";
