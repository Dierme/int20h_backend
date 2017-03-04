<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\WordsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Words';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="words-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Words', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' =>  'lang_full_name',
                'label' =>  'Language',
                'value' =>  function($model){
                    return $model->getLang()->one()->lang_full_name;
                }
            ],
            'text',
            'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
