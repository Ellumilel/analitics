<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'Летуаль';
$this->params['breadcrumbs'][] = $this->title;

$dataProvider = new \yii\data\ActiveDataProvider([
    'query' => \app\models\LetualProduct::find(),
    'pagination' => [
        'pageSize' => 20,
    ],
    'sort' => [
        'attributes' => ['article', 'group', 'category'],
    ]
]);

?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $dataProvider,
        'layout'=>"{sorter}\n{pager}\n{summary}\n{items}",
        'columns' => [
            'article',
            'group',
            'category',
            'sub_category',
            'brand',
            'title',
            'description',
            [
                "attribute" => "description",
                'filter' => array("1"=>"Active","2"=>"Inactive"),
                'value' => 'description',
            ]
            //'article',
            //'created_at:datetime',
            // ...
        ],
    ]) ?>
    <p>
        This is the About page. You may modify the following file to customize its content:
    </p>

    <code><?= __FILE__ ?></code>
</div>
