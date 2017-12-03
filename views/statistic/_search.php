<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RivegaucheCategorySearch */
/* @var $form yii\widgets\ActiveForm */

$js = <<< 'SCRIPT'
$('.srt-d-price').on('click', function (){
    $('#sort').val($.trim($(this).val()));
});
$('.srt-d-price-0').on('click', function (){
    $('#sort_0').val($.trim($(this).val()));
});
SCRIPT;
$this->registerJs($js);
?>

<div class="rivegauche-category-search">
    <?php $form = ActiveForm::begin(
        [
            'options' => ['data-pjax' => true],
            //'action' => ['statistic/price-matching'],
            'method' => 'get',
        ]
    ); ?>

    <input name="sort" id="sort" type="hidden" value="">
    <input name="sort_0" id="sort_0" type="hidden" value="">
    <div class="row">
        <div class="col-md-3">
            <div class="box">
                <div class="box-body table-responsive pad">
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <th></th>
                            <th>Летуаль</th>
                            <th>Элизе</th>
                            <th>РивГош</th>
                            <th>ИльДеБотэ</th>
                        </tr>
                        <!-- Default -->
                        <tr>
                            <td>
                                <div class="btn-group-vertical">
                                    Цена подружки
                                </div>
                            </td>
                            <td>
                                <div class="btn-group-vertical">
                                    <?= Html::submitButton(
                                        'выше <span class="pull text-white"><i class="fa fa-angle-up"></i></span>',
                                        [
                                            'class' => 'srt-d-price btn btn-block btn-xs btn-danger',
                                            'value' => '-l_d_price',
                                        ]
                                    ) ?>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group-vertical">
                                    <?= Html::submitButton(
                                        'выше <span class="pull text-white"><i class="fa fa-angle-up"></i></span>',
                                        [
                                            'class' => 'srt-d-price btn btn-block btn-xs btn-danger',
                                            'value' => '-e_d_price',
                                        ]
                                    ) ?>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group-vertical">
                                    <?= Html::submitButton(
                                        'выше <span class="pull text-white"><i class="fa fa-angle-up"></i></span>',
                                        [
                                            'class' => 'srt-d-price btn btn-block btn-xs btn-danger',
                                            'value' => '-r_d_price',
                                        ]
                                    ) ?>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group-vertical">
                                    <?= Html::submitButton(
                                        'выше <span class="pull text-white"><i class="fa fa-angle-up"></i></span>',
                                        [
                                            'class' => 'srt-d-price btn btn-block btn-xs btn-danger',
                                            'value' => '-i_d_price',
                                        ]
                                    ) ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="btn-group-vertical">
                                    Цена подружки
                                </div>
                            </td>
                            <td>
                                <div class="btn-group-vertical">
                                    <?= Html::submitButton(
                                        'ниже <span class="pull text-white"><i class="fa fa-angle-down"></i></span>',
                                        [
                                            'class' => 'srt-d-price btn btn-block btn-xs btn-success',
                                            'value' => 'l_d_price',
                                        ]
                                    ) ?>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group-vertical">
                                    <?= Html::submitButton(
                                        'ниже <span class="pull text-white"><i class="fa fa-angle-down"></i></span>',
                                        [
                                            'class' => 'srt-d-price btn btn-block btn-xs btn-success',
                                            'value' => 'e_d_price',
                                        ]
                                    ) ?>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group-vertical">
                                    <?= Html::submitButton(
                                        'ниже <span class="pull text-white"><i class="fa fa-angle-down"></i></span>',
                                        [
                                            'class' => 'srt-d-price btn btn-block btn-xs btn-success',
                                            'value' => 'r_d_price',
                                        ]
                                    ) ?>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group-vertical">
                                    <?= Html::submitButton(
                                        'ниже <span class="pull text-white"><i class="fa fa-angle-down"></i></span>',
                                        [
                                            'class' => 'srt-d-price btn btn-block btn-xs btn-success',
                                            'value' => 'i_d_price',
                                        ]
                                    ) ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="btn-group-vertical">
                                    Цены
                                </div>
                            </td>
                            <td>
                                <div class="btn-group-vertical">
                                    <?= Html::submitButton(
                                        'равны <span class="pull text-white"><i class="fa fa-angle-left"></i></span>',
                                        [
                                            'class' => 'srt-d-price-0 btn btn-block btn-xs btn-warning',
                                            'value' => 'l_d_price',
                                        ]
                                    ) ?>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group-vertical">
                                    <?= Html::submitButton(
                                        'равны <span class="pull text-white"><i class="fa fa-angle-left"></i></span>',
                                        [
                                            'class' => 'srt-d-price-0 btn btn-block btn-xs btn-warning',
                                            'value' => 'e_d_price',
                                        ]
                                    ) ?>

                                </div>
                            </td>
                            <td>
                                <div class="btn-group-vertical">
                                    <?= Html::submitButton(
                                        'равны <span class="pull text-white"><i class="fa fa-angle-left"></i></span>',
                                        [
                                            'class' => 'srt-d-price-0 btn btn-block btn-xs btn-warning',
                                            'value' => 'r_d_price',
                                        ]
                                    ) ?>

                                </div>
                            </td>
                            <td>
                                <div class="btn-group-vertical">
                                    <?= Html::submitButton(
                                        'равны <span class="pull text-white"><i class="fa fa-angle-left"></i></span>',
                                        [
                                            'class' => 'srt-d-price-0 btn btn-block btn-xs btn-warning',
                                            'value' => 'i_d_price',
                                        ]
                                    ) ?>
                                </div>
                            </td>
                        </tr>
                        <!-- /.success -->
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::a('Очистить фильтры', ['statistic/price-matching'], ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
