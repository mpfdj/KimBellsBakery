<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'load_env.php';
require_once __DIR__ . '/private/email_template.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include de autoloader van Composer
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ============================================================
    // 🔍 HONEYPOT CHECK - website
    // ============================================================
    // Als het honeypot veld "website" is ingevuld, dan is het een bot!
    if (!empty($_POST['website'])) {
        // Doe alsof het gelukt is (bot denkt dat het werkt)
        header("Location: contact.php?status=success&message=" . urlencode("Bedankt! Je bericht is verzonden."));
        exit;
    }


    // ============================================================
    // 🔍 HONEYPOT CHECK - checkbox
    // ============================================================
    // Als de honeypot checkbox "newsletter" is aangevinkt, dan is het een bot!
    // Echte gebruikers kunnen deze checkbox niet zien, dus ze kunnen hem ook niet aanvinken.
    if (isset($_POST['newsletter']) && $_POST['newsletter'] == '1') {
        // Doe alsof het gelukt is (bot denkt dat het werkt)
        header("Location: contact.php?status=success&message=" . urlencode("Bedankt! Je bericht is verzonden."));
        exit;
    }


    // ============================================================
    // 🔍 TIMESTAMP CHECK
    // ============================================================
    // Check of het formulier niet te snel is ingevuld (minder dan 3 seconden)
    if (isset($_POST['form_timestamp']) && (time() - $_POST['form_timestamp']) < 3) {
        header("Location: contact.php?status=error&message=" . urlencode("Formulier is te snel ingevuld."));
        exit;
    }


    // ============================================================
    // 📝 Normale formulier verwerking na bovenstaande checks
    // ============================================================

    // 1. Ontvang en valideer de formulierdata
    $name    = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email   = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(trim($_POST['subject'] ?? ''));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));

// echo "<pre>";
// var_dump($_POST);
// echo "</pre>";
// exit;



    // Eenvoudige validatie
    $errors = [];
    if (empty($name)) {
        $errors[] = "Naam is verplicht.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Voer een geldig e-mailadres in.";
    }
    if (empty($subject)) {
        $errors[] = "Onderwerp is verplicht.";
    }
    if (empty($message)) {
        $errors[] = "Bericht is verplicht.";
    }

    if (!empty($errors)) {
        $errorMsg = urlencode(implode(" ", $errors));
        header("Location: contact.php?status=error&message=" . $errorMsg);
        exit;
    }

    // 2. Configureer PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Mail gegevens
        $mail->isSMTP();
        $mail->Host       = 'mail.miel-de-jaeger.nl';           // Outgoing server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'info@miel-de-jaeger.nl';           // Jouw e-mail
        $mail->Password   = getenv('SMTP_PASSWORD');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;        // SSL encryptie
        $mail->Port       = 465;                                // SMTP poort voor SSL

        // Afzender en ontvanger
        $mail->setFrom('info@miel-de-jaeger.nl', 'KimBell\'s Contact formulier');
        $mail->addAddress('mpf.dejaeger@gmail.com', 'MPF de Jaeger');
        $mail->addReplyTo($email, $name);                       // Reply-to: de bezoeker

        // Inhoud
        $mail->isHTML(true);
        $mail->Subject = 'Contactformulier: ' . $subject;

        // HTML-body en plain-text body uit template
        $mail->Body    = renderContactEmail($name, $email, $subject, $message);
        $mail->AltBody = renderContactEmailPlain($name, $email, $subject, $message);

        // Verstuur de e-mail
        $mail->send();
        $successMsg = urlencode("Bedankt $name! Je bericht is succesvol verzonden. We nemen zo snel mogelijk contact met je op.");
        header("Location: contact.php?status=success&message=" . $successMsg);
        exit;

    } catch (Exception $e) {
        $errorMsg = urlencode("Er is een fout opgetreden: " . $mail->ErrorInfo);
        header("Location: contact.php?status=error&message=" . $errorMsg);
        exit;
    }
} else {
    // Als iemand direct deze pagina opent zonder POST-data
    header("Location: contact.php");
    exit;
}
?>