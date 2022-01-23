<?php

namespace api\modules\v1\models\post;

use Yii;
use yii\base\Model;
use common\models\Post;
use common\models\User;

/**
 * Post Create form
 * @property string $title
 * @property string $content
 * @property string $accessToken
 */
class PostCreateForm extends Model
{
    public $title;
    public $content;
    public $accessToken;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['accessToken', 'title', 'content'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['content'], 'string','max' => 4096],
        ];
    }

    public function create()
    {
        $userId = User::getActiveUserIdByAccessToken($this->accessToken);
        if (empty($userId)) {
            $this->addError('auth_error', 'Invalid access token');
            return null;
        }
        if (!$this->validate()) {
            return null;
        }
        $post = new Post();
        $post->userId = $userId;
        $post->title = $this->title;
        $post->content = $this->content;
        if (!$post->save()) {
            return false;
        }
        return $post;
    }
}
