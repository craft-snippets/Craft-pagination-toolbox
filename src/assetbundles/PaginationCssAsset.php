<?php

namespace craftsnippets\paginationtoolbox\assetbundles;

use Craft;
use craft\web\AssetBundle;
// use craft\web\assets\cp\CpAsset;


class PaginationCssAsset extends AssetBundle
{

    public function init()
    {
        $this->sourcePath = "@craftsnippets/paginationtoolbox/assetbundles";
        $this->css = [
            'default-pagination-style.css',
        ];
        parent::init();
    }
}
