<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use api\modules\v1\models\post\PostCreateForm;
use api\modules\v1\models\post\PostGetAllForm;
use api\modules\v1\models\post\PostGetForCurrentUserForm;

class PostController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'create' => ['post'],
                'get-all' => ['get'],
                'get-my' => ['get'],
            ]
        ];
        return $behaviors;
    }

    public function actionCreate()
    {
        $postForm = new PostCreateForm();
        $postForm->load(Yii::$app->request->bodyParams, '');
        if ($post = $postForm->create()) {
            return [
                'posts' => $post->serializeToArray(),
            ];
        }
        return [
            'error' => $postForm->getErrors(),
        ];
    }

    public function actionGetAll()
    {
        $postForm = new PostGetAllForm();
        $data = [
            'posts' => []
        ];
        $postForm->load(Yii::$app->request->get(), '');
        if ($postQuery = $postForm->getAll()) {
            foreach ($postQuery->each() as $post) {
                $data['posts'][] = $post->serializeToArray();
            }
            return $data;
        }
        return [
            'error' => $postForm->getErrors(),
        ];
    }
}
