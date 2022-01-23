<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;

/**
 * User model
 */
class User extends BaseUser implements IdentityInterface
{
    public const ROLE_ADMIN = 10;
    public const ROLE_CLIENT = 1;

    private const ROLE_ADMIN_LABEL = 'Admin';
    private const ROLE_CLIENT_LABEL = 'Client';

    public const STATUS_DELETED = 0;
    public const STATUS_INACTIVE = 9;
    public const STATUS_ACTIVE = 10;

    private const STATUS_DELETED_LABEL = 'Deleted';
    private const STATUS_INACTIVE_LABEL = 'Inactive';
    private const STATUS_ACTIVE_LABEL = 'Active';

    public const USER_STATUSES = [
        self::STATUS_DELETED => self::STATUS_DELETED_LABEL,
        self::STATUS_INACTIVE => self::STATUS_INACTIVE_LABEL,
        self::STATUS_ACTIVE => self::STATUS_ACTIVE_LABEL,
    ];

    private const USER_ROLES = [
        self::ROLE_ADMIN => self::ROLE_ADMIN_LABEL,
        self::ROLE_CLIENT => self::ROLE_CLIENT_LABEL,
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt',
                'value' => (new \DateTimeImmutable())->getTimestamp(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($userId)
    {
        return static::findOne(['userId' => $userId, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $accessToken = AccessToken::findUser($token);
        if ($accessToken) {
            return static::findOne(['userId' => $accessToken->userId]);
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public static function getActiveUserIdByAccessToken($token)
    {
        $user = self::findIdentityByAccessToken($token);
        if (!empty($user) && $user->status = self::STATUS_ACTIVE) {
            return $user->userId;
        }
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'passwordResetToken' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token)
    {
        return static::findOne([
            'verificationToken' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->passwordHash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->passwordHash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->authKey = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->passwordResetToken = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verificationToken = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->passwordResetToken = null;
    }

    /**
     * Return user statuses
     */

    public static function getStatusLabelByCode($status): string
    {
        return self::USER_STATUSES[$status];
    }

    /**
     * Return user roles
     */

    public static function roleLabelsByCode($role): string
    {
        return self::USER_ROLES[$role];
    }

    /**
     * Get current user
     * @return bool|User|IdentityInterface|null
     * @throws \Throwable
     */
    public static function getCurrent()
    {
        return !empty(\Yii::$app->user) ? \Yii::$app->user->getIdentity() : null;
    }

    /**
     * Get current user`s userId
     * @return bool|User|IdentityInterface|null
     * @throws \Throwable
     */
    public static function getCurrentUserId()
    {
        $currentUser = self::getCurrent();
        return !empty($currentUser) ? $currentUser->userId : null;
    }


    /**
     * Return user roles
     */

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function getPosts()
    {
        return $this->hasMany(Post::class, ['UserId' => 'UserId']);
    }
}
