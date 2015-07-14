<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LetualProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Летуаль';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="letual-product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?//= Html::a('Create Letual Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'id',
            'article',
            //'link:ntext',
            'group',
            [
                "attribute" => "category",
                'filter' => \app\models\LetualProductSearch::dropDownCategory(),
                'value' => 'category',
            ],
            [
                "attribute" => "sub_category",
                'filter' => \app\models\LetualProductSearch::dropDownSubCategory(),
                'value' => 'sub_category',
            ],
            [
                "attribute" => "brand",
                'filter' => \app\models\LetualProductSearch::dropDownBrand(),
                'value' => 'brand',
            ],
             'title:ntext',
             'description',
             'new_price',
             'old_price',

            //'image_link:ntext',
            // 'created_at',
            // 'updated_at',
            // 'deleted_at',

           // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
