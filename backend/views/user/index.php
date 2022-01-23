<?php

use common\models\User;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'userId',
            [
                'attribute' => 'username',
                'format' => 'html',
                'value' => static function (User $model) {
                    return Html::a($model->username, ['view', 'id' => $model->primaryKey]);
                }
            ],
            'email:email',
            [
                'attribute' => 'status',
                'value' => function (User $model) {
                    return User::getStatusLabelByCode($model->status);
                },
            ],
            'createdAt:datetime',
            'updatedAt:datetime',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}'
            ],
        ],
    ]); ?>


</div>
