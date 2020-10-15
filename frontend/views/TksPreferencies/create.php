<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TksPreferencies */

$this->title = 'Create Tks Preferencies';
$this->params['breadcrumbs'][] = ['label' => 'Tks Preferencies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tks-preferencies-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
