<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';


function send_otp_email($to_email, $to_name, $otp)
{
    $mail = new PHPMailer(true);

    try {

        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'mokshadamahajan2008@gmail.com';
        $mail->Password   = 'zgqd pboa qzvl haps';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Sender
        $mail->setFrom(
            'YOUR_GMAIL@gmail.com',
            'Udyojika'
        );

        // Receiver
        $mail->addAddress($to_email, $to_name);

        // Email
        $mail->isHTML(true);

        $mail->Subject = 'Udyojika - Password Reset OTP';

        $mail->Body = "
            <div style='font-family: Arial, sans-serif;'>
                
                <h2 style='color:#6b1e2e;'>
                    Udyojika Password Reset
                </h2>

                <p>Hello <strong>" . htmlspecialchars($to_name) . "</strong>,</p>

                <p>
                    We received a request to reset your Udyojika account password.
                </p>

                <p>Your verification OTP is:</p>

                <h1 style='letter-spacing:8px; color:#6b1e2e;'>
                    {$otp}
                </h1>

                <p>
                    This OTP is valid for <strong>10 minutes</strong>.
                </p>

                <p>
                    If you did not request a password reset, you can safely ignore
                    this email.
                </p>

                <br>

                <p>
                    Regards,<br>
                    <strong>Udyojika Team</strong>
                </p>

            </div>
        ";

        $mail->AltBody =
            "Your Udyojika password reset OTP is: {$otp}. "
            . "This OTP is valid for 10 minutes.";

        $mail->send();

        return true;

    } catch (Exception $e) {

        return false;
    }
}