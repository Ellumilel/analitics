<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RivegaucheProductSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rivegauche-product-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'article') ?>

    <?= $form->field($model, 'link') ?>

    <?= $form->field($model, 'group') ?>

    <?= $form->field($model, 'category') ?>

    <?php // echo $form->field($model, 'sub_category') ?>

    <?php // echo $form->field($model, 'brand') ?>

    <?php // echo $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'image_link') ?>

    <?php // echo $form->field($model, 'showcases_new') ?>

    <?php // echo $form->field($model, 'showcases_compliment') ?>

    <?php // echo $form->field($model, 'showcases_offer') ?>

    <?php // echo $form->field($model, 'showcases_exclusive') ?>

    <?php // echo $form->field($model, 'showcases_bestsellers') ?>

    <?php // echo $form->field($model, 'showcases_expertiza') ?>

    <?php // echo $form->field($model, 'gold_price') ?>

    <?php // echo $form->field($model, 'blue_price') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
