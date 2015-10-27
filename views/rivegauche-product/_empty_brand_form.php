<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RivegaucheProduct */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rivegauche-product-form">
    <?= Html::img($model->image_link, ['height' => '260px;', 'width' => '190px;',]) ?>
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'article')->textInput(['maxlength' => true, 'readonly' => true]) ?>
    <?= $form->field($model, 'title')->textarea(['rows' => 6, 'readonly' => true]) ?>
    <?= $form->field($model, 'description')->textInput(['maxlength' => true, 'readonly' => true]) ?>
    <?= $form->field($model, 'brand')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton(
                $model->isNewRecord ? 'Создать' : 'Обновить',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
