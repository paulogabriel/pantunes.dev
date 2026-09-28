<?php
declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['ok' => false, 'error' => 'Método não permitido.']));
}

header('Content-Type: application/json; charset=utf-8');

// ── Rate limiting: 5 submissions per IP every 10 minutes ──
session_start();
$now    = time();
$window = 600;
$limit  = 5;
if (!isset($_SESSION['mail_attempts'])) {
    $_SESSION['mail_attempts'] = [];
}
$_SESSION['mail_attempts'] = array_filter(
    $_SESSION['mail_attempts'],
    fn($t) => ($now - $t) < $window
);
if (count($_SESSION['mail_attempts']) >= $limit) {
    http_response_code(429);
    exit(json_encode(['ok' => false, 'error' => 'Muitas tentativas. Aguarde alguns minutos.']));
}

// ── Honeypot ──
if (!empty($_POST['website'])) {
    http_response_code(200);
    exit(json_encode(['ok' => true]));
}

// ── Sanitize and validate ──
function clean(string $val): string {
    return trim(strip_tags($val));
}

$name    = clean($_POST['name']    ?? '');
$email   = clean($_POST['email']   ?? '');
$message = clean($_POST['message'] ?? '');

$errors = [];
if (strlen($name) < 2)                         $errors[] = 'Nome obrigatório.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'E-mail inválido.';
if (strlen($message) < 10)                     $errors[] = 'Mensagem muito curta.';

if ($errors) {
    http_response_code(422);
    exit(json_encode(['ok' => false, 'error' => implode(' ', $errors)]));
}

// ── PHPMailer ──
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$cfg = require __DIR__ . '/../config/mail.php';

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = $cfg['host'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $cfg['username'];
    $mail->Password   = $cfg['password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = $cfg['port'];
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom($cfg['from_email'], $cfg['from_name']);
    $mail->addAddress($cfg['to_email'], $cfg['to_name']);
    $mail->addReplyTo($email, $name);

    $mail->Subject = "pantunes.dev — $name";

    $mail->isHTML(true);
    $mail->Body = "
<p><strong>Nome:</strong> " . htmlspecialchars($name) . "</p>
<p><strong>E-mail:</strong> " . htmlspecialchars($email) . "</p>
<p><strong>Mensagem:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
";
    $mail->AltBody = "Nome: $name\nE-mail: $email\n\n$message";

    $mail->send();

    $_SESSION['mail_attempts'][] = $now;
    exit(json_encode(['ok' => true]));

} catch (Exception $e) {
    http_response_code(500);
    exit(json_encode(['ok' => false, 'error' => 'Erro ao enviar. Tente novamente ou entre em contato pelo WhatsApp.']));
}
