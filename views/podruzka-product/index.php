<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use app\models\PodruzkaProduct;
use \app\models\LetualProduct;
use \app\models\RivegaucheProduct;
use \app\models\IledebeauteProduct;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PodruzkaProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $product array */

$this->title = 'Информационный продукт';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="podruzka-product-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'resizableColumns' => true,
        'bordered' => false,
        'columns' => [
            [
                    'format'=>'raw',
                    'class' => 'kartik\grid\EditableColumn',
                    'attribute' => 'article',
                    'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true, 'width' => '200px'],
                    ],
                    'value'=>function ($model, $key, $index, $widget) {
                        return $model->article;
                    },
                    'editableOptions'=> function ($model, $key, $index) {
                        return [
                                'header'=>'l_id',
                                'size'=>'md',
                                'afterInput'=>function ($form, $widget) use ($model, $index) {
                                    return
                                            Html::activeLabel($model, 'l_id').
                                            Html::input(
                                                '',
                                                'r_id',
                                                (!empty($lp = LetualProduct::find()->where(
                                                    ['id' => $model->l_id]
                                                )->one())) ? $lp->article : ''
                                            ).'</br>'.
                                            Html::activeLabel($model, 'r_id').
                                            Html::input(
                                                '',
                                                'r_id',
                                                (!empty($lp = RivegaucheProduct::find()->where(
                                                    ['id' => $model->r_id]
                                                )->one())) ? $lp->article : ''
                                            ).'</br>'.
                                            Html::activeLabel($model, 'i_id').
                                            Html::input(
                                                '',
                                                'i_id',
                                                (!empty($lp = IledebeauteProduct::find()->where(
                                                    ['id' => $model->i_id]
                                                )->one())) ? $lp->article : ''
                                            );
                                },
                                'formOptions' => [
                                        'action' => \Yii::$app->getUrlManager()->createUrl(['podruzka-product/article-update']),
                                ],
                        ];
                    }
            ],
            'title',
            [
                'filterInputOptions'=>['placeholder'=>''],
                'attribute' => 'arrival',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map((new PodruzkaProduct)->getListArrival($condition), 'arrival', 'arrival'),
                'filterWidgetOptions'=>[
                        'pluginOptions'=>['allowClear'=>true, 'width' => '80px'],
                ],
                'value' => 'arrival',
            ],
            [
                'filterInputOptions'=>['placeholder'=>''],
                'attribute' => 'group',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map((new PodruzkaProduct)->getListGroup($condition, true), 'group', 'group'),
                'filterWidgetOptions'=>[
                        'pluginOptions'=>['allowClear'=>true, 'width' => '150px'],
                ],
                'value' => 'group',
            ],
            [
                'filterInputOptions'=>['placeholder'=>''],
                'attribute' => 'category',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map((new PodruzkaProduct)->getListCategory($condition, true), 'category', 'category'),
                'filterWidgetOptions'=>[
                        'pluginOptions'=>['allowClear'=>true, 'width' => '150px'],
                ],
                'value' => 'category',
            ],
            [
                'filterInputOptions'=>['placeholder'=>''],
                'attribute' => 'sub_category',
                'filterType' => GridView::FILTER_SELECT2,
                'filter'=> ArrayHelper::map((new PodruzkaProduct)->getListSubCategory($condition, true), 'sub_category', 'sub_category'),
                'filterWidgetOptions'=>[
                        'pluginOptions'=>['allowClear'=>true, 'width' => '150px'],
                ],
                'value' => 'sub_category',
            ],
            [
                'filterInputOptions'=>['placeholder'=>''],
                'attribute' => 'detail',
                'filterType' => GridView::FILTER_SELECT2,
                'filter'=> ArrayHelper::map((new PodruzkaProduct)->getListDetail($condition, true), 'detail', 'detail'),
                'filterWidgetOptions'=>[
                        'pluginOptions'=>['allowClear'=>true, 'width' => '200px'],
                ],
                'value' => 'detail',
            ],
            [
                'filterInputOptions'=>['placeholder'=>''],
                'attribute' => 'brand',
                'filterType' => GridView::FILTER_SELECT2,
                'filter'=> ArrayHelper::map((new PodruzkaProduct)->getListBrand($condition, true), 'brand', 'brand'),
                'filterWidgetOptions'=>[
                        'pluginOptions'=>['allowClear'=>true, 'width' => '80px'],
                ],
                'value' => 'brand',
            ],
            [
                'filterInputOptions'=>['placeholder'=>''],
                'attribute' => 'sub_brand',
                'filterType' => GridView::FILTER_SELECT2,
                'filter'=> ArrayHelper::map((new PodruzkaProduct)->getListSubBrand($condition, true), 'sub_brand', 'sub_brand'),
                'filterWidgetOptions'=>[
                        'pluginOptions'=>['allowClear'=>true, 'width' => '80px'],
                ],
                'value' => 'sub_brand',
            ],
            [
                'filterInputOptions'=>['placeholder'=>''],
                'attribute' => 'line',
                'filterType' => GridView::FILTER_SELECT2,
                'filter'=> ArrayHelper::map((new PodruzkaProduct)->getListLine($condition, true), 'line', 'line'),
                'filterWidgetOptions'=>[
                        'pluginOptions'=>['allowClear'=>true, 'width' => '80px'],
                ],
                'value' => 'line',
            ],
             'price',
             'ma_price',
            // 'created_at',
            // 'updated_at',
            // 'deleted_at',

            //['class' => 'yii\grid\ActionColumn'],
        ],
        'responsive' => true,
        'hover' => true,
        'pjax'=>true,
        'pjaxSettings'=>[
                'neverTimeout'=>true,
                'beforeGrid'=>'My fancy content before.',
                'afterGrid'=>'My fancy content after.',
        ]
    ]); ?>
</div>
