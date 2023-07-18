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
        if(!PaginationToolbox::getInstance()->isProEdition()){
            throw new \Exception('Dynamic pagination functionality requires the PRO edition of the plugin.');
        }        
        return PaginationToolbox::getInstance()->pagination->renderPaginatedPage();
    }

}
