<?php
    define('SB_URL', 'https://lppngyplvepvxzdemisz.supabase.co');
    define('SB_KEY', 'sb_publishable_-TvrJGJpBj_Xea42Yoji6g_4OK8J60E');

    function supabase_request($method, $endpoint, $data = null) {
        $url = SB_URL . "/rest/v1/" . $endpoint;
        $ch = curl_init($url);
        
        $headers = [
            "apikey: " . SB_KEY,
            "Authorization: Bearer " . SB_KEY,
            "Content-Type: application/json",
            "Prefer: return=minimal"
        ];

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            die("cURL Error: " . curl_error($ch));
        }
        
        curl_close($ch);

        if ($httpCode >= 400) {
            die("Supabase Error: " . $response);
        }

        return [
            'data' => json_decode($response, true),
            'status' => $httpCode
        ];
    }
?>