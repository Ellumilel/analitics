<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ElizeCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории Elize';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elize-category-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            //'link:ntext',
            'group',
            'category',
            'sub_category',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
