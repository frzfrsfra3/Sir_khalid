<?php 

class UserIdentity extends CUserIdentity {
    private $_id;

    // public function authenticate() {
    //     $user = User::model()->findByAttributes(array('username' => $this->username));
    //     if ($user === null) {
    //         $this->errorCode = self::ERROR_USERNAME_INVALID;
    //     } elseif (!CPasswordHelper::verifyPassword($this->password, $user->password)) {
    //         $this->errorCode = self::ERROR_PASSWORD_INVALID;
    //     } elseif ($user->status == 0) {
    //         $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
    //         Yii::app()->user->setFlash('error', 'Your account is not verified.');
    //     } else {
    //         $this->_id = $user->id;
    //         $this->setState('username', $user->username);
    //         $this->errorCode = self::ERROR_NONE;
    //     }
    //     return !$this->errorCode;
    // }
    

    public function authenticate()
    {
        $user = User::model()->findByAttributes(array('username' => $this->username));
        if ($user === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if ($user->password !== md5($this->password)) {  // Ensure the password comparison is correct
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->_id = $user->id;
            $this->username = $user->username;
            $this->errorCode = self::ERROR_NONE;
        }
        return !$this->errorCode;
    }

    public function getId() {
        return $this->_id;
    }
}
