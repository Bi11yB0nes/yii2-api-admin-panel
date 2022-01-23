<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "accessToken".
 * @property int $accessTokenId
 * @property string $userId
 * @property string $accessToken
 */
class AccessToken extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'accesstoken';
    }

    /**
     * @throws \yii\base\Exception
     */
    public function generateToken()
    {
        $this->accessToken = \Yii::$app->security->generateRandomString();
    }

    public function getUserId($token)
    {
        $user = $this::findUser($token);
        return $user->userId;
    }

    public static function findUser($token)
    {
        return static::findOne(['accessToken' => $token]);
    }

    public static function findByUserId($userId)
    {
        return static::find()->where(['userId' => $userId])->one();
    }
}