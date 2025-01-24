<?php

use Yii;

$container = Yii::$container;

$container->setSingleton('app\components\services\TransactionsService',[
    'class' => 'app\components\services\TransactionsService',
]);
$container->setSingleton('app\components\services\CategoriesService',[
    'class' => 'app\components\services\CategoriesService',
]);
$container->setSingleton('app\components\services\BudgetSheetsService',[
    'class' => 'app\components\services\BudgetSheetsService',
]);
$container->setSingleton('app\components\services\AnalitycService',[
    'class' => 'app\components\services\AnalitycService',
]);
$container->setSingleton('app\components\services\UserService',[
    'class' => 'app\components\services\UserService',
]);
