<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%posts}}`.
 */
class m220122_131819_create_posts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%post}}', [
            'postId' => $this->primaryKey(),
            'userId' => $this->integer()->notNull(),
            'title' => $this->string(),
            'content' => $this->text(),
            'date' => $this->bigInteger(),
        ]);

        $this->addForeignKey(
            'fk-post-user-id',
            'post',
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
        $this->dropTable('{{%post}}');
    }
}
