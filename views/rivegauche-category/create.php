<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RivegaucheCategory */

$this->title = 'Create Rivegauche Category';
$this->params['breadcrumbs'][] = ['label' => 'Rivegauche Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rivegauche-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
