<?php

namespace frontend\controllers;

use Yii;
use app\models\TickerStats;
use app\models\TickerStatsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\Parser;

/**
 * TickerstatsController implements the CRUD actions for TickerStats model.
 */
class TickerstatsController extends Controller
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
     * Lists all TickerStats models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TickerStatsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TickerStats model.
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
     * Creates a new TickerStats model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TickerStats();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TickerStats model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TickerStats model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TickerStats model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TickerStats the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TickerStats::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Adds one ticker info from:
     * frontend\controllers\ParserController::yahooTickerInfo($ticker)
     * to 'ticker_stats' db table
     * 
     * @param string $ticker
     */
    public function addTickerInfo($ticker='FIZZ'){
        $tickerInfo = Parser::yahooTickerInfo($ticker);
        if (TickerStats::find()->where(['ticker' => $ticker])->andwhere([
                'name' => 'Short Ratio', 
                'date' => $tickerInfo['Short Ratio']['date'],
            ])->one()) {
        } else {
            $tickerStats = new TickerStats;
            $tickerStats->ticker = $ticker;
            $tickerStats->name = 'Short Ratio';
            $tickerStats->date = $tickerInfo['Short Ratio']['date'];
            $tickerStats->value = $tickerInfo['Short Ratio']['value'];
            //var_dump($tickerStats);
            $tickerStats->save();
        }
    }

    /**
     * test - //TODO:: to delete
     */
    public function actionAddtickerinfo(){
        $this->addTickerInfo();
    }
}
