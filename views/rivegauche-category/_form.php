<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RivegaucheCategory */
/* @var $form yii\widgets\ActiveForm */
$data = ['category' => 'category', 'brand' => 'brand'];
?>

<div class="rivegauche-category-form col-md-4">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'link')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'group')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sub_category')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList($data) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'создать' : 'обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
