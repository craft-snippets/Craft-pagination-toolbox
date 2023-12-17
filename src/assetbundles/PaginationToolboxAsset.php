<?php

namespace craftsnippets\paginationtoolbox\assetbundles;

use Craft;
use craft\web\AssetBundle;
// use craft\web\assets\cp\CpAsset;


class PaginationToolboxAsset extends AssetBundle
{

    public function init()
    {
        $this->sourcePath = "@craftsnippets/paginationtoolbox/assetbundles";
        $this->js = [
            'ajax-pagination.js',
        ];
        parent::init();
    }
}
