<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

/**
 * The Controller for Language converting
 */
class LanguageController extends Controller
{
    public function actionIndex($lang = '')
    {
        $result = Yii::$app->lang->set($lang);

        return $this->redirect(Yii::$app->request->referrer ? : Yii::$app->homeUrl);
    }
}