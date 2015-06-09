<?php
/**
 * @link http://www.u-bo.com/
 * @copyright 南京友博网络科技有限公司 
 * @license http://www.u-bo.com/license/
 */

namespace backend\models;

use Yii;
use common\models\ActiveRecord;
use yii\web\IdentityInterface;
use common\models\IdentityTrait;
use yii\behaviors\AttributeBehavior;

/**
 * 后台管理员模型，对应表"{{%admin}}"
 *
 * @property integer $used_id 用户ID
 * @property string $username 用户名
 * @property string $email 邮箱
 * @property integer $frontend_user_id 绑定前台用户的ID
 * @property string $auth_key 身份验证密钥,保证cookie安全
 * @property string $password_hash 加盐的密码
 * @property string $password_reset_token 重置密码token
 * @property integer $status 状态,启用为1禁用为0
 * @property integer $login_times 登录次数
 * @property integer $login_error_times 登录失败次数
 * @property integer $last_login_ip 最后登录ip地址
 * @property integer $last_login_time 最后登录时间
 * @property integer $last_modify_password_time 最后修改密码时间
 * @property integer $create_time 创建时间
 *
 * @author legendjw <legendjww@gmail.com>
 * @since 0.1
 */
class Admin extends ActiveRecord implements IdentityInterface
{
    use IdentityTrait;
    /**
     * @var string 获取用户输入的密码
     */
    public $password;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin}}';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->on(self::EVENT_BEFORE_INSERT, [$this, 'generatePassword']);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [self::EVENT_BEFORE_INSERT => 'create_time'],
                'value' => function ($event) {
                    return time();
                }
            ],
        ];
    }

    /**
     * @inheritdoc
     *
     * 管理员模型共有3个场景:
     *
     * - 新增用户:必须要填写密码
     * - 编辑用户:可以不填写密码
     * - 修改密码:只要激活用户和密码字段
     */
    public function scenarios()
    {
        $scenario = parent::scenarios();
        $scenario['create'] = ['username', 'password', 'email', 'status'];
        $scenario['update'] = ['username', 'email', 'status'];
        $scenario['resetPwd'] = ['username', 'password'];
        return $scenario;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'trim'],
            [['username', 'email', 'status'], 'required'],
            [['username', 'email'], 'unique'],
            ['email', 'email'],
            ['password', 'required', 'on' => ['create', 'resetPwd']],
            ['password', 'string', 'min' => 6, 'max' => 32],
            [['frontend_user_id', 'status', 'login_times', 'login_error_times', 'last_login_ip', 'last_login_time', 'last_modify_password_time', 'create_time'], 'integer'],
            [['username', 'email', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'ID',
            'username' => '用户名',
            'email' => '邮箱',
            'frontend_user_id' => '绑定前台用户的ID',
            'auth_key' => '身份验证密钥,保证cookie安全',
            'password' => '密码',
            'password_hash' => '加盐的密码',
            'password_reset_token' => '重置密码token',
            'status' => '状态',
            'login_times' => '登录次数',
            'login_error_times' => '登录失败次数',
            'last_login_ip' => '最后登录ip地址',
            'last_login_time' => '最后登录时间',
            'last_modify_password_time' => '最后修改密码时间',
            'create_time' => '创建时间',
        ];
    }

    /**
     * @inheritdoc
     */
    public function listAttributes()
    {
        return [
            'user_id' => [
                'width' => '5%',
            ],
            'username' => [
                'width' => '10%',
            ],
            'frontend_user_id' => [
                'label' => '前台用户',
                'width' => '10%',
                'handle' => ['empty', ['default' => '未绑定']],
            ],
            'email' => [
                'width' => '10%',
            ],
            'login_times' => [
                'label' => '登录次数/错误次数',
                'width' => '10%',
                'handle' => ['join', ['default' => '未登录过', 'joinFields' => ['login_error_times']]],
            ],
            'last_login_time' => [
                'label' => '最后登录时间/IP',
                'width' => '10%',
                'handle' => function ($field, $model) {
                    return empty($model->$field) ? '未登录过' : date('Y-m-d H:i:s', $model->$field) . '/' . $model->last_login_ip;
                },
            ],
            'last_modify_password_time' => [
                'width' => '10%',
                'handle' => ['date', ['default' => '未修改过']],
            ],
            'create_time' => [
                'width' => '10%',
                'handle' => ['date', ['default' => '未知']],
            ],
            'status' => [
                'width' => '5%',
                'handle' => ['map', ['mapData' => static::getStatusItems()]],
            ],
            'operation' => [
                'label' => '操作',
                'width' => '10%',
                'handle' => ['operation'],
            ],
        ];

    }

    /**
     * @inheritdoc
     */
    public function controlAttributes()
    {
        return [
            [
                'id' => 'basic',
                'name' => '基本属性',
                'attributes' => [
                    'username' => [
                        'type' => 'text',
                    ],
                    'password' => [
                        'type' => 'password',
                        'hint' => '新增用户时必须填写密码，修改用户时如果为空则不修改，否则则修改密码。',
                    ],
                    'email' => [
                        'type' => 'text',
                    ],
                    'status' => [
                        'type' => 'dropDown',
                        'items' => static::getStatusItems(),
                    ],
                ]
            ]
        ];
    }

    /**
     * 生成密码
     *
     * @param Event $event 模型事件
     */
    public function generatePassword($event)
    {
        if (!empty($this->password)) {
            $this->setPassword($this->password);
            //更新修改密码时间
            if ($this->scenario == 'update') {
                $this->last_modify_password_time = time();
            }
        }

        $this->generateAuthKey();
    }

}
