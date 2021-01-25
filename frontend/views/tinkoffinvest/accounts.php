<?php
use yii\helpers\Html;
use common\models\TksPreferencies;

$this->title = 'API ' . TksPreferencies::findByPreferenceName('ClientType')->Value .
  ' accounts';
?>

<div>
  <h1><?= Html::encode($this->title) ?></h1>
  <?
  var_dump($a);
  ?>
</div>