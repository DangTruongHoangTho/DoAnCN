<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';


function sendOTP($toEmail, $otp) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();                                            
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = 'thodang2003@gmail.com';
        $mail->Password   = 'nubm ivnz clcv lxcj';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         
        $mail->Port       = 587;                                    

        $mail->setFrom('thodang2003@gmail.com ', 'T&T Store');
        $mail->addAddress($toEmail);   

        $mail->isHTML(true);                                
        $mail->Subject = "Ma xac nhan OTP";
        $mail->Body    = "Mã OTP của bạn là: <strong>$otp</strong>. Vui lòng không chia sẻ với bất kỳ ai.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Lỗi khi gửi email: {$mail->ErrorInfo}";
    }
}
function generateOTP($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $otp = substr(str_shuffle($characters), 0, $length);

    return $otp;
}


?>
