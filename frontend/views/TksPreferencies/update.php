<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TksPreferencies */

$this->title = 'Update Tks Preferencies: ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Tks Preferencies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tks-preferencies-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
