<?php
class Admin_Model_Users extends Cms_DbTable
{
    /** Table name */
    protected $_name    = 'admin_users';



    public function doAuth($loginName,$password)
    {
        $name = $this->_name;

        // setup Zend_Auth adapter for a database table
        $authAdapter = new Zend_Auth_Adapter_DbTable($this->getDefaultAdapter());
        $authAdapter->setTableName($name);
        $authAdapter->setIdentityColumn('email');
        $authAdapter->setCredentialColumn('password');

        // Set the input credential values to authenticate against
        $authAdapter->setIdentity($loginName);
        $authAdapter->setCredential($this->getCryptPassword($password));

        // do the authentication
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($authAdapter);

        if ($result->isValid()) {
            // success: store database row to auth's storage
            // system. (Not the password though!)
            $data = $authAdapter->getResultRowObject(null,'password');
            $data->admin = true;
            $auth->getStorage()->write($data);

            return true;
        }
        else{
            return false;
        }


    }

    public function getAuth(){
        $auth = Zend_Auth::getInstance();
        return $auth;
    }

    public function isAuth(){
        $auth = Zend_Auth::getInstance();
        if($auth->hasIdentity() and @$auth->getIdentity()->admin != true){
          return false;
        }
        return $auth->hasIdentity();
    }

    public function clearAuth(){
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
    }


    public function getData()
    {
        $auth = Zend_Auth::getInstance();
        return $auth->getIdentity();
    }

    public function getUser()
    {
        $auth = Zend_Auth::getInstance();
        return $auth->getIdentity();
    }


    public function update(array $data,$where){

      if(!empty($data['password'])){
        $data['password'] = $this->getCryptPassword($data['password']);
      }
      elseif(isset($data['password'])){
        unset($data['password']);
      }

      return parent::update($data,$where);
    }

    public function insert(array $data){

      $data['password'] = $this->getCryptPassword($data['password']);
      return parent::insert($data);
    }



    public function getCryptPassword($password){
        return sha1($password."VelmiDlheZnakyKtoreBraniaHacku+%%");
    }
}