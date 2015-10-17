<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RivegaucheProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rivegauche Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rivegauche-product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Rivegauche Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'article',
            'link:ntext',
            'group',
            'category',
            // 'sub_category',
            // 'brand',
            // 'title:ntext',
            // 'description',
            // 'image_link:ntext',
            // 'showcases_new',
            // 'showcases_compliment',
            // 'showcases_offer',
            // 'showcases_exclusive',
            // 'showcases_bestsellers',
            // 'showcases_expertiza',
            // 'gold_price',
            // 'blue_price',
            // 'price',
            // 'created_at',
            // 'updated_at',
            // 'deleted_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
