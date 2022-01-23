<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'userId' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'authKey' => $this->string(32),
            'passwordHash' => $this->string()->notNull(),
            'passwordResetToken' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'role' => $this->smallInteger()->notNull()->defaultValue(1),
            'status' => $this->smallInteger()->notNull()->defaultValue(9),
            'createdAt' => $this->bigInteger(),
            'updatedAt' => $this->bigInteger(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
