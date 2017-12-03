<?php

$this->title = 'Загрузка информационного продукта';

/* @var $this yii\web\View */
?>
<div class="row">
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"></h3>
                <a href="<?= \Yii::$app->getUrlManager()->createUrl(['upload/example','file'=>'inform']); ?>" class="btn btn-success btn-xs">
                    Скачать шаблон &nbsp<i class="fa fa-download"></i>
                </a>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="upload-index">
                    <?= $kato = \kato\DropZone::widget(
                        [
                            'dropzoneContainer' => 'upload_inform',
                            'options' => [
                                'url' => \Yii::$app->getUrlManager()->createUrl(['site/upload']),
                                'dictDefaultMessage' => 'Перетащите сюда файлы для загрузки'
                            ],

                        ]
                    );
                    ?>
                    <br>
                    <blockquote>
                        <p>Условия отбора ассортимента для загрузки:</p>
                        <small>статус карточки - активная (кроме исключенная)</small>
                        <small>приход - разрешен и запрещен</small>
                        <small>группа – все, кроме ДЕКОРАТИВНЫЕ АКСЕССУАРЫ, ПРОЧИЕ, РЕКЛАМА.</small>
                    </blockquote>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /. box -->
    </div>
</div>
<div class="row upload-inform">
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Статистика загрузок</h3>
            </div><!-- /.box-header -->
            <div class="box-body no-padding">
                <div class="table-responsive mailbox-messages">
                    <table class="table table-bordered">
                        <tbody>
                        <?php foreach ($upload as $row): ?>
                            <tr>
                                <td class="mailbox-star"><i class="fa fa-star text-yellow"></i></td>
                                <td>
                                    <div class="icheckbox_flat-blue" style="position: relative;" aria-checked="false"
                                         aria-disabled="false">Загрузка: <?= $row->task; ?></div>
                                </td>
                                <td class="mailbox-name"><?= ($row->status) ? 'Успешно' : 'Ожидание' ?></td>
                                <td class="mailbox-subject"><b>StartTime: <?= $row->updated_at; ?></td>
                                <td class="mailbox-attachment"><b>EndTime: <?= $row->created_at; ?></td>
                                <td class="mailbox-date"><?= (new DateTime($row->updated_at))->diff(
                                        new DateTime($row->created_at)
                                    )->format("В работе: %h часов, %i минут и %s секунд\n"); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table><!-- /.table -->
                </div><!-- /.mail-box-messages -->
            </div><!-- /.box-body -->
        </div><!-- /. box -->
    </div>
</div>
