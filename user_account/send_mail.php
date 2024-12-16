<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include thư viện PHPMailer
require '../vendor/autoload.php';


function sendOTP($toEmail, $otp) {
    $mail = new PHPMailer(true);

    try {
        // Cấu hình Server Email (SMTP)
        $mail->isSMTP();                                            
        $mail->Host       = 'smtp.gmail.com'; // SMTP của Gmail
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = 'thodang2003@gmail.com'; // Email của bạn
        $mail->Password   = 'nubm ivnz clcv lxcj';   // Mật khẩu ứng dụng Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         
        $mail->Port       = 587;                                    

        // Người gửi và người nhận
        $mail->setFrom('thodang2003@gmail.com ', 'T&T Store');
        $mail->addAddress($toEmail);   

        // Nội dung email
        $mail->isHTML(true);                                
        $mail->Subject = "Ma xac nhan OTP";
        $mail->Body    = "Mã OTP của bạn là: <strong>$otp</strong>. Vui lòng không chia sẻ với bất kỳ ai.";

        // Gửi email
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
