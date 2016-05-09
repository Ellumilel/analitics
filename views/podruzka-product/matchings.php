<style>
    .size {
        white-space: nowrap; /* Запрещаем перенос строк */
        overflow: hidden; /* Обрезаем все, что не помещается в область */
        max-width: 180px; /* Ширина*/
        height: 50px;
        /* Высота/
       background: #fc0; /* Цвет фона */
        text-overflow: ellipsis; /* Добавляем многоточие */
    }

    .size:hover {
        white-space: normal; /* Обычный перенос текста */
    }
</style>
<?php
use himiklab\jqgrid\JqGridWidget;
use yii\helpers\Url;
use \kartik\dynagrid\DynaGrid;
use \app\helpers\TextHelper;
use \yii\helpers\ArrayHelper;
use \app\models\PodruzkaProduct;
use \kartik\grid\GridView;

/*
$columns = [
    [
        'format' => 'raw',
        'class' => 'kartik\grid\EditableColumn',
        'attribute' => 'article',
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '200px'],
        ],
        'value' => function ($model, $key, $index, $widget) {
            return $model->article;
        },
        'editableOptions' => function ($model, $key, $index) {
            return [
                'header' => 'l_id',
                'size' => 'md',
                'afterInput' => function ($form, $widget) use ($model, $index) {
                    return TextHelper::getArticleMatchingForm($model);
                },
                'formOptions' => [
                    'action' => \Yii::$app->getUrlManager()->createUrl(['podruzka-product/article-update']),
                ],
            ];
        }
    ],
    [
        'format' => 'raw',
        'attribute' => 'title',
        'value' => function ($model, $key, $index, $widget) {
            return '<div class="size">'.$model->title.'</div>';
        },
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '400px'],
        ],
        'vAlign'=>'middle',
        'hAlign'=>'left',
        'width'=>'400px',
    ],
    [
        'filterInputOptions' => ['placeholder' => ''],
        'attribute' => 'arrival',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map((new PodruzkaProduct)->getListArrival($condition), 'arrival', 'arrival'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '80px'],
        ],
        'value' => 'arrival',
    ],
    [
        'filterInputOptions' => ['placeholder' => ''],
        'attribute' => 'group',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map((new PodruzkaProduct)->getListGroup($condition, true), 'group', 'group'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '150px'],
        ],
        'value' => 'group',
    ],
    [
        'filterInputOptions' => ['placeholder' => ''],
        'attribute' => 'category',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map((new PodruzkaProduct)->getListCategory($condition, true), 'category',
            'category'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '150px'],
        ],
        'value' => 'category',
    ],
    [
        'filterInputOptions' => ['placeholder' => ''],
        'attribute' => 'sub_category',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map((new PodruzkaProduct)->getListSubCategory($condition, true),
            'sub_category', 'sub_category'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '150px'],
        ],
        'value' => 'sub_category',
    ],
    [
        'filterInputOptions' => ['placeholder' => ''],
        'attribute' => 'detail',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map((new PodruzkaProduct)->getListDetail($condition, true), 'detail',
            'detail'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '200px'],
        ],
        'value' => 'detail',
    ],
    [
        'filterInputOptions' => ['placeholder' => ''],
        'attribute' => 'brand',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map((new PodruzkaProduct)->getListBrand($condition, true), 'brand', 'brand'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '100px'],
        ],
        'value' => 'brand',
    ],
    [
        'filterInputOptions' => ['placeholder' => ''],
        'attribute' => 'sub_brand',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map((new PodruzkaProduct)->getListSubBrand($condition, true), 'sub_brand',
            'sub_brand'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '100px'],
        ],
        'value' => 'sub_brand',
    ],
    [
        'filterInputOptions' => ['placeholder' => ''],
        'attribute' => 'line',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map((new PodruzkaProduct)->getListLine($condition, true), 'line', 'line'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '100px'],
        ],
        'value' => 'line',
    ],
    'price',
    'ma_price',
    [
        'attribute' => 'l_old_price',
        'value' => function ($data) {
            return (!empty($data->l->old_price)) ? $data->l->old_price : '';
        },
        'format' => 'raw',
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
        ],
    ],
    [
        'attribute' => 'l_new_price',
        'value' => function ($data) {
            return (!empty($data->l->new_price)) ? $data->l->new_price : '';
        },
        'format' => 'raw',
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
        ],
    ],
    [
        'attribute' => 'r_price',
        'value' => function ($data) {
            return (!empty($data->r->price)) ? $data->r->price : '';
        },
        'format' => 'raw',
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
        ],
    ],
    [
        'attribute' => 'r_blue_price',
        'value' => function ($data) {
            return (!empty($data->r->blue_price)) ? $data->r->blue_price : '';
        },
        'format' => 'raw',
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
        ],
    ],
    [
        'attribute' => 'r_gold_price',
        'value' => function ($data) {
            return (!empty($data->r->gold_price)) ? $data->r->gold_price : '';
        },
        'format' => 'raw',
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
        ],
    ],
    [
        'attribute' => 'e_old_price',
        'value' => function ($data) {
            return (!empty($data->e->old_price)) ? $data->e->old_price : '';
        },
        'format' => 'raw',
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
        ],
    ],
    [
        'attribute' => 'e_new_price',
        'value' => function ($data) {
            return (!empty($data->e->new_price)) ? $data->e->new_price : '';
        },
        'format' => 'raw',
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
        ],
    ],
    [
        'attribute' => 'i_old_price',
        'value' => function ($data) {
            return (!empty($data->i->old_price)) ? $data->i->old_price : '';
        },
        'format' => 'raw',
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
        ],
    ],
    [
        'attribute' => 'i_new_price',
        'value' => function ($data) {
            return (!empty($data->i->new_price)) ? $data->i->new_price : '';
        },
        'format' => 'raw',
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
        ],
    ],
    [
        'attribute' => 'l_title',
        'value' => function ($data) {
            $text = '';
            if (!empty($data->l->title)) {
                $text .= $data->l->title;
            }
            if (!empty($data->l->description)) {
                $text .= ' ' . $data->l->description;
            }
            return '<div class="size">'.$text.'</div>';
        },
        'format' => 'raw',
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
        ],
    ],
    [
        'attribute' => 'l_link',
        'value' => function ($data) {
            if (!empty($data->l->link)) {
                return '<a href="' . $data->l->link . '" target="_blank">ссылка</a>';
            } else {
                return '';
            }
        },
        'format' => 'raw',
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
        ],
    ],
    [
        'attribute' => 'r_title',
        'value' => function ($data) {
            $text = '';
            if (!empty($data->r->title)) {
                $text .= $data->r->title;
            }
            if (!empty($data->r->description)) {
                $text .= ' ' . $data->r->description;
            }
            return '<div class="size">'.$text.'</div>';
        },
        'format' => 'raw',
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
        ],
    ],
    [
        'attribute' => 'r_link',
        'value' => function ($data) {
            if (!empty($data->r->link)) {
                return '<a href="' . $data->r->link . '" target="_blank">ссылка</a>';
            } else {
                return '';
            }
        },
        'format' => 'raw',
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
        ],
    ],
    [
        'attribute' => 'i_title',
        'value' => function ($data) {
            $text = '';
            if (!empty($data->i->title)) {
                $text .= $data->i->title;
            }
            if (!empty($data->i->description)) {
                $text .= ' ' . $data->i->description;
            }
            return '<div class="size">'.$text.'</div>';
        },
        'format' => 'raw',
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
        ],
    ],
    [
        'attribute' => 'r_link',
        'value' => function ($data) {
            if (!empty($data->i->link)) {
                return '<a href="' . $data->i->link . '" target="_blank">ссылка</a>';
            } else {
                return '';
            }
        },
        'format' => 'raw',
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
        ],
    ],
    [
        'attribute' => 'l_date',
        'value' => function ($data) {
            if (!empty($data->l->updated_at)) {
                return date('Y-m-d', strtotime($data->l->updated_at));
            } else {
                return null;
            }
        }
    ],
];

echo DynaGrid::widget([
    'columns'=>$columns,
    'storage'=>DynaGrid::TYPE_COOKIE,
    'theme'=>'panel-info',
    'gridOptions'=>[
        'dataProvider'=>$dataProvider,
        'filterModel'=>$searchModel,
        'showPageSummary'=>true,
        'floatHeader'=>true,
        'pjax'=>true,
        'panel'=>['heading'=>'<h3 class="panel-title">Таблица сопоставленных данных</h3>'],
    ],
    'options'=>['id'=>'dynagrid-1'] // a unique identifier is important
]);
*/
echo JqGridWidget::widget(
    [
        'requestUrl' => Url::to('jqgrid'),
        'gridSettings' => [
            'colNames' => [
                'Артикул',
                'Наименование',
                'Приход',
                'Группа',
                'Категория',
                'Подкатегория',
                'Детализация',
                'Бренд',
                'Подбренд',
                'Линейка',
                'Цена',
                'МА цена',
                'Наименование Летуаль',
                'Артикул Летуаль',
                'Описание Летуаль',
                'Цена Летуаль',
                'Новая цена Летуаль',
                'l_id',
                'e_id',
                'r_id',
                'i_id',
            ],
            'colModel' => [
                ['name' => 'article', 'index' => 'article', 'width' => 50, 'align' => "center"],
                ['name' => 'title', 'index' => 'title', 'width' => 450],
                [
                    'name' => 'arrival',
                    'index' => 'arrival',
                    'width' => 80,
                    "template" => "booleanCheckboxFa",
                    'searchoptions' => [

                        "value" => ":Любой;1:Разрешен;0:Запрещен",
                    ],

                ],
                ['name' => 'group', 'index' => 'group', 'editable' => false, 'width' => 150],
                ['name' => 'category', 'index' => 'category', 'editable' => false, 'width' => 150],
                ['name' => 'sub_category', 'index' => 'sub_category', 'editable' => false, 'width' => 150],
                ['name' => 'detail', 'index' => 'detail', 'editable' => false, 'width' => 150],
                [
                    'name' => 'brand',
                    'index' => 'brand',
                    'editable' => false,
                    'width' => 150,
                    'stype' => 'select',
                    'searchoptions' => [
                        'sopt' => ["eq", "ne"],
                        'value' => ":Любой;standard:standard;static:static;static-this-grid:static-this-grid",
                    ],
                ],
                ['name' => 'sub_brand', 'index' => 'sub_brand', 'editable' => false, 'width' => 150],
                ['name' => 'line', 'index' => 'line', 'editable' => false, 'width' => 150],
                ['name' => 'price', 'index' => 'price', 'editable' => false, 'width' => 150],
                ['name' => 'ma_price', 'index' => 'ma_price', 'editable' => false, 'width' => 150],
                ['name' => 'l.title', 'index' => 'l.title', 'editable' => false, 'width' => 150],
                ['name' => 'l.article', 'index' => 'l.article', 'editable' => false, 'width' => 150],
                ['name' => 'l.description', 'index' => 'l.description', 'editable' => false, 'width' => 150],
                ['name' => 'l.old_price', 'index' => 'l.old_price', 'editable' => false, 'width' => 150],
                ['name' => 'l.new_price', 'index' => 'l.new_price', 'editable' => false, 'width' => 150],
                ['name' => 'l_id', 'index' => 'l_id', 'editable' => false, 'width' => 150],
                ['name' => 'e_id', 'index' => 'e_id', 'editable' => false, 'width' => 150],
                ['name' => 'r_id', 'index' => 'r_id', 'editable' => false, 'width' => 150],
                ['name' => 'i_id', 'index' => 'i_id', 'editable' => false, 'width' => 150],
            ],
            'multiSort' => true,
            'iconSet' => 'fontAwesome',
            'gridview' => true,
            'rowNum' => 80,
            //'loadonce' => true, //сохраняет фильтр
            //'autowidth' => true,
            'autowidth' => true,
            'height' => '70vh',
            'caption' => "Таблица сопоставления",
        ],
        'pagerSettings' => [
            'edit' => false,
            'add' => false,
            'del' => false,
            'search' => ['multipleSearch' => true],
        ],
        'filterToolbarSettings' => ['stringResult' => true, 'autosearch' => true],
        'enableFilterToolbar' => true,
        'enableColumnChooser' => true,
    ]
);
?>
