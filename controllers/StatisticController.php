<?php

namespace app\controllers;

use app\models\PodruzkaProduct;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

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
                        'actions' => ['index','avg-brand','avg-category','avg-matching'],
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
}
