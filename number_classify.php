<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

function is_prime($n) {
    if ($n <= 1) return false;
    for ($i = 2; $i <= sqrt($n); $i++) 
        if ($n % $i == 0) return false;
    return true;
}

function is_perfect($n) {
    if ($n <= 1) return false;
    $sum = 1;
    for ($i = 2; $i <= sqrt($n); $i++) 
        if ($n % $i == 0) $sum += $i + ($n / $i);
    return $sum == $n;
}

function is_armstrong($n) {
    $num = abs($n);
    $digits = str_split((string)$num);
    $power = count($digits);
    $sum = array_sum(array_map(fn($d) => pow($d, $power), $digits));
    return $sum == $num;
}

$input = $_GET['number'] ?? null;

if (!is_numeric($input) || !ctype_digit((string)abs($input))) {
    http_response_code(400);
    echo json_encode(["number" => $input, "error" => true]);
    exit;
}

$number = (int)$input;
$abs_num = abs($number);
$properties = [];
$fun_fact = "";

if (is_armstrong($number)) {
    $properties[] = "armstrong";
    $digits = str_split((string)$abs_num);
    $fact = implode("^$power + ", $digits) . "^$power = $abs_num";
    $fun_fact = "$abs_num is an Armstrong number because $fact";
} elseif (is_prime($abs_num)) {
    $properties[] = "prime";
    $fun_fact = "$abs_num is a prime number.";
} elseif (is_perfect($abs_num)) {
    $properties[] = "perfect";
    $fun_fact = "$abs_num is a perfect number.";
}

$properties[] = ($abs_num % 2 == 0) ? "even" : "odd";

echo json_encode([
    "number" => $number,
    "is_prime" => is_prime($abs_num),
    "is_perfect" => is_perfect($abs_num),
    "properties" => $properties,
    "digit_sum" => array_sum(str_split((string)$abs_num)),
    "fun_fact" => $fun_fact ?: "$abs_num is just a cool number!"
]);
?>
