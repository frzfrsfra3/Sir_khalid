<?php
class WebUser extends CWebUser
{
    private $_model;

    // Retrieve the status of the logged-in user
    function getStatus()
    {
        if ($this->isGuest) {
            return null;
        }

        if ($this->_model === null) {
            $this->_model = User::model()->findByPk($this->id);
        }

        return $this->_model ? $this->_model->status : null;
    }
    public function getIsVerified()
    {
        // Replace this with your actual verification logic
        return $this->status;
    }
}
