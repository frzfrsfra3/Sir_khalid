<?php
class User extends CActiveRecord {
    public $username;
    public $email;
    public $password;
    public $status;
    public $verify_token;

    // Other model methods and rules

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'user';
    }
    public function relations() {
        return array(
            'likes' => array(self::HAS_MANY, 'Like', 'user_id'),
            'blogPosts' => array(self::HAS_MANY, 'BlogPost', 'user_id'),
        );
    }
    public function getIsVerified()
    {
        // Replace this with your actual verification logic
        return $this->status;
    }
    
    public function rules() {
        return array(
            array('username, email, password', 'required'),
            array('email', 'email'),
            array('email, username', 'unique'),
            array('password', 'length', 'min' => 6),
            array('status', 'in', 'range' => array(0, 1)),
            array('verify_token', 'length', 'max' => 128),
        );
    }
}
