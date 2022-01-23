<?php

use common\models\User;
use yii\db\Migration;

/**
 * Class m220122_185250_add_admin_to_user_table
 */
class m220122_185250_add_admin_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $now =(new \DateTimeImmutable())->getTimestamp();
        $email = \Yii::$app->params['adminDefaultEmail'];
        $this->insert('user', array(
            'username' => stristr($email, '@', true),
            'email' => $email,
            'passwordHash' => \Yii::$app->security->generatePasswordHash(\Yii::$app->params['adminDefaultPassword']),
            'status' => User::STATUS_ACTIVE,
            'role' => User::ROLE_ADMIN,
            'createdAt' => $now,
            'updatedAt' => $now,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220122_185250_add_admin_to_user_table cannot be reverted.\n";

        return false;
    }
    */
}
