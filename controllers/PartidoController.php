<?php

namespace app\controllers;

use Yii;
use app\models\Partido;
use app\models\PartidoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Model;

/**
 * PartidoController implements the CRUD actions for Partido model.
 */
class PartidoController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Partido models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PartidoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Partido model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Partido model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Partido();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_partido]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Partido model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_partido]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Partido model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $fecha_id = $this->findModel($id)->fecha_id;
        $this->findModel($id)->delete();

        return $this->redirect(['crear-partido-por-fecha','fecha_id'=>$fecha_id]);
    }

    /**
     * Finds the Partido model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Partido the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Partido::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    function actionCrearPartidoPorFecha($fecha_nro)
    {
        
        $addPartido = Yii::$app->request->post('add-partido');
        if($addPartido){
            $partido = new Partido(['fecha_id'=>$fecha_nro]);
            $partido->save();
        }
        
        $partidos = Partido::find()->where(['fecha_id'=>$fecha_nro])->all();

        if (Model::loadMultiple($partidos, Yii::$app->request->post()) && Model::validateMultiple($partidos)) {
            foreach ($partidos as $partido) {
                $partido->save(false);
            }
//            return $this->redirect('index');
        }

        return $this->render('multiCreate', [
                    'partidos' => $partidos,
                    'fecha_nro' => $fecha_nro,
        ]);
        
    }
    public function actionCargarGoles($id){
        $model = Partido::findOne($id);
        return $this->render('cargarGoles',['partido'=>$model]);
    }
    
  
}
