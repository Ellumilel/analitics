<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LetualCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="letual-category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'link')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'group')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'category')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'sub_category')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
