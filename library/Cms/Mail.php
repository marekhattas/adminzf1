<?php

class Cms_Mail extends Zend_Mail
{

    private $_checkApplicationEnv = true;

    public function checkApplicationEnv($flag){
        $this->_checkApplicationEnv = $flag;
    }
    
    public function send($transport = null) {

        $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/plugins.ini',APPLICATION_ENV);

        if($transport == null){

            //var_dump($config->mail);exit;
            if(!empty($config->mail->transport)){
                $c = array(
                'ssl' => $config->mail->transport->ssl,
                'port' => $config->mail->transport->port,
                'auth' => $config->mail->transport->auth,
                'username' => $config->mail->transport->username,
                'password' => $config->mail->transport->password
                );
                $transport = new Zend_Mail_Transport_Smtp($config->mail->transport->host, $c);
            }
        }

        //deafult Recipient
        $recipients = $this->getRecipients();
        if(count($recipients) == 0){
            $this->addTo($config->mail->default->addTo);
        }

        //deafult From
        $from = $this->getFrom();
        if(empty($from)){
            $this->setFrom($config->mail->default->from, $config->mail->default->fromName);
        }

        //signature
        $model = new Admin_Model_Emaillog();
        $mime = $this->getBodyHtml();
        if($mime != false){
            $text = $mime->getRawContent();
            $text .= $config->mail->default->signature;
            $this->setBodyHtml($text);
        }else{
            $mime = $this->getBodyText();
            $this->_bodyText = false;
            $text = $mime->getRawContent();
            $text = nl2br($text);
            $text .= $config->mail->default->signature;
            $this->setBodyHtml($text);
        }


        $id = $model->insert(
                array('subject'=>$this->getSubject(),
                      'email'=>array_shift($this->getRecipients()),
                      'text' => $text,
                      'from' => $this->getFrom(),
                      'env'  => APPLICATION_ENV
                    ));
        if(APPLICATION_ENV == 'production' or $this->_checkApplicationEnv == false){
            $email = parent::send($transport);
            $model->update(array('response'=>'OK'),'id = "'.$id.'"');
            return $email;
        }

        return $this;


    }

}