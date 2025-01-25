<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    exit('Unauthorized access.');
}

$json_file = '../assets/json/bank-info.json';
$bank_account = $_POST['bank_account'] ?? '';
$pin = $_POST['pin'] ?? '';

if (!file_exists($json_file)) {
    http_response_code(500);
    exit('Bank information not available.');
}

$json_data = json_decode(file_get_contents($json_file), true);

$is_valid = false;
foreach ($json_data as $account) {
    if ($account['account_number'] === $bank_account && $account['pin'] === $pin) {
        $is_valid = true;
        break;
    }
}

echo $is_valid ? 'VALID' : 'INVALID';

?>