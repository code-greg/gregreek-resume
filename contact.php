<?php
header('Content-Type: application/json');

// Inclua o autoload do Composer ou o arquivo PHPMailer manualmente
require_once 'vendor/autoload.php'; // Se usou composer
// require 'PHPMailer/src/PHPMailer.php'; // Se baixou manualmente
require_once 'config_email.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if (!$name || !$email || !$subject || !$message) {
    echo json_encode(['success' => false, 'error' => 'Todos os campos são obrigatórios.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'error' => 'E-mail inválido.']);
    exit;
}

$mail = new PHPMailer(true);

try {
    // Configurações do servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $email_user;
    $mail->Password = $email_pass;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Remetente e destinatário
    $mail->setFrom($email, $name);
    $mail->addAddress('gregbcontato@gmail.com');

    // Conteúdo do e-mail
    $mail->Subject = $subject;
    $mail->Body    = "Nome: $name\nE-mail: $email\nAssunto: $subject\nMensagem:\n$message";

    $mail->send();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Erro ao enviar: ' . $mail->ErrorInfo]);
}

?>
