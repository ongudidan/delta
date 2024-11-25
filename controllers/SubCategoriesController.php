<?php

namespace app\controllers;

use app\models\SubCategories;
use app\models\SubCategoriesSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SubCategoriesController implements the CRUD actions for SubCategories model.
 */
class SubCategoriesController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'only' => ['logout', 'update', 'delete', 'create', 'view', 'index'],
                    'rules' => [
                        [
                            'actions' => ['logout', 'update', 'delete', 'create', 'view', 'index'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all SubCategories models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->can('sub-category-view')) {

            $searchModel = new SubCategoriesSearch();
            $dataProvider = $searchModel->search($this->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            $this->layout = 'LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }

    /**
     * Displays a single SubCategories model.
     * @param int $sub_category_id Sub Category ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($sub_category_id)
    {
        if (Yii::$app->user->can('sub-category-view')) {

            return $this->render('view', [
                'model' => $this->findModel($sub_category_id),
            ]);
        } else {
            $this->layout = 'LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }

    /**
     * Creates a new SubCategories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (Yii::$app->user->can('sub-category-create')) {

            $model = new SubCategories();

            if ($this->request->isPost) {
                if ($model->load($this->request->post()) && $model->save()) {
                    return $this->redirect(['view', 'sub_category_id' => $model->sub_category_id]);
                }
            } else {
                $model->loadDefaultValues();
            }

            return $this->render('create', [
                'model' => $model,
            ]);
        } else {
            $this->layout = 'LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }

    /**
     * Updates an existing SubCategories model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $sub_category_id Sub Category ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($sub_category_id)
    {
        if (Yii::$app->user->can('sub-category-update')) {

            $model = $this->findModel($sub_category_id);

            if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'sub_category_id' => $model->sub_category_id]);
            }

            return $this->render('update', [
                'model' => $model,
            ]);
        } else {
            $this->layout = 'LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }

    /**
     * Deletes an existing SubCategories model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $sub_category_id Sub Category ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($sub_category_id)
    {
        if (Yii::$app->user->can('sub-category-delete')) {

            $this->findModel($sub_category_id)->delete();

            return $this->redirect(['index']);
        } else {
            $this->layout = 'LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }

    /**
     * Finds the SubCategories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $sub_category_id Sub Category ID
     * @return SubCategories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($sub_category_id)
    {
        if (($model = SubCategories::findOne(['sub_category_id' => $sub_category_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
