<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\post\PostGetForCurrentUserForm;
use api\modules\v1\models\user\SignupForm;
use api\modules\v1\models\user\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\Controller;


class UserController extends Controller
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'login' => ['post'],
                'signup' => ['post'],
            ]
        ];
        return $behaviors;
    }

    public function actionLogin()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new LoginForm();
        $model->load(Yii::$app->request->bodyParams, '');
        if ($accessToken = $model->login()) {
            return [
                'access_token' => $accessToken->accessToken,
            ];
        } else {
            return [
                'error' => $model->getErrors(),
            ];
        }
    }

    public function actionSignup()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new SignupForm();
        $model->load(Yii::$app->request->bodyParams, '');
        if ($token = $model->signup()) {
            return [
                'access_token' => $token
            ];
        } else {
            return [
                'error' => $model->getErrors(),
            ];
        }
    }

    public function actionGetCurrentUserPosts()
    {
        $postForm = new PostGetForCurrentUserForm();
        $postForm->load(Yii::$app->request->get(), '');
        $data = [
            'posts' => []
        ];
        if ($postQuery = $postForm->getForCurrentUser()) {
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
