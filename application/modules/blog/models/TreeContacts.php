<?php
class Application_Model_TreeContacts extends Cms_DbTable
{
    /** Table name */
    protected $_name    = 'tree_contacts';

    public function mail($post){
      $form = $this->getForm();
            if ($form->isValid($post)) {
                $data = $form->getValues();
                $text = $data['name']."\n".$data['email']."\n".$data['phone']."\n".$data['message']."\n\n";
                $mail = new Zend_Mail('UTF-8');
                $mail->setSubject("Kontaktný formulár - ".$_SERVER["HTTP_HOST"]);
                $mail->setBodyText($text);
                $mail->setFrom($data['email']);
                $row = $this->find(68);
                $mail->addTo($row[0]['email']);
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

        return $form;
    }

}
