<?php
/**
 * @link http://www.u-bo.com
 * @copyright 南京友博网络科技有限公司 
 * @license http://www.u-bo.com/license/
 */

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * 公共登录表单模型
 *
 * @property string $username 用户名
 * @property string $password 密码
 * @property boolean $rememberMe 是否记住我
 * @property string $userClass 用户模型类名
 *
 * @author legendjw <legendjww@gmail.com>
 * @since 0.1
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = false;

    public $userClass = 'User';
    private $_user = false;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
        ];
    }

    /**
     * 验证密码是否正确
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '用户名或者密码错误！');
            }
        }
    }

    /**
     * 执行登录
     *
     * @return boolean 是否登录成功
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * 通过[[username]]查找用户
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $userClass = $this->userClass;
            $this->_user = $userClass::findByUsername($this->username);
        }

        return $this->_user;
    }
}
