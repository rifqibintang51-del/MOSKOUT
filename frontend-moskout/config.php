<?php
session_start();

define('BASE_API', 'https://admin-moskout.freedev.app/api');

function fetch_api($endpoint, $method = 'GET', $data = null, &$httpCode = null) {
    $url = BASE_API . $endpoint;
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $headers = ['Accept: application/json'];

    if (isset($_SESSION['api_token'])) {
        $headers[] = 'Authorization: Bearer ' . $_SESSION['api_token'];
    }

    if ($method === 'POST' && $data) {
        $payload = json_encode($data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Content-Length: ' . strlen($payload);
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($response === false) {
        return [
            'success' => false,
            'message' => 'Gagal terhubung ke API Server Laravel. Pastikan server berjalan di ' . BASE_API
        ];
    }

    $decoded = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [
            'success' => false,
            'message' => 'Format response API tidak valid.'
        ];
    }

    return $decoded;
}

function callAPI($method, $endpoint, $data = null) {
    $httpCode = null;
    $response = fetch_api($endpoint, $method, $data, $httpCode);
    return [
        'status_code' => $httpCode ?: 200,
        'response' => $response
    ];
}
