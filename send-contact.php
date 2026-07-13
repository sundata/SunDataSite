<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');

if (function_exists('mb_language')) {
    mb_language('Japanese');
}

if (function_exists('mb_internal_encoding')) {
    mb_internal_encoding('UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'POSTで送信してください。', 405);
}

if (!empty($_POST['company'] ?? '')) {
    respond(true, '送信しました。');
}

$name = trim((string)($_POST['name'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$topic = trim((string)($_POST['topic'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

$allowedTopics = [
    '業務システム開発',
    'アプリ開発',
    'ステモン妙典校',
    '民宿事業',
    '民宿・長期契約相談',
    '採用情報',
    'その他',
    '业务系统开发',
    '应用开发',
    'STEMON妙典校',
    '民宿业务',
    '民宿・长期合同咨询',
    '招聘信息',
    '其他',
    'Business System Development',
    'App development',
    'STEMON Myoden',
    'Guesthouse Business',
    'Guesthouse / long-term contract',
    'Recruit',
    'Other',
];

if ($name === '' || $email === '' || $message === '') {
    respond(false, '必須項目を入力してください。', 422);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, 'メールアドレスの形式を確認してください。', 422);
}

if (!in_array($topic, $allowedTopics, true)) {
    $topic = 'その他';
}

if (textLength($name) > 80 || textLength($email) > 120 || textLength($message) > 3000) {
    respond(false, '入力内容が長すぎます。短くして再度送信してください。', 422);
}

$to = 'info@sundata.co.jp';
$subject = 'Web問い合わせ: ' . $topic;
$body = implode("\n", [
    'SunDataサービス株式会社 Webサイトから問い合わせがありました。',
    '',
    'お名前: ' . $name,
    'メール: ' . $email,
    '相談内容: ' . $topic,
    '',
    'メッセージ:',
    $message,
    '',
    '送信日時: ' . date('Y-m-d H:i:s'),
    '送信元IP: ' . ($_SERVER['REMOTE_ADDR'] ?? ''),
]);

$safeFrom = 'noreply@sundata.co.jp';
$replyToName = encodeHeader(sanitizeHeader($name));
$headers = [
    'From: SunData Website <' . $safeFrom . '>',
    'Reply-To: ' . $replyToName . ' <' . $email . '>',
    'Content-Type: text/plain; charset=UTF-8',
    'Content-Transfer-Encoding: 8bit',
    'X-Mailer: PHP/' . phpversion(),
];

$headerText = implode("\r\n", $headers);
$sent = function_exists('mb_send_mail')
    ? mb_send_mail($to, $subject, $body, $headerText)
    : mail($to, encodeHeader($subject), $body, $headerText);

if (!$sent) {
    respond(false, '送信に失敗しました。時間をおいて再度お試しください。', 500);
}

respond(true, '送信しました。');

function sanitizeHeader(string $value): string
{
    $value = preg_replace('/[\r\n]+/', ' ', $value) ?? '';
    return trim($value);
}

function encodeHeader(string $value): string
{
    if (function_exists('mb_encode_mimeheader')) {
        return mb_encode_mimeheader($value, 'UTF-8');
    }

    return '=?UTF-8?B?' . base64_encode($value) . '?=';
}

function textLength(string $value): int
{
    if (function_exists('mb_strlen')) {
        return mb_strlen($value);
    }

    return strlen($value);
}

function respond(bool $ok, string $message, int $status = 200)
{
    http_response_code($status);
    echo json_encode([
        'ok' => $ok,
        'message' => $message,
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
