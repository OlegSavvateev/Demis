<?php

namespace app\controllers;

use Yii;
use app\models\Computation;
use app\models\ComputationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use app\models\Code;

/**
 * ComputationController implements the CRUD actions for Computation model.
 */
class ComputationController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
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
        ];
    }

    /**
     * Lists all Computation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ComputationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Computation model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Computation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $computation = new Computation();
        if ($computation->load(Yii::$app->request->post())) {
            $computation->setCodesFromIntArray($computation->findCodes());
            if ($computation->save()) {
                return $this->redirect(['view', 'id' => $computation->id]);
            } else {
                return $this->render('create', [
                    'model' => $computation,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $computation,
            ]);
        }
    }

    /**
     * Updates an existing Computation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $computation = $this->findModel($id);
        if ($computation->load(Yii::$app->request->post())) {
            $computation->setCodesFromIntArray($computation->findCodes());
            if ($computation->save()) {
                return $this->redirect(['view', 'id' => $computation->id]);
            } else {
                return $this->render('update', [
                    'model' => $computation,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $computation,
            ]);
        }
    }

    /**
     * Deletes an existing Computation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Computation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Computation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Computation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрошенная страница не существует.');
        }
    }
}
