<?php

include('PHPMailer-master/PHPMailerAutoload.php');

function forget_mail($email,$token)
{
    
    $send_email = $email;
    $send_token = $token;
    $mail = new PHPMailer;
    $mail->SMTPDebug = 1;
    $mail->isSMTP();
    $mail->Host = "4slash.com";
    $mail->SMTPAuth = true;
    $mail->Username = "ikram@4slash.com";
    $mail->Password = "4Slash1234!@#$";
    $mail->Port = 587;
    $mail->setFrom("ikram@4slash.com", "Sprout");
    $mail->AddAddress($send_email); 

    //Send HTML or Plain Text email
    $mail->isHTML(true);

    // <tr>
    // <td style='text-align:center'>
    // <img src='".$_SERVER['HTTP_HOST']."/img/pass-head.png' width='100%'>
    // </td>
    // </tr>
    // <p style='padding-bottom:30px;'><img src='".$_SERVER['HTTP_HOST']."/img/sprout01.png'></p>

    $mail->Subject = "Sprout - Reset Password";
    $mail->Body = "<div class='container' style='width: 600px; background-color: #f8f8f8'><table width='100%'>
        <tbody>
        <tr>
        <td style='text-align: center'><img src='https://4slash.com/sprout/img/sprout01.png' width='14%'></td>
        <td>
        <p><b>24/7 Support: +923322323883</b></p>
        <p>Dont worry we've got you covered</p>
        </td>
        </tr>
        </tbody>
        </table>
        <table style='width:100%'>
        <tbody>
        <tr>
        <td style='padding-bottom:40px; padding-left:10px;font-family:Lato, sans-serif; padding-top:60px;'>
        <p>Simply click below link to update your password</p>
        </td>
        </tr>
        <tr>
        <td style='padding-bottom:40px; padding-left:10px;font-family:Lato, sans-serif'>
        <a style='background-color: #90b833;padding: 15px;border-radius: 5px; text-decoration:none;color:white' href='".$_SERVER['HTTP_HOST']."/resetpassword.php?email=".$email."&&token=".$token."'>CHANGE PASSWORD NOW</a></td>
        </tr>
        <td style='padding-bottom:40px; padding-left:10px;font-family:Lato, sans-serif;'>
        <p>Still have questions? Our dedicated team of specialists is here to help. Call: +923322323883 or email us at info@4slash.com</p>
        </td>
        </tr>
        </tbody>
        </table>
        <table style='width:100%'>
        <tbody>
        <tr style='width:100%'>
        <td style='background-color:#BEBEBE; padding:20px 10px;'>
        <p style='padding-bottom:30px;'>Please do not reply to this email. Emails sent to this address will not be answered. </p>
        <p style=';font-family:Lato, sans-serif;'>Copyright © 2023.All rights reserved</p>
        </td>
        </tr></tbody>
        </table>
        </div>";

    $mail->AltBody = "This is the plain text version of the email content";
     
    if (!$mail->send()) {
        echo "Couldnt send email";
        echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
    } else {
        
        echo "Email has been sent successfully!";
        return true;
    }
   
}




function forget_mail2($email,$token)
{
    $send_email = $email;
    $send_token = $token;
    $mail = new PHPMailer;
    $mail->SMTPDebug = 1;
    $mail->isSMTP();
    $mail->Host = "4slash.com";
    $mail->SMTPAuth = true;
    $mail->Username = "ikram@4slash.com";
    $mail->Password = "4Slash1234!@#$";
    $mail->Port = 587;
    $mail->setFrom("ikram@4slash.com", "Sprout");
    $mail->AddAddress($send_email); 
    //Send HTML or Plain Text email
    $mail->isHTML(true);
    $mail->Subject = "Sprout - Reset Password";
    $mail->Body = "<p>Test Email</p>";
    $mail->AltBody = "This is the plain text version of the email content";
     
    if (!$mail->send()) {
        echo "Couldnt send email";
        echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
    } else {
        echo "Email has been sent successfully!";
        return true;
    }
}