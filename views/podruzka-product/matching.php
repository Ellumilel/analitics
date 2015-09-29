<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
//use yii\grid\GridView;
use app\models\PodruzkaProduct;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PodruzkaProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $product array */

$this->title = 'Сопоставление';
$this->params['breadcrumbs'][] = $this->title;
?>
<a href="<?= \Yii::$app->getUrlManager()->createUrl(['download/matching']); ?>" class="btn btn-primary" ><i class="fa fa-download"></i> Выгрузить в Excel</a>
<div class="podruzka-product-index">
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'pjaxSettings'=>[
                'neverTimeout'=>true,
                'beforeGrid'=>'My fancy content before.',
                'afterGrid'=>'My fancy content after.',
            ],
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],

                //'id',
                'article',
                'title',
                [
                    'attribute'=>'arrival',
                    'filter'=> ArrayHelper::map((new PodruzkaProduct)->getListArrival($condition), 'arrival', 'arrival'),
                ],
                [
                    'attribute'=>'group',
                    'filter'=> ArrayHelper::map((new PodruzkaProduct)->getListGroup($condition, true), 'group', 'group'),
                ],
                [
                    'attribute'=>'category',
                    'filter'=> ArrayHelper::map((new PodruzkaProduct)->getListCategory($condition, true), 'category', 'category'),
                ],
                [
                    'attribute'=>'sub_category',
                    'filter'=> ArrayHelper::map((new PodruzkaProduct)->getListSubCategory($condition, true), 'sub_category', 'sub_category'),
                ],
                [
                    'attribute'=>'detail',
                    'filter'=> ArrayHelper::map((new PodruzkaProduct)->getListDetail($condition, true), 'detail', 'detail'),
                ],
                [
                    'attribute'=>'brand',
                    'filter'=> ArrayHelper::map((new PodruzkaProduct)->getListBrand($condition, true), 'brand', 'brand'),
                ],
                [
                    'attribute'=>'sub_brand',
                    'filter'=> ArrayHelper::map((new PodruzkaProduct)->getListSubBrand($condition, true), 'sub_brand', 'sub_brand'),
                ],
                [
                    'attribute'=>'line',
                    'filter'=> ArrayHelper::map((new PodruzkaProduct)->getListLine($condition, true), 'line', 'line'),
                ],
                'price',
                'ma_price',
                [
                    'attribute'=>'l_old_price',
                    'label' => 'let.price',
                    'value' => function($data) {
                        return (!empty($data->l->old_price)) ? $data->l->old_price : null;
                    },
                ],
                [
                    'attribute'=>'l_new_price',
                    'label' => 'let.new_price',
                    'value' => function($data) {
                        return (!empty($data->l->new_price)) ? $data->l->new_price : null;
                    }
                ],
                [
                    'attribute'=>'r_price',
                    'label' => 'rive.r_price',
                    'value' => function($data) {
                        return (!empty($data->r->price)) ? $data->r->price : null;
                    }
                ],
                [
                    'attribute'=>'r_blue_price',
                    'label' => 'rive.r_blue_price',
                    'value' => function($data) {
                        if(!empty($data->r->blue_price)) {
                            return $data->r->blue_price;
                        } else {
                            return null;
                        }
                    }
                ],
                [
                    'attribute'=>'r_gold_price',
                    'label' => 'rive.r_gold_price',
                    'value' => function($data) {
                        if(!empty($data->r->gold_price)) {
                            return $data->r->gold_price;
                        } else {
                            return null;
                        }
                    }
                ],
                [
                    'attribute'=>'i_old_price',
                    'label' => 'ile.price',
                    'value' => function($data) {
                        if(!empty($data->i->old_price)) {
                            return $data->i->old_price;
                        } else {
                            return null;
                        }
                    },
                ],
                [
                    'attribute'=>'i_new_price',
                    'label' => 'ile.new_price',
                    'value' => function($data) {
                        if(!empty($data->i->new_price)) {
                            return $data->i->new_price;
                        } else {
                            return null;
                        }
                    }
                ],
                /*[
                    'attribute' => 'act_role_id',
                    'label' => 'Actor Role',
                    'value' => 'actRole.role_name',
                    'filter' => yii\helpers\ArrayHelper::map(app\models\ActorRole::find()->orderBy('role_name')->asArray()->all(),'act_role_id','role_name')
                ],*/
                [
                    'attribute'=>'l_title',
                    'label' => 'let.title',
                    'value' => function($data) {
                        return (!empty($data->l->title)) ? $data->l->title : null;
                    }
                ],
                [
                    'attribute'=>'l_desc',
                    'label' => 'let.desc',
                    'value' => function($data) {
                        return (!empty($data->l->description)) ? $data->l->description : null;
                    }
                ],
                [
                    'attribute'=>'l_link',
                    'label' => 'let.link',
                    'format' => 'raw',
                    'value' => function($data) {
                        if(!empty($data->l->link)) {
                            return '<a href="'.$data->l->link.'" target="_blank">ссылка</a>';
                        } else{
                            return null;
                        }
                    }
                ],
                [
                    'attribute'=>'r_title',
                    'label' => 'rive.title',
                    'value' => function($data) {
                        return (!empty($data->r->title)) ? $data->r->title : null;
                    }
                ],
                [
                    'attribute'=>'r_desc',
                    'label' => 'rive.r_desc',
                    'value' => function($data) {
                        return (!empty($data->r->description)) ? $data->r->description : null;
                    }
                ],
                [
                    'attribute'=>'r_link',
                    'label' => 'rive.link',
                    'format' => 'raw',
                    'value' => function($data) {
                        if(!empty($data->r->link)) {
                            return '<a href="'.$data->r->link.'" target="_blank">ссылка</a>';
                        } else{
                            return null;
                        }
                    }
                ],
                [
                    'attribute'=>'i_title',
                    'label' => 'ile.title',
                    'value' => function($data) {
                        return (!empty($data->i->title)) ? $data->i->title : null;
                    }
                ],
                [
                    'attribute'=>'i_desc',
                    'label' => 'ile.desc',
                    'value' => function($data) {
                        return (!empty($data->i->description)) ? $data->i->description : null;
                    }
                ],
                [
                    'attribute'=>'i_link',
                    'label' => 'ile.link',
                    'format' => 'raw',
                    'value' => function($data) {
                        if(!empty($data->i->link)) {
                            return '<a href="'.$data->i->link.'" target="_blank">ссылка</a>';
                        } else{
                            return null;
                        }
                    }
                ],
                [
                    'attribute'=>'l_date',
                    'label' => 'let.date',
                    'value' => function($data) {
                        if(!empty($data->l->updated_at)) {
                            return date('Y-m-d',strtotime($data->l->updated_at));
                        } else{
                            return null;
                        }
                    }
                ],
                // 'created_at',
                // 'updated_at',
                // 'deleted_at',

                //['class' => 'yii\grid\ActionColumn'],
            ],
            'responsive'=>true,
            'hover'=>true,

        ]); ?>
</div>
