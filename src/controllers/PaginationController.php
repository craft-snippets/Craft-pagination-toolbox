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
        return PaginationToolbox::getInstance()->pagination->renderPaginatedPage();
    }

}
