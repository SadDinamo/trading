<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use common\models\TksPreferencies;

AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name . ' - ' . TksPreferencies::findByPreferenceName('ClientType')->Value,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        $menuItems = [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Настройки', 'url' => ['/tkspreferencies/index']],
            ['label' => 'Отчеты', 'items' => [
                '<li class="dropdown-header">Внутренняя база данных</li>',
                ['label' => 'Список всех тикеров', 'url' => ['/tksinvesttickers/index']],
                '<li class="divider"></li>',
                '<li class="dropdown-header">Парсинг сайтов</li>',
                ['label' => 'Маржинальные тикеры Tinkoff', 'url' => ['/parser/marginsharesarray']],
                ['label' => 'Yahoo.finance - информация по тикеру', 'url' => ['/parser/yahootickerinfo']],
                '<li class="divider"></li>',
                '<li class="dropdown-header">Запросы к API</li>',
                ['label' => 'Акции', 'url' => ['/tinkoffinvest/stocks']],
                ['label' => 'Облигации', 'url' => ['/tinkoffinvest/bonds']],
                ['label' => 'ETF', 'url' => ['/tinkoffinvest/etfs']],
                ['label' => 'Валюты', 'url' => ['/tinkoffinvest/currencies']],
                ['label' => 'Портфель', 'url' => ['/tinkoffinvest/portfolio']],
                ['label' => 'Счета', 'url' => ['/tinkoffinvest/accounts']],
                ['label' => 'Баланс счета в выбранной валюте', 'url' => ['/tinkoffinvest/portfoliocurrencybalance']],
            ]],
            ['label' => 'Действия', 'items' => [
                ['label' => 'Обновить список тикеров', 'url' => ['/tksinvesttickers/tickerstodb']],
            ]],
            ['label' => 'Sandbox only', 'items' => [
                ['label' => 'Добавить денег', 'url' => ['#']],
            ]],
            //['label' => 'About', 'url' => ['/site/about']],
            //['label' => 'Contact', 'url' => ['/site/contact']],
        ];
        // if (Yii::$app->user->isGuest) {
        //     $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
        //     $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
        // } else {
        //     $menuItems[] = '<li>'
        //         . Html::beginForm(['/site/logout'], 'post')
        //         . Html::submitButton(
        //             'Logout (' . Yii::$app->user->identity->username . ')',
        //             ['class' => 'btn btn-link logout']
        //         )
        //         . Html::endForm()
        //         . '</li>';
        // }
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>