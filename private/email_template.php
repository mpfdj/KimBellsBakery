<?php
/**
 * E-mail templates voor het contactformulier.
 *
 * Alle variabelen die binnenkomen zijn al door htmlspecialchars() gehaald
 * in het aanroepende script, dus veilig om direct te echoën.
 */

function renderContactEmail(string $name, string $email, string $subject, string $message): string
{
    ob_start();
    ?>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; color: #333; }
            h2 { color: #c87a4f; }
            .info { background: #f5f0eb; padding: 15px; border-radius: 8px; }
            .label { font-weight: bold; color: #3d2c1b; }
        </style>
    </head>
    <body>
        <h2>Nieuw bericht van KimBells Bakery</h2>
        <div class='info'>
            <p><span class='label'>Naam:</span> <?= $name ?></p>
            <p><span class='label'>E-mail:</span> <a href='mailto:<?= $email ?>'><?= $email ?></a></p>
            <p><span class='label'>Onderwerp:</span> <?= $subject ?></p>
            <p><span class='label'>Bericht:</span><br><?= nl2br($message) ?></p>
        </div>
        <p style='font-size: 12px; color: #888;'>Dit bericht is verzonden via het contactformulier op de website.</p>
    </body>
    </html>
    <?php
    return ob_get_clean();
}

function renderContactEmailPlain(string $name, string $email, string $subject, string $message): string
{
    return "Nieuw bericht van KimBells Bakery\n\n"
         . "Naam: $name\n"
         . "E-mail: $email\n"
         . "Onderwerp: $subject\n\n"
         . "Bericht:\n$message";
}