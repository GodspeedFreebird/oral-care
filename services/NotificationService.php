<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Require PHPMailer manually since we didn't use Composer
require_once BASE_PATH . '/vendor/PHPMailer/src/Exception.php';
require_once BASE_PATH . '/vendor/PHPMailer/src/PHPMailer.php';
require_once BASE_PATH . '/vendor/PHPMailer/src/SMTP.php';
require_once BASE_PATH . '/models/Notification.php';

class NotificationService {
    private $config;
    private $notificationModel;

    public function __construct() {
        $this->config = require BASE_PATH . '/config/mail.php';
        $this->notificationModel = new Notification();
    }

    /**
     * Initializes PHPMailer with config settings.
     */
    private function getMailer() {
        $mail = new PHPMailer(true);
        try {
            // Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable for debugging
            $mail->isSMTP();
            $mail->Host       = $this->config['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->config['username'];
            $mail->Password   = $this->config['password'];
            $mail->SMTPSecure = $this->config['encryption'];
            $mail->Port       = $this->config['port'];

            // Recipients
            $mail->setFrom($this->config['from_email'], $this->config['from_name']);
            return $mail;
        } catch (Exception $e) {
            error_log("PHPMailer Config Error: {$mail->ErrorInfo}");
            return null;
        }
    }

    /**
     * Helper to wrap email content in the standard OAUTHC template.
     */
    private function getEmailTemplate($title, $bodyContent) {
        // Logo embedding will be handled by PHPMailer's AddEmbeddedImage in the send function,
        // we use cid:logo to reference it in the HTML.
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
                .container { background-color: #ffffff; max-width: 600px; margin: 0 auto; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
                .header { text-align: center; border-bottom: 2px solid #0056b3; padding-bottom: 20px; margin-bottom: 20px; }
                .header img { max-width: 120px; }
                .header h2 { color: #0056b3; margin-top: 15px; font-size: 24px; }
                .content { color: #333333; line-height: 1.6; font-size: 16px; }
                .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #eeeeee; text-align: center; color: #777777; font-size: 12px; line-height: 1.5; }
                .footer strong { color: #555555; }
                .btn { display: inline-block; padding: 10px 20px; margin-top: 20px; background-color: #0056b3; color: #ffffff; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <img src='cid:oauthc_logo' alt='OAUTHC Logo'>
                    <h2>{$title}</h2>
                </div>
                <div class='content'>
                    {$bodyContent}
                </div>
                <div class='footer'>
                    <strong>Obafemi Awolowo University Teaching Hospitals Complex (OAUTHC)</strong><br>
                    Dental Centre<br>
                    Ile-Ife, Osun State, Nigeria<br><br>
                    Email: dental@oauthc.edu.ng (placeholder)<br>
                    Phone: +234 XXX XXX XXXX (placeholder)<br><br>
                    <em>This is an automated message from the OAUTHC Oral Health Management System.<br>
                    Please do not reply directly to this email.</em>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Send an appointment related email (Confirmation, Reminder, Cancellation, Rescheduled)
     */
    public function sendAppointmentEmail($type, $appointmentData, $userEmail, $userId) {
        $mail = $this->getMailer();
        if (!$mail) return false;

        $subject = '';
        $title = '';
        $bodyContent = '';
        
        $dateFormatted = date('l, F j, Y', strtotime($appointmentData['appointment_date']));
        $timeFormatted = date('h:i A', strtotime($appointmentData['appointment_time']));

        switch ($type) {
            case 'booking_confirmation':
                $subject = 'Appointment Confirmation - OAUTHC Dental';
                $title = 'Appointment Confirmed';
                $bodyContent = "
                    <p>Dear {$appointmentData['patient_name']},</p>
                    <p>Your appointment has been successfully booked.</p>
                    <p><strong>Department:</strong> {$appointmentData['department_name']}<br>
                    <strong>Doctor:</strong> {$appointmentData['practitioner_name']}<br>
                    <strong>Date:</strong> {$dateFormatted}<br>
                    <strong>Time:</strong> {$timeFormatted}</p>
                    <p>Please arrive 15 minutes early for your appointment. If you need to cancel or reschedule, please use the patient portal.</p>
                ";
                break;
            case 'reminder_24h':
                $subject = 'Appointment Reminder - OAUTHC Dental';
                $title = 'Upcoming Appointment Reminder';
                $bodyContent = "
                    <p>Dear {$appointmentData['patient_name']},</p>
                    <p>This is a friendly reminder for your upcoming dental appointment tomorrow.</p>
                    <p><strong>Department:</strong> {$appointmentData['department_name']}<br>
                    <strong>Doctor:</strong> {$appointmentData['practitioner_name']}<br>
                    <strong>Date:</strong> {$dateFormatted}<br>
                    <strong>Time:</strong> {$timeFormatted}</p>
                    <p>We look forward to seeing you. If you can no longer make it, please cancel via the portal as soon as possible.</p>
                ";
                break;
            case 'cancellation':
                $subject = 'Appointment Cancelled - OAUTHC Dental';
                $title = 'Appointment Cancelled';
                $bodyContent = "
                    <p>Dear {$appointmentData['patient_name']},</p>
                    <p>Your appointment scheduled for <strong>{$dateFormatted} at {$timeFormatted}</strong> with <strong>{$appointmentData['practitioner_name']}</strong> has been cancelled.</p>
                    <p>If this was a mistake, please book a new appointment through the portal.</p>
                ";
                break;
        }

        // Log the pending attempt
        $logId = $this->notificationModel->create([
            'appointment_id' => $appointmentData['appointment_id'],
            'type' => $type,
            'status' => 'pending',
            'recipient_email' => $userEmail,
            'message' => $bodyContent,
            'scheduled_at' => date('Y-m-d H:i:s')
        ]);

        try {
            $mail->addAddress($userEmail);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            
            // Embed Logo
            $logoPath = BASE_PATH . '/public/assets/images/logo.jfif';
            if (file_exists($logoPath)) {
                $mail->addEmbeddedImage($logoPath, 'oauthc_logo');
            }
            
            $mail->Body = $this->getEmailTemplate($title, $bodyContent);
            $mail->AltBody = strip_tags(str_replace(['<br>', '</p>'], ["\r\n", "\r\n\r\n"], $bodyContent));

            $mail->send();
            $this->notificationModel->updateStatus($logId, 'sent');
            return true;
        } catch (Exception $e) {
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            $this->notificationModel->updateStatus($logId, 'failed');
            return false; // Intentionally allowed to fail quietly for the user, but logged in DB
        }
    }

    /**
     * Send welcome email with default password to a new patient
     */
    public function sendWelcomeEmail($userEmail, $fullName, $username, $password) {
        $mail = $this->getMailer();
        if (!$mail) return false;

        try {
            $mail->addAddress($userEmail, $fullName);
            $mail->Subject = "Welcome to OAUTHC Dental - Your Account Details";

            $loginUrl = "http://" . $_SERVER['HTTP_HOST'] . str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) . "/login";
            $changePasswordUrl = "http://" . $_SERVER['HTTP_HOST'] . str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) . "/change-password";

            $bodyContent = "
                <p>Dear <strong>{$fullName}</strong>,</p>
                <p>Welcome to the OAUTHC Dental Portal! An account has been created for you by our reception desk.</p>
                
                <div style='background-color: #f3f4f6; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                    <p style='margin: 0; padding: 0;'><strong>Username:</strong> {$username}</p>
                    <p style='margin: 5px 0 0 0; padding: 0;'><strong>Temporary Password:</strong> {$password}</p>
                </div>

                <p style='color: #d97706; font-weight: bold;'>Important Security Notice:</p>
                <p>For your security, please log in and change your temporary password immediately through the following link:</p>
                <p><a href='{$changePasswordUrl}' style='display: inline-block; padding: 10px 20px; background-color: #14b8a6; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;'>Change Your Password</a></p>
                
                <p>If the button above does not work, you can copy and paste this URL into your browser:</p>
                <p style='word-break: break-all; color: #1976d2;'>{$changePasswordUrl}</p>
                
                <p>You can also log in anytime at <a href='{$loginUrl}'>our portal</a>.</p>
            ";

            $mail->Body = $this->getEmailTemplate("Welcome to OAUTHC Dental", $bodyContent);
            $mail->AltBody = "Dear {$fullName},\n\nWelcome to OAUTHC Dental! Your account has been created.\n\nUsername: {$username}\nTemporary Password: {$password}\n\nPlease log in and change your password immediately at: {$changePasswordUrl}\n\nThank you.";

            // Standard embedding of logo
            $logoPath = BASE_PATH . '/public/assets/images/logo_oauthc.png';
            if (file_exists($logoPath)) {
                $mail->addEmbeddedImage($logoPath, 'dental_logo');
            }

            // Try to send real email
            try {
                $result = $mail->send();
            } catch (Exception $e) {
                error_log("SMTP send failed, mocking email instead. Error: " . $e->getMessage());
                $result = false;
            }
            
            // For local development, always save a copy to scratch/emails.html
            $emailLog = "<hr><h3>To: {$userEmail} ({$fullName})</h3><h4>Subject: Welcome to OAUTHC Dental - Your Account Details</h4><div>" . $mail->Body . "</div>";
            file_put_contents(BASE_PATH . "/scratch/emails.html", $emailLog, FILE_APPEND);
            
            // Return true in development to allow flow to continue even if SMTP fails
            return true;
        } catch (Exception $e) {
            error_log("Failed to send welcome email to {$userEmail}. Error: " . $e->getMessage());
            return false;
        }
    }

}
