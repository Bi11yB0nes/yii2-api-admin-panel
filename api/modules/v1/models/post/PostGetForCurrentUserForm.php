<?php

namespace api\modules\v1\models\post;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Post;

/**
 * Post Create form
 * @property int $limit
 * @property int $offset
 * @property string $accessToken
 */
class PostGetForCurrentUserForm extends Model
{
    public $limit = 5;
    public $offset = 0;
    public $accessToken;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['accessToken', 'limit', 'offset'], 'integer'],
        ];
    }

    public function getForCurrentUser()
    {
        $userId = User::getActiveUserIdByAccessToken($this->accessToken);
        if (!$userId) {
            $this->addError('auth_error', 'Invalid access token');
            return null;
        }
        return Post::find()
            ->where(['userId' => $userId])
            ->limit($this->limit)
            ->offset($this->offset);
    }
}

