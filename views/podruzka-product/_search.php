<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PodruzkaProductSearch */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs(
    '$("document").ready(function(){
        $("#w0").on("pjax:end", function() {
            $.pjax.reload({container:"#w1"});  //Reload GridView
        });
    });'
);

?>

<div class="podruzka-product-search">
    <?php yii\widgets\Pjax::begin(['id' => 'search-form']) ?>
    <?php $form = ActiveForm::begin([
        'action' => ['matching'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'article') ?>

    <?= $form->field($model, 'group') ?>

    <?= $form->field($model, 'category') ?>

    <?= $form->field($model, 'sub_category') ?>

    <?= $form->field($model, 'detail') ?>

    <?= $form->field($model, 'brand') ?>

    <?= $form->field($model, 'sub_brand') ?>

    <?= $form->field($model, 'line') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php yii\widgets\Pjax::end() ?>

</div>
