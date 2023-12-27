<?php

namespace craftsnippets\paginationtoolbox\controllers;

use Craft;
use craft\web\Controller;
use yii\web\ServerErrorHttpException;

use craft\helpers\Template;
use craftsnippets\paginationtoolbox\PaginationToolbox;

class PaginationController extends Controller
{

    protected array|int|bool $allowAnonymous = true;

    public function actionGetPaginatedPage()
    {
        $request = Craft::$app->getRequest();

        // template param
        $templateEncoded = $request->headers->get(PaginationToolbox::$plugin->getSettings()->paramTemplate);
        $template = Craft::$app->security->validateData($templateEncoded);
        if($template === false){
            throw new ServerErrorHttpException('Invalid parameter');
        }

        // variables param
        $variablesEncoded = $request->headers->get(PaginationToolbox::$plugin->getSettings()->paramVariables);
        $variables = Craft::$app->security->validateData($variablesEncoded);
        if($variables === false){
            throw new ServerErrorHttpException('Invalid parameter');
        }
        $variablesArray = json_decode($variables, true);

        return PaginationToolbox::getInstance()->pagination->renderPaginatedPage($template, $variablesArray);
    }

}
