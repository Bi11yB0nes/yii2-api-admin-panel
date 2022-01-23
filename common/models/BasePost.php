<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "post".
 * @property int $postId
 * @property int $userId
 * @property string $title
 * @property string $content
 * @property int $date
 */
class BasePost extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId'], 'integer'],
            [['content', 'title'], 'required'],
            [['userId'], 'default', 'value' => (User::getCurrentUserId())],
            [['content'], 'string'],
            [['date'], 'integer'],
            [['date'], 'default', 'value' => (new \DateTimeImmutable())->getTimestamp()],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'postId' => 'Post ID',
            'userId' => 'User ID',
            'title' => 'Title',
            'content' => 'Content',
            'date' => 'Date',
        ];
    }

    /**
     * Gets query for [[BaseUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['userId' => 'userId']);
    }
}
