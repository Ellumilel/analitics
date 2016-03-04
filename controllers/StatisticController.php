<?php

namespace app\controllers;

use app\models\ElizeProduct;
use app\models\ElizeProductSearch;
use app\models\IledebeauteProduct;
use app\models\IledebeauteProductSearch;
use app\models\LetualProduct;
use app\models\LetualProductSearch;
use app\models\PodruzkaProduct;
use app\models\PodruzkaProductSearch;
use app\models\RivegaucheProduct;
use app\models\RivegaucheProductSearch;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Yii;

class StatisticController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['inform'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login'],
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => [
                            'index',
                            'avg-brand',
                            'avg-category',
                            'avg-matching',
                            'price-matching',
                            'new-product',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $rivegaucheDataProvider = RivegaucheProductSearch::getStatistics();
        $iledebeauteDataProvider = IledebeauteProductSearch::getStatistics();
        $letualDataProvider = LetualProductSearch::getStatistics();
        $elizeDataProvider = ElizeProductSearch::getNewStatistics();

        return $this->render(
            'index',
            [
                'rivegaucheDataProvider' => $rivegaucheDataProvider,
                'iledebeauteDataProvider' => $iledebeauteDataProvider,
                'letualDataProvider' => $letualDataProvider,
                'elizeDataProvider' => $elizeDataProvider,
            ]
        );
    }

    /**
     * Среднее значение по бренду
     *
     * @return string
     */
    public function actionAvgBrand()
    {
        $brands = (new PodruzkaProduct())->getBrandAvgPrice();

        return $this->render('avg_brand', ['brands' => $brands]);
    }

    /**
     * Среднее значение по категории
     *
     * @return string
     */
    public function actionAvgCategory()
    {
        $category = (new PodruzkaProduct())->getCategoryAvgPrice();

        return $this->render('avg_category', ['category' => $category]);
    }

    /**
     * Среднее значение по сопоставленным
     *
     * @return string
     */
    public function actionAvgMatching()
    {
        $brands = (new PodruzkaProduct())->getBrandCategoryAvgPrice();

        return $this->render('avg_matching', ['brands' => $brands]);
    }

    /**
     * Вывод сравнения цен
     */
    public function actionPriceMatching()
    {
        $searchModel = new PodruzkaProductSearch();
        $condition = [];

        if (isset(Yii::$app->request->queryParams['PodruzkaProductSearch'])) {
            $params = Yii::$app->request->queryParams['PodruzkaProductSearch'];
            if ($params['arrival']) {
                $condition['arrival'] = $params['arrival'];
            }
            if ($params['category']) {
                $condition['category'] = $params['category'];
            }
            if ($params['brand']) {
                $condition['brand'] = $params['brand'];
            }
        }
        $dataProvider = $searchModel->searchPriceMatching(Yii::$app->request->queryParams);

        return $this->render(
            'price_matching',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'condition' => $condition,
            ]
        );
    }

    public function actionNewProduct()
    {

        // параметры по умолчанию
        $params = Yii::$app->request->queryParams;
        $condition = [];
        $price = [];
        $model = new LetualProduct();
        $partner = '';

        if (!empty($params['partner'])) {
            switch ($params['partner']) {
                case 'ile':
                    $searchModel = new IledebeauteProductSearch();
                    if (!empty($params['IledebeauteProductSearch'])) {
                        $condition = $params['IledebeauteProductSearch'];
                    }
                    if ($params['date']) {
                        $condition['created_at'] = $params['date'];
                    }
                    $price = ['new_price', 'old_price'];
                    $model = new IledebeauteProduct();
                    $partner = 'Иль Де Ботэ';
                    break;
                case 'riv':
                    $searchModel = new RivegaucheProductSearch();
                    if (!empty($params['RivegaucheProductSearch'])) {
                        $condition = $params['RivegaucheProductSearch'];
                    }
                    if ($params['date']) {
                        $condition['created_at'] = $params['date'];
                    }
                    $price = ['price', 'blue_price', 'gold_price'];
                    $model = new RivegaucheProduct();
                    $partner = 'РивГош';
                    break;
                case 'let':
                    $searchModel = new LetualProductSearch();
                    if (!empty($params['LetualProductSearch'])) {
                        $condition = $params['LetualProductSearch'];
                    }
                    if ($params['date']) {
                        $condition['created_at'] = $params['date'];
                    }
                    $price = ['new_price', 'old_price'];
                    $model = new LetualProduct();
                    $partner = 'Летуаль';
                    break;
                case 'eli':
                    $searchModel = new ElizeProductSearch();
                    if (!empty($params['ElizeProductSearch'])) {
                        $condition = $params['ElizeProductSearch'];
                    }
                    if ($params['date']) {
                        $condition['created_at'] = $params['date'];
                    }
                    $price = ['new_price', 'old_price'];
                    $model = new ElizeProduct();
                    $partner = 'Элизэ';
                    break;
            }
        }
        if (!empty($searchModel)) {
            $dataProvider = $searchModel->searchNewProduct(Yii::$app->request->queryParams);

            return $this->render(
                'new_product',
                [
                    'model' => $model,
                    'partner' => $partner,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'condition' => $condition,
                    'price' => $price,
                ]
            );
        }
    }
}
