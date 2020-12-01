<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Настройки';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tks-preferencies-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            // 'ID',
            'PreferenceName',
            'Value',
            'Min',
            'Max',
            ['class' => 'yii\grid\ActionColumn',
            'template' => '{update}'],
        ],
    ]); ?>
    
</div>