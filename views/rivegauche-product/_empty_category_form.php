<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RivegaucheProduct */
/* @var $form yii\widgets\ActiveForm */

$category = \app\models\RivegaucheCategory::findBySql("Select distinct category from rivegauche_category")->all();
foreach ($category as $itemC) {
    $dataC[] = $itemC['category'];
}

$group = \app\models\RivegaucheCategory::findBySql("Select distinct `group` from rivegauche_category")->all();
foreach ($group as $itemG) {
    $dataG[] = $itemG['group'];
}

$subCat = \app\models\RivegaucheCategory::findBySql("Select distinct sub_category from rivegauche_category")->all();
foreach ($subCat as $itemS) {
    $dataS[] = $itemS['sub_category'];
}

?>

<div class="rivegauche-product-form">
    <?= Html::img($model->image_link, ['height' => '260px;', 'width' => '190px;',]) ?>
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'article')->textInput(['maxlength' => true, 'readonly' => true]) ?>
    <?= $form->field($model, 'title')->textarea(['rows' => 6, 'readonly' => true]) ?>
    <?= $form->field($model, 'description')->textInput(['maxlength' => true, 'readonly' => true]) ?>
    <?= $form->field($model, 'category')->dropDownList($dataC) ?>
    <?= $form->field($model, 'group')->dropDownList($dataG) ?>
    <?= $form->field($model, 'sub_category')->dropDownList($dataS) ?>
    <div class="form-group">
        <?= Html::submitButton(
                $model->isNewRecord ? 'Создать' : 'Обновить',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
