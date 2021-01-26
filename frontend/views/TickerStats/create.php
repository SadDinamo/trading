<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TickerStats */

$this->title = 'Create Ticker Stats';
$this->params['breadcrumbs'][] = ['label' => 'Ticker Stats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticker-stats-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
