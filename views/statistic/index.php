<?php

/* @var $this yii\web\View */
/* @var $searchModel app\models\PodruzkaProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $product array */
/* @var $rivegaucheDataProvider \app\models\RivegaucheProductSearch */
/* @var $iledebeauteDataProvider \app\models\IledebeauteProductSearch */
/* @var $letualDataProvider \app\models\LetualProductSearch */
/* @var $elizeDataProvider \app\models\ElizeProductSearch */

$this->title = 'Статистика сбора данных по новинкам';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-3">
        <div class="box box-solid box-primary">
            <div class="box-header">
                <a title="Выгрузка таблицы сбора"
                   href="<?= \Yii::$app->getUrlManager()->createUrl(['download/download', 'company' => 'letual']); ?>"
                   class="btn btn-sm btn-default btn-flat pull-right"><i class="fa fa-download"></i></a>
                <h3 class="box-title">Летуаль всего: <?= (new \app\models\LetualProduct)->find()->count() ?></h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <?php \yii\widgets\Pjax::begin(['id' => 'let_list_wrapper']) ?>
                <?=
                \yii\widgets\ListView::widget(
                    [
                        'dataProvider' => $letualDataProvider,
                        'options' => [
                            'tag' => 'div',
                            'class' => 'let-list-wrapper',
                            'id' => 'let-list-wrapper',
                        ],
                        'layout' => "{items}\n{summary}\n{pager}",
                        'itemView' => function ($model, $key, $index, $widget) {
                            return $this->render('_list_item', ['model' => $model, 'link' => 'let']);
                        },
                        'itemOptions' => [
                            'tag' => false,
                        ],
                        'pager' => [
                            'nextPageLabel' => 'вперед',
                            'prevPageLabel' => 'назад',
                            'maxButtonCount' => 4,
                        ],
                    ]
                );
                ?>
                <?php \yii\widgets\Pjax::end() ?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
    <div class="col-md-3">
        <div class="box box-solid box-primary">
            <div class="box-header">
                <h3 class="box-title">РивГош всего: <?= (new \app\models\RivegaucheProduct)->find()->count() ?>
                </h3>
                <a title="Выгрузка таблицы сбора"
                   href="<?= \Yii::$app->getUrlManager()->createUrl(['download/download', 'company' => 'rive']); ?>"
                   class="btn btn-sm btn-default btn-flat pull-right"><i class="fa fa-download"></i></a>
            </div><!-- /.box-header -->
            <div class="box-body">
                <?php \yii\widgets\Pjax::begin(['id' => 'riv_list_wrapper']) ?>
                <?=
                \yii\widgets\ListView::widget(
                    [
                        'dataProvider' => $rivegaucheDataProvider,
                        'options' => [
                            'tag' => 'div',
                            'class' => 'riv-list-wrapper',
                            'id' => 'riv-list-wrapper',
                        ],
                        'layout' => "{items}\n{summary}\n{pager}",
                        'itemView' => function ($model, $key, $index, $widget) {
                            return $this->render('_list_item', ['model' => $model, 'link' => 'riv']);
                        },
                        'itemOptions' => [
                            'tag' => false,
                        ],
                        'pager' => [
                            'nextPageLabel' => 'вперед',
                            'prevPageLabel' => 'назад',
                            'maxButtonCount' => 4,
                        ],
                    ]
                );
                ?>
                <?php \yii\widgets\Pjax::end() ?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
    <div class="col-md-3">
        <div class="box box-solid box-primary">
            <div class="box-header">
                <a title="Выгрузка таблицы сбора"
                   href="<?= \Yii::$app->getUrlManager()->createUrl(['download/download', 'company' => 'ile']); ?>"
                   class="btn btn-sm btn-default btn-flat pull-right"><i class="fa fa-download"></i></a>
                <h3 class="box-title">Иль де боте всего: <?= (new \app\models\IledebeauteProduct)->find()->count() ?>
                    <?php
                    /* echo \yii\helpers\Html::a('Выгрузить в excel',\Yii::$app->getUrlManager()->createUrl(['upload/cmd']), [
                         'title' => Yii::t('yii', 'Загрузить'),
                         'onclick'=>"
                          $.ajax({
                             type     :'POST',
                             cache    : false,
                             url  : '".\Yii::$app->getUrlManager()->createUrl(['upload/cmd'])."',
                             success  : function(response) {

                             }
                         });return false;",
                                         ]);
                    */
                    ?>
                </h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <?php \yii\widgets\Pjax::begin(['id' => 'ile_list_wrapper']) ?>
                <?=
                \yii\widgets\ListView::widget(
                    [
                        'dataProvider' => $iledebeauteDataProvider,
                        'options' => [
                            'tag' => 'div',
                            'class' => 'ile-list-wrapper',
                            'id' => 'ile-list-wrapper',
                        ],
                        'layout' => "{items}\n{summary}\n{pager}",
                        'itemView' => function ($model, $key, $index, $widget) {
                            return $this->render('_list_item', ['model' => $model, 'link' => 'ile']);
                        },
                        'itemOptions' => [
                            'tag' => false,
                        ],
                        'pager' => [
                            'nextPageLabel' => 'вперед',
                            'prevPageLabel' => 'назад',
                            'maxButtonCount' => 4,
                        ],
                    ]
                );
                ?>
                <?php \yii\widgets\Pjax::end() ?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
    <div class="col-md-3">
        <div class="box box-solid box-primary">
            <div class="box-header">
                <a title="Выгрузка таблицы сбора"
                   href="<?= \Yii::$app->getUrlManager()->createUrl(['download/download', 'company' => 'eli']); ?>"
                   class="btn btn-sm btn-default btn-flat pull-right">
                    <i class="fa fa-download"></i>
                </a>
                <h3 class="box-title">Элизэ всего: <?= (new \app\models\ElizeProduct)->find()->count() ?></h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <?php \yii\widgets\Pjax::begin(['id' => 'eli_list_wrapper']) ?>
                <?=
                \yii\widgets\ListView::widget(
                    [
                        'dataProvider' => $elizeDataProvider,
                        'options' => [
                            'tag' => 'div',
                            'class' => 'eli-list-wrapper',
                            'id' => 'eli-list-wrapper',
                        ],
                        'layout' => "{items}\n{summary}\n{pager}",
                        'itemView' => function ($model, $key, $index, $widget) {
                            return $this->render('_list_item', ['model' => $model, 'link' => 'eli']);
                        },
                        'itemOptions' => [
                            'tag' => false,
                        ],
                        'pager' => [
                            'nextPageLabel' => 'вперед',
                            'prevPageLabel' => 'назад',
                            'maxButtonCount' => 4,
                        ],
                    ]
                );
                ?>
                <?php \yii\widgets\Pjax::end() ?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
</div>

