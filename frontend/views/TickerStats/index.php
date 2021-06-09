<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TickerStatsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ticker Stats';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticker-stats-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Ticker Stats', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'ticker',
            'short_ratio',
            'short_percent_of_float',
            'ebitda',
            'total_cash',
            'total_debt',
            'operating_cash_flow',
            'date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php echo yii2mod\alert\Alert::widget(); ?>

    <?php Pjax::end(); ?>

</div>