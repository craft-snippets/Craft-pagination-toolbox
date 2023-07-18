<?php

namespace craftsnippets\paginationtoolbox\models;

use craftsnippets\paginationtoolbox\PaginationToolbox;

use Craft;
use craft\base\Model;
use craft\helpers\Template;
use craftsnippets\paginationtoolbox\assetbundles\PaginationCssAsset;


class PaginationWidget extends Model
{

const DEFAULT_PAGINATION_TEMPLATE = 'pagination-toolbox/pagination';

public $links;
public $options;
public $pageInfo;

public $ariaLabel;
public $cssClassNav;
public $cssClassUl;
public $cssClassLi;

public function init(): void
{
	// options
	$defaultOptions = [
		'cssClassNav' => PaginationToolbox::getInstance()->pagination::DEFAULT_CSS_CLASS_NAV,
		'cssClassUl' => PaginationToolbox::getInstance()->pagination::DEFAULT_CSS_CLASS_UL,
		'cssClassLi' => PaginationToolbox::getInstance()->pagination::DEFAULT_CSS_CLASS_LI,
		'addSeoTags' => PaginationToolbox::getInstance()->pagination::ADD_PAGINATION_SEO_TAGS,
		'includeDefaultCss' => PaginationToolbox::getInstance()->pagination::INCLUDE_DEFAULT_CSS,
	];
	$this->options = (array) $this->options;
	$this->options = array_merge($defaultOptions,  $this->options);
	$this->options = (object) $this->options;

	//assign attrs
	$this->ariaLabel = Craft::t('pagination-toolbox', 'pagination');
	$this->cssClassNav = $this->options->cssClassNav;
	$this->cssClassUl = $this->options->cssClassUl;
	$this->cssClassLi = $this->options->cssClassLi;
}

public function getLinks()
{
	return $this->links;
}

public function render()
{

	// seo tags
	if($this->options->addSeoTags == true){
		$this->addSeoTags();
	}

	// default css
	if($this->options->includeDefaultCss == true){
		$this->registerCSSAssets();
	}

	$context = [
		// 'paginationLinks' => $this->links,
		// 'cssClassNav' => $this->cssClassNav,
		// 'cssClassUl' => $this->cssClassUl,
		// 'cssClassLi' => $this->cssClassLi,
		// 'ariaLabel' => $this->ariaLabel,
		'pagination' => $this,
	];
	$template = self::DEFAULT_PAGINATION_TEMPLATE;

	$html = Craft::$app->getView()->renderTemplate(
	    $template, 
	    $context,
	    Craft::$app->view::TEMPLATE_MODE_SITE
	);
	$html = Template::raw($html);
	return $html;	
}

public function addSeoTags()
{
	$plugins = Craft::$app->getPlugins();
    if($plugins->isPluginEnabled('seomatic')){
    	\nystudio107\seomatic\services\Helper::paginate($this->pageInfo);
    }else{
    	$html = '';
    	if(!is_null($this->pageInfo->prevUrl)){    		
    		$html .= '<link href="' . $this->pageInfo->prevUrl . '" rel="prev">';
    		$html .= "\n";
    	}
    	if(!is_null($this->pageInfo->nextUrl)){
    		$html .= '<link href="' . $this->pageInfo->nextUrl . '" rel="next">';
    		$html .= "\n";
    	}
    	Craft::$app->view->registerHtml($html, Craft::$app->view::POS_HEAD);
    }
}

public function registerCSSAssets()
{
    if(Craft::$app->getRequest()->getIsSiteRequest() == false){
        return;
    }
	Craft::$app->view->registerAssetBundle(PaginationCssAsset::class);
}

}
