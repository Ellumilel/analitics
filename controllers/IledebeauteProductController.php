<?php

namespace app\controllers;

use Yii;
use app\models\IledebeauteProduct;
use app\models\IledebeauteProductSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * IledebeauteProductController implements the CRUD actions for IledebeauteProduct model.
 */
class IledebeauteProductController extends Controller
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
                            'view',
                            'create',
                            'update',
                            'delete',
                            'brand-update',
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
     * Lists all IledebeauteProduct models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new IledebeauteProductSearch();
        $condition = [];
        if (isset(Yii::$app->request->queryParams['IledebeauteProductSearch'])) {
            $params = Yii::$app->request->queryParams['IledebeauteProductSearch'];

            if ($params['group']) {
                $condition['group'] = $params['group'];
            }
            if ($params['category']) {
                $condition['category'] = $params['category'];
            }
            if ($params['sub_category']) {
                $condition['sub_category'] = $params['sub_category'];
            }
            if ($params['brand']) {
                $condition['brand'] = $params['brand'];
            }
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'condition' => $condition,
        ]);
    }

    /**
     * Displays a single IledebeauteProduct model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new IledebeauteProduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new IledebeauteProduct();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing IledebeauteProduct model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing IledebeauteProduct model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the IledebeauteProduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IledebeauteProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IledebeauteProduct::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @return string
     */
    public function actionBrandUpdate()
    {
        $post = Yii::$app->request->post();
        if (!empty($post['editableKey'])
            && !empty($model = $this->findModel($post['editableKey']))
            && $post['IledebeauteProduct'][0]['brand']
        ) {
            $brand = strtoupper($post['IledebeauteProduct'][0]['brand']);
            $model->brand = $brand;
            if ($model->save(true)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
