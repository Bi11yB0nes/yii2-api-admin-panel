<?php

namespace api\modules\v1\models\user;

use yii\base\Model;
use common\models\AccessToken;
use common\models\User;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $email;
    public $password;

    private $_email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['email', 'password'], 'required'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect email or password.');
            }
        }
    }

    /**
     * @return AccessToken|null
     */
    public function login()
    {
        if (!$this->validate()) {
            return null;
        }
        $userId = $this->getUser()->userId;
        $token = AccessToken::findByUserId($userId);
        if (!$token) {
            $token = new AccessToken();
        }
        $token->userId = $userId;
        $token->generateToken();
        return $token->save() ? $token : null;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_email === null) {
            $this->_email = User::findByEmail($this->email);
        }

        return $this->_email;
    }
}
