<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Post;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Post', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'postId',
            [
                'attribute' => 'title',
                'format' => 'html',
                'value' => static function (Post $model) {
                    return Html::a($model->title, ['view', 'id' => $model->primaryKey]);
                }
            ],

            'content:ntext',
            'date:datetime',
            'userId',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}'
            ],
        ],
    ]); ?>


</div>
