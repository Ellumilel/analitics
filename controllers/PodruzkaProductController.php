<?php

namespace app\controllers;

use app\models\ElizeProduct;
use app\models\IledebeauteProduct;
use app\models\LetualProduct;
use app\models\RivegaucheProduct;
use Yii;
use app\models\PodruzkaProduct;
use app\models\PodruzkaProductSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use himiklab\jqgrid\actions\JqGridActiveAction;

/**
 * PodruzkaProductController implements the CRUD actions for PodruzkaProduct model.
 */
class PodruzkaProductController extends Controller
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
                            'matching',
                            'matchings',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'jqgrid',
                            'article-update',
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

    public function actions()
    {
        return [
            'jqgrid' => [
                'class' => JqGridActiveAction::className(),
                'model' => PodruzkaProductSearch::className(),
                'scope' => function ($query) {
                    /** @var \yii\db\ActiveQuery $query */
                    $query->select(
                        [
                            'podruzka_product.article as article',
                            'podruzka_product.title as title',
                            'podruzka_product.arrival',
                            'podruzka_product.group',
                            'podruzka_product.category',
                            'podruzka_product.sub_category',
                            'podruzka_product.detail',
                            'podruzka_product.brand',
                            'podruzka_product.sub_brand',
                            'podruzka_product.line',
                            'podruzka_product.price',
                            'podruzka_product.ma_price',
                            'letual_product.title as l_title',
                            'letual_product.article as l_article',
                            'letual_product.description as l_description',
                            'letual_product.old_price as l_old_price',
                            'letual_product.new_price as l_new_price',
                            'l_id',
                            'e_id',
                            'r_id',
                            'i_id',
                        ]
                    )
                        ->joinWith('l', true, 'LEFT JOIN')
                        ->joinWith('r', true, 'LEFT JOIN')
                        ->joinWith('i', true, 'LEFT JOIN')
                        ->joinWith('e', true, 'LEFT JOIN')
                    ;
                },
            ],
        ];
    }

    /**
     * Lists all PodruzkaProduct models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PodruzkaProductSearch();
        $condition = [];
        if (isset(Yii::$app->request->queryParams['PodruzkaProductSearch'])) {
            $params = Yii::$app->request->queryParams['PodruzkaProductSearch'];

            if ($params['group']) {
                $condition['group'] = $params['group'];
            }
            if ($params['category']) {
                $condition['category'] = $params['category'];
            }
            if ($params['sub_category']) {
                $condition['sub_category'] = $params['sub_category'];
            }
            if ($params['detail']) {
                $condition['detail'] = $params['detail'];
            }
            if ($params['brand']) {
                $condition['brand'] = $params['brand'];
            }
            if ($params['sub_brand']) {
                $condition['sub_brand'] = $params['sub_brand'];
            }
            if ($params['line']) {
                $condition['line'] = $params['line'];
            }
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render(
            'index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'condition' => $condition,
            ]
        );
    }

    /**
     * Lists all PodruzkaProduct models.
     *
     * @return mixed
     */
    public function actionMatching()
    {
        $searchModel = new PodruzkaProductSearch();
        $condition = [];
        if (isset(Yii::$app->request->queryParams['PodruzkaProductSearch'])) {
            $params = Yii::$app->request->queryParams['PodruzkaProductSearch'];

            if ($params['group']) {
                $condition['group'] = $params['group'];
            }
            if ($params['category']) {
                $condition['category'] = $params['category'];
            }
            if ($params['sub_category']) {
                $condition['sub_category'] = $params['sub_category'];
            }
            if ($params['detail']) {
                $condition['detail'] = $params['detail'];
            }
            if ($params['brand']) {
                $condition['brand'] = $params['brand'];
            }
            if ($params['sub_brand']) {
                $condition['sub_brand'] = $params['sub_brand'];
            }
            if ($params['line']) {
                $condition['line'] = $params['line'];
            }
        }
        $dataProvider = $searchModel->searchMatching(Yii::$app->request->queryParams);

        return $this->render(
            'matching',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'condition' => $condition,
            ]
        );
    }

    /**
     * Lists all PodruzkaProduct models.
     *
     * @return mixed
     */
    public function actionMatchings()
    {
        $searchModel = new PodruzkaProductSearch();
        $condition = [];
        if (isset(Yii::$app->request->queryParams['PodruzkaProductSearch'])) {
            $params = Yii::$app->request->queryParams['PodruzkaProductSearch'];

            if ($params['group']) {
                $condition['group'] = $params['group'];
            }
            if ($params['category']) {
                $condition['category'] = $params['category'];
            }
            if ($params['sub_category']) {
                $condition['sub_category'] = $params['sub_category'];
            }
            if ($params['detail']) {
                $condition['detail'] = $params['detail'];
            }
            if ($params['brand']) {
                $condition['brand'] = $params['brand'];
            }
            if ($params['sub_brand']) {
                $condition['sub_brand'] = $params['sub_brand'];
            }
            if ($params['line']) {
                $condition['line'] = $params['line'];
            }
        }
        $dataProvider = $searchModel->searchMatching(Yii::$app->request->queryParams);

        return $this->render(
            'matchings',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'condition' => $condition,
            ]
        );
    }

    /**
     * Displays a single PodruzkaProduct model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render(
            'view',
            [
                'model' => $this->findModel($id),
            ]
        );
    }

    /**
     * Creates a new PodruzkaProduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PodruzkaProduct();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render(
                'create',
                [
                    'model' => $model,
                ]
            );
        }
    }

    /**
     * Updates an existing PodruzkaProduct model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render(
                'update',
                [
                    'model' => $model,
                ]
            );
        }
    }

    /**
     * Deletes an existing PodruzkaProduct model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PodruzkaProduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return PodruzkaProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PodruzkaProduct::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @return string
     */
    public function actionArticleUpdate()
    {
        if ($pArticle = reset($_POST['PodruzkaProduct'])['article']) {
            if ($pp = PodruzkaProduct::find()->where(['article' => $pArticle])->one()) {
                if (!empty($_POST['l_id'])) {
                    if ($lp = LetualProduct::find()->where(['article' => $_POST['l_id']])->one()) {
                        $pp->l_id = $lp->id;
                    }
                } else {
                    $pp->l_id = null;
                }

                if (!empty($_POST['r_id'])) {
                    if ($rp = RivegaucheProduct::find()->where(['article' => $_POST['r_id']])->one()) {
                        $pp->r_id = $rp->id;
                    }
                } else {
                    $pp->r_id = null;
                }

                if (!empty($_POST['i_id'])) {
                    if ($ip = IledebeauteProduct::find()->where(['article' => $_POST['i_id']])->one()) {
                        $pp->i_id = $ip->id;
                    }
                } else {
                    $pp->i_id = null;
                }

                if (!empty($_POST['e_id'])) {
                    if ($ep = ElizeProduct::find()->where(['article' => $_POST['e_id']])->one()) {
                        $pp->e_id = $ep->id;
                    }
                } else {
                    $pp->e_id = null;
                }

                $pp->save();

                return json_encode(['result' => true]);
            }
        }

        return json_encode(['result' => false]);
    }
}
