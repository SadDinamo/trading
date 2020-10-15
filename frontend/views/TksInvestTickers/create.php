<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TksInvestTickers */

$this->title = 'Create Tks Invest Tickers';
$this->params['breadcrumbs'][] = ['label' => 'Tks Invest Tickers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tks-invest-tickers-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
