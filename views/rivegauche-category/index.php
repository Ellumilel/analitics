<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RivegaucheCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории РивГош ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="rivegauche-category-index">
        <div class="col-md-6">
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <p>
                <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
            </p>

            <?= GridView::widget(
                [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        //['class' => 'yii\grid\SerialColumn'],
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
                    'responsive' => true,
                    'hover' => true,
                    'pjax' => true,
                    'export' => false,
                    'toggleData' => false,
                    'panel' => [
                        'type' => GridView::TYPE_PRIMARY,
                        'heading' => 'Категории РивГош',
                    ],
                    'pjaxSettings' => [
                        'neverTimeout' => true,
                        'options' => [
                            'id' => 'letual_product',
                        ],
                    ],
                ]
            ); ?>
        </div>
    </div>
</div>
