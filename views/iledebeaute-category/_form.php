<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\IledebeauteCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="iledebeaute-category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'link')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'group')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sub_category')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'создать' : 'обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
