<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\IledebeauteCategory */

$this->title = 'Create Iledebeaute Category';
$this->params['breadcrumbs'][] = ['label' => 'Iledebeaute Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iledebeaute-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
