<?php

use yii\widgets\Pjax;
use yii\grid\GridView;
use app\helpers\TextHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PodruzkaProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $product array */

$this->title = 'Сравнение цен по сопоставленным артикулам';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-4">
        <div>
            <ul>
                <li><a href="#">Цена Подружки выше на <span class="pull text-red">   <i class="fa fa-angle-up"></i> 123</span></a></li>
                <li><a href="#">Цена Подружки ниже на<span class="pull text-green">   <i class="fa fa-angle-down"></i> 123</span></a></li>
                <li><a href="#">Цены одинаковые <span class="pull text-yellow">   <i class="fa fa-angle-left"></i> 0</span></a></li>
            </ul>
        </div>
    </div>
</div>
<div class="podruzka-product-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php Pjax::begin(); ?>

    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                'article',
                'title',
                [
                    'attribute'=>'arrival',
                    'filter'=> \yii\helpers\ArrayHelper::map((new \app\models\PodruzkaProduct())->getListArrival($condition), 'arrival', 'arrival'),
                ],
                [
                    'attribute'=>'category',
                    'filter'=> \yii\helpers\ArrayHelper::map((new \app\models\PodruzkaProduct())->getListCategory($condition, true), 'category', 'category'),
                ],
                [
                    'attribute'=>'brand',
                    'filter'=> \yii\helpers\ArrayHelper::map((new \app\models\PodruzkaProduct())->getListBrand($condition, true), 'brand', 'brand'),
                ],
                'price',
                'ma_price',
                [
                    'attribute' => 'l_old_price',
                    'label' => 'let.price',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return (!empty($data->l->old_price)) ? TextHelper::getPriceMatchLink(
                            $data->price,
                            $data->l->old_price,
                            $data->l->link
                        ) : '';
                    },
                ],
                [
                    'attribute' => 'l_new_price',
                    'label' => 'let.new_price',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return (!empty($data->l->new_price)) ? TextHelper::getPriceMatchLink(
                            $data->price,
                            $data->l->new_price,
                            $data->l->link
                        ) : '';
                    },
                ],
                [
                    'attribute' => 'r_price',
                    'label' => 'rive.r_price',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return (!empty($data->r->price)) ? TextHelper::getPriceMatchLink(
                            $data->price,
                            $data->r->price,
                            $data->r->link
                        ) : '';
                    },
                ],
                [
                    'attribute' => 'r_gold_price',
                    'label' => 'rive.r_gold_price',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return (!empty($data->r->gold_price)) ? TextHelper::getPriceMatchLink(
                            $data->price,
                            $data->r->gold_price,
                            $data->r->link
                        ) : '';
                    },
                ],
                [
                    'attribute' => 'i_old_price',
                    'label' => 'ile.old_price',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return (!empty($data->i->old_price)) ? TextHelper::getPriceMatchLink(
                            $data->price,
                            $data->i->old_price,
                            $data->i->link
                        ) : '';
                    },
                ],
                [
                    'attribute' => 'i_new_price',
                    'label' => 'ile.new_price',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return (!empty($data->i->new_price)) ? TextHelper::getPriceMatchLink(
                            $data->price,
                            $data->i->new_price,
                            $data->i->link
                        ) : '';
                    },
                ],
                //['class' => 'yii\grid\ActionColumn'],
            ],
        ]
    ); ?>
    <?php Pjax::end(); ?>
</div>
