<?php

namespace api\modules\v1\models\post;

use Yii;
use yii\base\Model;
use common\models\Post;

/**
 * Post Create form
 */
class PostGetAllForm extends Model
{
    public $limit = 5;
    public $offset = 0;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['limit', 'offset'], 'integer'],
        ];
    }

    public function getAll()
    {
        return Post::find()
            ->limit($this->limit)
            ->offset($this->offset);
    }
}

