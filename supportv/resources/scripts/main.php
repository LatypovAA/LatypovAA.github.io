<?php
include 'send_mail.php';
class Main
{

    private $type = 'html';
    private $charset = 'utf-8';

    public function __construct()
    {
        $optJson = array('options' => function($value){ return json_decode($value, true);});
        $this->msg = filter_input(INPUT_POST, 'msg', FILTER_CALLBACK, $optJson);
        if(!isset($this->msg)){
            throw new Exception("No message param");
        }
        $this->config = $this->get_config();
    }
    
    public function send()
    {
        $result = array(
            'success'=>true
        );
        $send_mail = new Send_mail();
        
        $flag = $send_mail->email($this->get_recipient_msg())  // Адресат
          ->from_name($this->msg['user'])  // Имя отправителя
          ->from_mail($this->get_sender_msg())   // Адрес отправителя
          ->subject($this->get_title_msg())  // Тема сообщения
          ->message($this->get_text_msg()) // Тело сообщения
//          ->files($files) // Путь до прикрепляемого файла (можно массив)
          ->charset($this->charset) // Кодировка (по умолчанию utf-8)
          ->content_type($this->type)  // тип сообщения (по умолчанию 'plain')
          ->send(); // Отправка почты  

        if(!$flag){
            $result['success'] = false;
            $result['error'] = $flag;
        } 
        
        return $result;
    }
    
    private function get_sender_msg()
    {
        $email = ($this->msg['email'])? $this->msg['email'] : $this->config['default_email'];
        if(!isset($email)){
            throw new Exception("No email sender");
        }
        return $email;
    }
    
    private function get_recipient_msg()
    {
        $email = $this->config['subPlans'][$this->msg['plan']];
        if(!isset($email)){
            throw new Exception("No email recipient");
        }
        return $email;
    }
    
    private function get_title_msg()
    {
        return sprintf('%s: %s - %s', $this->msg['user'], $this->msg['unit']['nm'], $this->msg['modul']);
    }
    
    private function get_text_msg()
    {
        $format = 'Модуль: %s\r\n\r\n'
                . 'Пользователь: %s\r\n\r\n'
                . 'Объект: %s\r\n\r\n'
                . '%s';
        
        return sprintf($format, $this->msg['modul'], $this->msg['user'], $this->msg['unit']['nm']);
    }
    
    private function get_config()
    {
        $content = file_get_contents('../data/config-email.json');
        return json_decode($content, true);
    }
    
}
?>