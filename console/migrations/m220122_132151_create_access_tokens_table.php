<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%access_tokens}}`.
 */
class m220122_132151_create_access_tokens_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%accessToken}}', [
            'accessTokenId' => $this->primaryKey(),
            'userId' => $this->integer()->notNull(),
            'accessToken' => $this->string()->notNull()->unique(),
        ]);

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-token-user-id',
            'accessToken',
            'userId',
            'user',
            'userId',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%accessToken}}');
    }
}
