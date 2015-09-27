<?php

use yii\helpers\Html;


$this->title = 'Загрузка информ. продукта';

/* @var $this yii\web\View */
?>
<div class="upload-index">
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
<div class="row">
    <div class="col-md-9">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Статистика загрузок</h3>
            </div><!-- /.box-header -->
            <div class="box-body no-padding">
                <div class="table-responsive mailbox-messages">
                    <table class="table table-hover table-striped">
                        <tbody>
                        <?php foreach ($upload as $row): ?>
                            <tr>
                                <td class="mailbox-star"><i class="fa fa-star text-yellow"></i></td>
                                <td><div class="icheckbox_flat-blue" style="position: relative;" aria-checked="false" aria-disabled="false">Загрузка: <?= $row->task; ?></div></td>
                                <td class="mailbox-name"><?= ($row->status) ? 'Успешно' : 'Ожидание' ?></td>
                                <td class="mailbox-subject"><b>StartTime: <?= $row->updated_at; ?></td>
                                <td class="mailbox-attachment"><b>EndTime: <?= $row->created_at; ?></td>
                                <td class="mailbox-date"><?= (new DateTime($row->updated_at))->diff(new DateTime($row->created_at))->format("В работе: %h часов, %i минут и %s секунд\n");?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table><!-- /.table -->
                </div><!-- /.mail-box-messages -->
            </div><!-- /.box-body -->
        </div><!-- /. box -->
    </div>
</div>
