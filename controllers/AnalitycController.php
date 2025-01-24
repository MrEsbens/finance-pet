<?php

namespace app\controllers;

use yii\web\Controller;
use Yii;

class AnalyticsController extends Controller
{
    public function actionIndex()
    {
        $from = Yii::$app->request->get('from');
        $to = Yii::$app->request->get('to');
        return $this->render('analytics-page');
    }
}