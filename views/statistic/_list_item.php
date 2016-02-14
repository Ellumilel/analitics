<div class="col-sm-3 col-xs-6">
    <div class="description-block border-right">
        <h5 class="description-header">
            <a href="<?= \Yii::$app->getUrlManager()->createUrl(
                ['statistic/new-product', 'partner'=>'ile', 'date' => $model['dates']]
            ); ?>">
                <?=$model['counts']?>
            </a>
        </h5>
        <span class="description-text"><?= $model['dates'] ?></span>
    </div><!-- /.description-block -->
</div>
