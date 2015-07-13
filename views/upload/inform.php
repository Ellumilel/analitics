<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Загрузка информ. продукта';
$this->params['breadcrumbs'][] = $this->title;

/* @var $this yii\web\View */
?>
<div class="upload-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?
    $kato = \kato\DropZone::widget([
        'dropzoneContainer' => 'upload_inform',
        'options' => [
            'url' => \Yii::$app->getUrlManager()->createUrl(['site/upload']),

        ],
    ]);
    echo $kato;
    ?>
</div>
