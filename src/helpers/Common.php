<?php

namespace craftsnippets\paginationtoolbox\helpers;
use craft\web\AssetBundle;
use Craft;

class Common
{
    public static function insertAssetBundle(string $assetBundleClass, $asFile = false): void
    {
        if($asFile){
            Craft::$app->view->registerAssetBundle($assetBundleClass);
            return;
        }

        $obj = new $assetBundleClass;
        foreach ($obj->css as $cssFile) {
            $cssPath = $obj->sourcePath . '/' . $cssFile;
            $cssString = file_get_contents($cssPath);
            Craft::$app->view->registerCss(
                $cssString
            );
        }
        foreach ($obj->js as $jsFile) {
            $jsPath = $obj->sourcePath . '/' . $jsFile;
            $jsString = file_get_contents($jsPath);
            Craft::$app->view->registerJs(
                $jsString
            );
        }

        // Craft::$app->view::POS_END

    }
}