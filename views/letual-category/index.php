<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LetualCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Letual категории';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="letual-category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            //'link:ntext',
            'group',
            'category',
            'sub_category',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>

    <? echo \kato\DropZone::widget([ 'options' => [
        'url' => \Yii::$app->getUrlManager()->createUrl(['site/upload'])
        ],
    ]);  ?>
</div>
