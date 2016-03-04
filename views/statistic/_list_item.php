<div class="box-body no-padding">
    <ul class="nav nav-pills nav-stacked">
        <li><a href="<?= \Yii::$app->getUrlManager()->createUrl(
                ['statistic/new-product', 'partner' => $link, 'date' => $model['dates']]
            ); ?>"><b><?= $model['dates'] ?></b>
                <span style="font-size: 100%;" class="label label-success pull-right"><?= $model['counts'] ?></span></a>
        </li>
    </ul>
</div>
