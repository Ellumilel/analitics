<?php
$this->title = 'Загрузка Файла сопоставления';
$this->params['breadcrumbs'][] = $this->title;
/* @var $this yii\web\View */
?>
<div class="col-md-4">
    <div class="box box-warning">
        <div class="box-header with-border">
            <h3 class="box-title">Файл</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="upload-index">
                <?php $kato = \kato\DropZone::widget([
                    'dropzoneContainer' => 'upload_matching',
                    'options' => [
                        'url' => \Yii::$app->getUrlManager()->createUrl(['site/upload-matching']),

                    ],
                ]);
                echo $kato;
                ?>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
</div>

