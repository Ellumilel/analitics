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
                <h3 class="box-title">Иль де боте всего: <?= (new \app\models\IledebeauteProduct)->find()->count() ?> </h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <?php
                    $model = \app\models\IledebeauteProduct::getStatistic();
                ?>
                <? $countLast = 0; ?>
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
                    <? $countLast = $countNew; ?>
                <? endforeach; ?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
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
                <? $countLast = 0; ?>
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
                    <? $countLast = $countNew; ?>
                <? endforeach; ?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
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
                <? $countLast = 0; ?>
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
                    <? $countLast = $countNew; ?>
                <? endforeach; ?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
</div>
