<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tks Preferencies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tks-preferencies-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Preference', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            // 'ID',
            'PreferenceName',
            'Value',
            'Min',
            'Max',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>