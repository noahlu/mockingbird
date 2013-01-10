<?php 

class MailHandler {
    private $from;

    function send ($to, $subject, $content) {
        $this->date = date("Y年m月d日");
        $this->from = "noreply@". $_SERVER['HTTP_HOST'];
        $subject = "=?UTF-8?B?". base64_encode($subject). "?=";
        $header = "MIME-Version: 1.0 \r\n";
        $header .= "Content-type: text/html; charset=utf-8 \r\n";
        $header .= "To: $to \r\n";
        $header .= "From: ". $this->from . "\r\n";

        $result = mail($to, $subject, $content, $header);
        return $result;
    }
    
}
?>
