<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RivegaucheProduct */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rivegauche-product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'article')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'group')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sub_category')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'brand')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image_link')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'showcases_new')->textInput() ?>

    <?= $form->field($model, 'showcases_compliment')->textInput() ?>

    <?= $form->field($model, 'showcases_offer')->textInput() ?>

    <?= $form->field($model, 'showcases_exclusive')->textInput() ?>

    <?= $form->field($model, 'showcases_bestsellers')->textInput() ?>

    <?= $form->field($model, 'showcases_expertiza')->textInput() ?>

    <?= $form->field($model, 'gold_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'blue_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'deleted_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
