<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RivegaucheCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rivegauche категории';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rivegauche-category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'id',
            //'link:ntext',
            'group',
            'category',
            'sub_category',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
