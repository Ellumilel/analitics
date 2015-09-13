<?php

/* @var $this yii\web\View */
/* @var $searchModel app\models\PodruzkaProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $product array */

$this->title = 'Статистика сбора данных';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-4">
        <div class="box box-solid box-info">
            <div class="box-header">
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
                <?php
                    $model = \app\models\IledebeauteProduct::getStatistic();
                ?>
                <? $countLast = (new \app\models\IledebeauteProduct)->find()->count(); ?>
                <? foreach ($model as $row): ?>
                    <? $countNew = $row['counts']; ?>
                    <div class="col-sm-3 col-xs-6">
                        <div class="description-block border-right">
                            <? if($countLast == 0): ?>
                                <span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>
                            <? else: ?>
                                <? if($countNew * 100 / $countLast > 0): ?>
                                    <span class="description-percentage text-green"><i class="fa fa-caret-up"></i><?= round($countNew * 100 / $countLast, 2)?>%</span>
                                <? elseif($countNew * 100 / $countLast < 0): ?>
                                    <span class="description-percentage text-red"><i class="fa fa-caret-down"></i><?= round($countNew * 100 / $countLast, 2)?>%</span>
                                <? else: ?>
                                    <span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>
                                <? endif; ?>
                            <? endif; ?>
                            <h5 class="description-header"><?=$row['counts']?></h5>
                            <span class="description-text"><?=$row['dates']?></span>
                        </div><!-- /.description-block -->
                    </div>
                <? endforeach; ?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
    <div class="col-md-4">
        <a href="<?= \Yii::$app->getUrlManager()->createUrl(['download/download','company'=>'rive']); ?>" class="btn btn-primary" ><i class="fa fa-download"></i> Выгрузка РивГош</a>
        <?php
        /*echo \yii\helpers\Html::a('Выгрузить в Excel',\Yii::$app->getUrlManager()->createUrl(['upload/cmd']), [
            'title' => Yii::t('yii', 'Загрузить'),
            'id' => 'download',
            'class' => 'btn btn-primary',
            'onclick'=>"
              $.ajax({
                 type :'POST',
                 cache : false,
                 data : {'letual':'123'},
                 url : '".\Yii::$app->getUrlManager()->createUrl(['download/download'])."',
                 beforeSend: function(){
                    \$('#analytic_link').html('<div id=\"pre_loader\"></div>');
                    \$('#download').attr('disabled', 'disabled');
                 },
                 success  : function(response) {
                    $(\"#analytic_link\").html(response);
                    \$('#download').attr('disabled', '');
                 }
             });return false;",
        ]);
        */
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="box box-solid box-info">
            <div class="box-header">
                <h3 class="box-title">Рив Гош всего: <?= (new \app\models\RivegaucheProduct)->find()->count() ?> </h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <?php
                    $model = \app\models\RivegaucheProduct::getStatistic();
                ?>
                <? $countLast = (new \app\models\RivegaucheProduct)->find()->count(); ?>
                <? foreach ($model as $row): ?>
                    <? $countNew = $row['counts']; ?>
                    <div class="col-sm-3 col-xs-6">
                        <div class="description-block border-right">
                            <? if($countLast == 0): ?>
                                <span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>
                            <? else: ?>
                                <? if($countNew * 100 / $countLast > 0): ?>
                                    <span class="description-percentage text-green"><i class="fa fa-caret-up"></i><?= round($countNew * 100 / $countLast, 2)?>%</span>
                                <? elseif($countNew * 100 / $countLast < 0): ?>
                                    <span class="description-percentage text-red"><i class="fa fa-caret-down"></i><?= round($countNew * 100 / $countLast, 2)?>%</span>
                                <? else: ?>
                                    <span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>
                                <? endif; ?>
                            <? endif; ?>
                            <h5 class="description-header"><?=$row['counts']?></h5>
                            <span class="description-text"><?=$row['dates']?></span>
                        </div><!-- /.description-block -->
                    </div>
                <? endforeach; ?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
    <div class="col-md-4">
        <a href="<?= \Yii::$app->getUrlManager()->createUrl(['download/download','company'=>'ile']); ?>" class="btn btn-primary" ><i class="fa fa-download"></i> Выгрузка Иль Де Боте</a>
        <?php
        /*echo \yii\helpers\Html::a('Выгрузить в Excel',\Yii::$app->getUrlManager()->createUrl(['upload/cmd']), [
            'title' => Yii::t('yii', 'Загрузить'),
            'id' => 'download',
            'class' => 'btn btn-primary',
            'onclick'=>"
              $.ajax({
                 type :'POST',
                 cache : false,
                 data : {'letual':'123'},
                 url : '".\Yii::$app->getUrlManager()->createUrl(['download/download'])."',
                 beforeSend: function(){
                    \$('#analytic_link').html('<div id=\"pre_loader\"></div>');
                    \$('#download').attr('disabled', 'disabled');
                 },
                 success  : function(response) {
                    $(\"#analytic_link\").html(response);
                    \$('#download').attr('disabled', '');
                 }
             });return false;",
        ]);
        */
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="box box-solid box-info">
            <div class="box-header">
                <h3 class="box-title">Летуаль всего: <?= (new \app\models\LetualProduct)->find()->count() ?></h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <?php
                $model = \app\models\LetualProduct::getStatistic();

                /*echo \yii\widgets\DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [                      // the owner name of the model
                            'label' => 'Количество позиций',
                            'value' => $model['counts'],
                        ],
                        [                      // the owner name of the model
                            'label' => 'Дата обновления',
                            'value' => $model['dates'],
                        ],
                        //'dates:datetime', // creation date formatted as datetime
                    ],
                ]);*/
                ?>
                <? $countLast = (new \app\models\LetualProduct)->find()->count(); ?>
                <? foreach ($model as $row): ?>
                    <? $countNew = $row['counts']; ?>
                    <div class="col-sm-3 col-xs-6">
                        <div class="description-block border-right">
                            <? if($countLast == 0): ?>
                                <span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>
                            <? else: ?>
                                <? if($countNew * 100 / $countLast > 0): ?>
                                    <span class="description-percentage text-green"><i class="fa fa-caret-up"></i><?= round($countNew * 100 / $countLast, 2)?>%</span>
                                <? elseif($countNew * 100 / $countLast < 0): ?>
                                    <span class="description-percentage text-red"><i class="fa fa-caret-down"></i><?= round($countNew * 100 / $countLast, 2)?>%</span>
                                <? else: ?>
                                    <span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>
                                <? endif; ?>
                            <? endif; ?>
                            <h5 class="description-header"><?=$row['counts']?></h5>
                            <span class="description-text"><?=$row['dates']?></span>
                        </div><!-- /.description-block -->
                    </div>
                <? endforeach; ?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>

    <div class="col-md-4">
        <a href="<?= \Yii::$app->getUrlManager()->createUrl(['download/download','company'=>'letual']); ?>" class="btn btn-primary" ><i class="fa fa-download"></i> Выгрузка Летуаль</a>
        <?php
        /*echo \yii\helpers\Html::a('Выгрузить в Excel',\Yii::$app->getUrlManager()->createUrl(['upload/cmd']), [
            'title' => Yii::t('yii', 'Загрузить'),
            'id' => 'download',
            'class' => 'btn btn-primary',
            'onclick'=>"
              $.ajax({
                 type :'POST',
                 cache : false,
                 data : {'letual':'123'},
                 url : '".\Yii::$app->getUrlManager()->createUrl(['download/download'])."',
                 beforeSend: function(){
                    \$('#analytic_link').html('<div id=\"pre_loader\"></div>');
                    \$('#download').attr('disabled', 'disabled');
                 },
                 success  : function(response) {
                    $(\"#analytic_link\").html(response);
                    \$('#download').attr('disabled', '');
                 }
             });return false;",
        ]);
        */
        ?>
    </div>
</div>


