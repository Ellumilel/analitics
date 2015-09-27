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
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
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
                        return $data->l->old_price;
                    },
                ],
                [
                    'attribute'=>'l_new_price',
                    'label' => 'let.new_price',
                    'value' => function($data) {
                        return $data->l->new_price;
                    }
                ],
                [
                    'attribute'=>'r_price',
                    'label' => 'rive.price',
                    'value' => function($data) {
                        return $data->r->price;
                    }
                ],
                [
                    'attribute'=>'r_blue_price',
                    'label' => 'rive.r_blue_price',
                    'value' => function($data) {
                        return $data->r->blue_price;
                    }
                ],
                [
                    'attribute'=>'r_gold_price',
                    'label' => 'rive.r_gold_price',
                    'value' => function($data) {
                        return $data->r->gold_price;
                    }
                ],
                [
                    'attribute'=>'i_old_price',
                    'label' => 'ile.price',
                    'value' => function($data) {
                        return $data->i->old_price;
                    },
                ],
                [
                    'attribute'=>'i_new_price',
                    'label' => 'ile.new_price',
                    'value' => function($data) {
                        return $data->i->new_price;
                    }
                ],
                /*[
                    'attribute' => 'act_role_id',
                    'label' => 'Actor Role',
                    'value' => 'actRole.role_name',
                    'filter' => yii\helpers\ArrayHelper::map(app\models\ActorRole::find()->orderBy('role_name')->asArray()->all(),'act_role_id','role_name')
                ],*/
                [
                    'attribute'=>'l_article',
                    'label' => 'l.article',
                    'value' => function($data) {
                        return $data->l->article;
                    }
                ],
                [
                    'attribute'=>'l_title',
                    'label' => 'let.title',
                    'value' => function($data) {
                        return $data->l->title;
                    }
                ],
                [
                    'attribute'=>'l_desc',
                    'label' => 'let.desc',
                    'value' => function($data) {
                        return $data->l->description;
                    }
                ],
                [
                    'attribute'=>'l_link',
                    'label' => 'let.link',
                    'format' => 'raw',
                    'value' => function($data) {
                        return '<a href="'.$data->l->link.'" target="_blank">ссылка</a>';
                    }
                ],
                [
                    'attribute'=>'r_article',
                    'label' => 'rive.article',
                    'value' => function($data) {
                        return $data->r->article;
                    }
                ],
                [
                    'attribute'=>'r_title',
                    'label' => 'rive.title',
                    'value' => function($data) {
                        return $data->r->title;
                    }
                ],
                [
                    'attribute'=>'r_desc',
                    'label' => 'rive.r_desc',
                    'value' => function($data) {
                        return $data->r->description;
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
                            return '';
                        }
                    }
                ],
                [
                    'attribute'=>'i_article',
                    'label' => 'ile.article',
                    'value' => function($data) {
                        return $data->i->article;
                    }
                ],
                [
                    'attribute'=>'i_title',
                    'label' => 'ile.title',
                    'value' => function($data) {
                        return $data->i->title;
                    }
                ],
                [
                    'attribute'=>'i_desc',
                    'label' => 'ile.desc',
                    'value' => function($data) {
                        return $data->i->description;
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
                            return '';
                        }
                    }
                ],
                [
                    'attribute'=>'l_date',
                    'label' => 'let.date',
                    'value' => function($data) {
                        return date('Y-m-d',strtotime($data->l->updated_at));
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
