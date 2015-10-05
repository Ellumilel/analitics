<?php

namespace app\controllers;

use app\models\PodruzkaProduct;
use app\models\PodruzkaProductSearch;
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
                        'actions' => ['index','avg-brand','avg-category','avg-matching','price-matching'],
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
        return $this->render('index');
    }

    /**
     * Среднее значение по бренду
     *
     * @return string
     */
    public function actionAvgBrand()
    {
        $brands = (new PodruzkaProduct())->getBrandAvgPrice();
        return $this->render('avg_brand',['brands' => $brands]);
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
        return $this->render('avg_matching',['brands' => $brands]);
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

            if($params['group']) {
                $condition['group'] = $params['group'];
            }
            if($params['category']) {
                $condition['category'] = $params['category'];
            }
            if($params['sub_category']) {
                $condition['sub_category'] = $params['sub_category'];
            }
            if($params['detail']) {
                $condition['detail'] = $params['detail'];
            }
            if($params['brand']) {
                $condition['brand'] = $params['brand'];
            }
            if($params['sub_brand']) {
                $condition['sub_brand'] = $params['sub_brand'];
            }
            if($params['line']) {
                $condition['line'] = $params['line'];
            }
        }
        $dataProvider = $searchModel->searchPriceMatching(Yii::$app->request->queryParams);

        return $this->render('price_matching', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'condition' => $condition,
        ]);
    }
}
