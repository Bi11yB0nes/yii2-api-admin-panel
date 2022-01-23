<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $userId
 * @property string $username
 * @property string $authKey
 * @property string $passwordHash
 * @property string|null $passwordResetToken
 * @property string $email
 * @property int $role
 * @property int $status
 * @property int $createdAt
 * @property int $updatedAt
 * @property string|null $verificationToken
 *
 * @property BasePost[] $posts
 */
class BaseUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'authKey', 'passwordHash', 'email', 'role', 'status'], 'required'],
            [['role', 'status'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['username', 'passwordHash', 'passwordResetToken', 'email', 'verificationToken'], 'string', 'max' => 255],
            [['authKey'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['passwordResetToken'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'userId' => 'User ID',
            'username' => 'User Name',
            'authKey' => 'Auth Key',
            'passwordHash' => 'Password Hash',
            'passwordResetToken' => 'Password Reset Token',
            'email' => 'Email',
            'role' => 'Role',
            'status' => 'Status',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'verificationToken' => 'Verification Token',
        ];
    }

    /**
     * Gets query for [[Posts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['userId' => 'userId']);
    }
}
