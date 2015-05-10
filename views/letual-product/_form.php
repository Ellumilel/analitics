<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LetualProduct */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="letual-product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'article')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'link')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'group')->textInput(['maxlength' => 500]) ?>

    <?= $form->field($model, 'category')->textInput(['maxlength' => 500]) ?>

    <?= $form->field($model, 'sub_category')->textInput(['maxlength' => 500]) ?>

    <?= $form->field($model, 'brand')->textInput(['maxlength' => 500]) ?>

    <?= $form->field($model, 'title')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => 500]) ?>

    <?= $form->field($model, 'image_link')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'deleted_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
