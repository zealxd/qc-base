<?php

use yii\db\Schema;
use yii\db\Migration;

class m150227_152353_init_app extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=MyISAM';
        }

        $this->createTable('{{%admin}}', [
            'user_id' => Schema::TYPE_PK . ' COMMENT \'用户ID\'',
            'username' => Schema::TYPE_STRING . ' NOT NULL DEFAULT \'\' COMMENT \'用户名\'',
            'email' => Schema::TYPE_STRING . ' NOT NULL DEFAULT \'\' COMMENT \'邮箱\'',
            'frontend_user_id' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0 COMMENT \'绑定前台用户的ID\'',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL DEFAULT \'\' COMMENT \'身份验证密钥,保证cookie安全\'',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL DEFAULT \'\' COMMENT \'加盐的密码\'',
            'password_reset_token' => Schema::TYPE_STRING . ' NOT NULL DEFAULT \'\' COMMENT \'重置密码token\'',

            'status' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0 COMMENT \'状态,启用为1禁用为0\'',
            'login_times' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0 COMMENT \'登录次数\'',
            'login_error_times' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0 COMMENT \'登录失败次数\'',
            'last_login_ip' => Schema::TYPE_INTEGER . '(10) NOT NULL DEFAULT 0 COMMENT \'最后登录ip地址\'',
            'last_login_time' => Schema::TYPE_INTEGER . '(10) NOT NULL DEFAULT 0 COMMENT \'最后登录时间\'',
            'last_modify_password_time' => Schema::TYPE_INTEGER . '(10) NOT NULL DEFAULT 0 COMMENT \'最后修改密码时间\'',
            'create_time' => Schema::TYPE_INTEGER . '(10) NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
        ], $tableOptions . ' COMMENT=\'后台管理员表\'');

        //新增一个超级管理员
        $this->insert('{{%admin}}', [
            'username' => 'admin',
            'email' => 'admin@u-bo.com',
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash('admin'),
            'status' => 1,
            'create_time' => time(),
        ]);

    }

    public function down()
    {
        $this->dropTable('{{%admin}}');
    }
}
