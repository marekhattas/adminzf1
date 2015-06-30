<?php
class Blog_Model_Contacts extends Cms_DbTable
{
    /** Table name */
    protected $_name    = 'blog_contacts';


    public function mail($post){
      $form = $this->getForm();
            if ($form->isValid($post) and empty($post['iamrobot']) and $post['iamhuman'] == 'ano') {
                $data = $form->getValues();
                $text = $data['name']."\n".$data['email']."\n".$data['phone']."\n".$data['message']."\n\n ip:".$_SERVER['REMOTE_ADDR'];

                $mail = new Cms_Mail('UTF-8');
                $mail->setSubject("Kontaktný formulár - ".$_SERVER["HTTP_HOST"]);
                $mail->setBodyText($text);
                $mail->setFrom("maximed.sk@gmail.com","Maximed");

                $row = $this->getRow(19);
                $mail->addTo($row->email);

                return $mail->send();
            }
      return false;
    }


    public function getForm(){
        $form = new Zend_Form();

        $elm = new Zend_Form_Element_Text('name');
        $elm->setAttrib('size', '30');
        $form->addElement($elm);

        $elm = new Zend_Form_Element_Text('email');
        $elm->setAttrib('size', '30');
        $form->addElement($elm);

        $elm = new Zend_Form_Element_Text('phone');
        $elm->setAttrib('size', '30');
        $form->addElement($elm);

        $elm = new Zend_Form_Element_Textarea('message');
        $elm->setAttrib('cols', '45');
        $elm->setAttrib('rows', '14');
        $form->addElement($elm);

        $elm = new Zend_Form_Element_Text('iamrobot');
        $elm->setAttrib('size', '30');
        $form->addElement($elm);

        $elm = new Zend_Form_Element_Text('iamhuman');
        $elm->setAttrib('size', '30');
        //$elm->setValue('ano');
        $form->addElement($elm);

        return $form;
    }

}
