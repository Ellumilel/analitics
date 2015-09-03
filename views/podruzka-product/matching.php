<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use app\models\PodruzkaProduct;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PodruzkaProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $product array */

$this->title = 'Сопоставление';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="podruzka-product-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            'article',
            'title',
            'arrival',
            [
                'attribute'=>'group',
                'filter'=> ArrayHelper::map((new PodruzkaProduct)->getListGroup($condition), 'group', 'group'),
            ],
            [
                'attribute'=>'category',
                'filter'=> ArrayHelper::map((new PodruzkaProduct)->getListCategory($condition), 'category', 'category'),
            ],
            [
                'attribute'=>'sub_category',
                'filter'=> ArrayHelper::map((new PodruzkaProduct)->getListSubCategory($condition), 'sub_category', 'sub_category'),
            ],
            [
                'attribute'=>'detail',
                'filter'=> ArrayHelper::map((new PodruzkaProduct)->getListDetail($condition), 'detail', 'detail'),
            ],
            [
                'attribute'=>'brand',
                'filter'=> ArrayHelper::map((new PodruzkaProduct)->getListBrand($condition), 'brand', 'brand'),
            ],
            [
                'attribute'=>'sub_brand',
                'filter'=> ArrayHelper::map((new PodruzkaProduct)->getListSubBrand($condition), 'sub_brand', 'sub_brand'),
            ],
            [
                'attribute'=>'line',
                'filter'=> ArrayHelper::map((new PodruzkaProduct)->getListLine($condition), 'line', 'line'),
            ],
            'price',
            'ma_price',
            [
                'attribute'=>'old_Price',
                'label' => 'Лет.Цена',
                'value' => function($data) {
                    return $data->l->old_price;
                }
            ],
            [
                'attribute'=>'new_price',
                'label' => 'Лет.Цена со скидкой',
                'value' => function($data) {
                    return $data->l->new_price;
                }
            ],
            /*[
                'attribute' => 'act_role_id',
                'label' => 'Actor Role',
                'value' => 'actRole.role_name',
                'filter' => yii\helpers\ArrayHelper::map(app\models\ActorRole::find()->orderBy('role_name')->asArray()->all(),'act_role_id','role_name')
            ],*/
            [
                'attribute'=>'new_price',
                'label' => 'Лет.Артикул',
                'value' => function($data) {
                    return $data->l->article;
                }
            ],
            [
                'attribute'=>'new_price',
                'label' => 'Лет.Наименование',
                'value' => function($data) {
                    return $data->l->title;
                }
            ],
            [
                'attribute'=>'new_price',
                'label' => 'Лет.Описание',
                'value' => function($data) {
                    return $data->l->description;
                }
            ],
            [
                'attribute'=>'new_price',
                'label' => 'Лет.ссылка на товар',
                'format' => 'raw',
                'value' => function($data) {
                    return '<a href="'.$data->l->link.'"  target="_blank">ссылка</a>';
                }
            ],
            [
                'attribute'=>'new_price',
                'label' => 'Лет.дата выгрузки',
                'value' => function($data) {
                    return date('Y-m-d',strtotime($data->l->updated_at));
                }
            ],
            // 'created_at',
            // 'updated_at',
            // 'deleted_at',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
