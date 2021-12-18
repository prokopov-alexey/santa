<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\db\User;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $key;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['key'], 'required'],
            [['key'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['key' => 'secret_id']],
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $user = User::findIdentityByAccessToken($this->key);
            if ($user) {
                return Yii::$app->user->login($user, 3600*24*30);
            } else {
                return false;
            }
        }
        
        return false;
    }
}
