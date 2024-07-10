<?php 
class RegisterForm extends CFormModel
{
    public $username;
    public $password;
    public $email;
    public $verifyPassword;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('username, password, email, verifyPassword', 'required'),
            array('email', 'email'),
            array('username, email', 'unique', 'className' => 'User'),
            array('password', 'compare', 'compareAttribute' => 'verifyPassword'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'username' => 'Username',
            'password' => 'Password',
            'verifyPassword' => 'Verify Password',
            'email' => 'Email',
        );
    }
}
