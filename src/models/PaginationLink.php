<?php

namespace craftsnippets\paginationtoolbox\models;

use craftsnippets\paginationtoolbox\PaginationToolbox;

use Craft;
use craft\base\Model;
use craft\helpers\Template;


class PaginationLink extends Model
{

const DEFAULT_TEMPLATE = 'pagination-toolbox/pagination-link';

public $linkContent;
public $linkAltText;
public $linkNumber;
public $linkUrl;
public $isEllipsis;
public $isCurrent;
public $isDisabled;
public $numberDataAttribute;
public $linkDisabledAttribute;

public $options;

public function init(): void
{
	$this->changeUrlForDynamic();
	$this->numberDataAttribute = PaginationToolbox::getInstance()->pagination::DEFAULT_NUMBER_DATA_ATTRIBUTE;
	$this->linkDisabledAttribute = PaginationToolbox::getInstance()->pagination::DEFAULT_LINK_DISABLED_ATTRIBUTE;
}

private function changeUrlForDynamic()
{
	$request = Craft::$app->getRequest();
	if(
		// $request->getIsAjax() && 
		!is_null($this->linkUrl) &&
		!is_null($request->get(PaginationToolbox::$plugin->getSettings()->paramBaseUrl)) &&
		!is_null($request->get(PaginationToolbox::$plugin->getSettings()->paramTemplate)) &&
		!is_null($request->get(PaginationToolbox::$plugin->getSettings()->paramQueryParams))
	){
		$this->linkUrl = 
		$request->get(PaginationToolbox::$plugin->getSettings()->paramBaseUrl) . 
		'/' . 
		Craft::$app->getConfig()->getGeneral()->getPageTrigger() . 
		$this->linkNumber . 
		$request->get(PaginationToolbox::$plugin->getSettings()->paramQueryParams);
	}		
}

public function getCssClasses()
{
	$linkCssClasses = [$this->options->cssClassLink];
	if($this->isCurrent){
		$linkCssClasses[] = $this->options->cssClassCurrent;
	}
	if($this->isEllipsis){
		$linkCssClasses[] = $this->options->cssClassEllipsis;
	}
	return $linkCssClasses;		
}

public function render()
{

	$context = [
		// 'linkContent' => $this->linkContent,
		// 'linkAltText' => $this->linkAltText,
		// 'linkNumber' => $this->linkNumber,
		// 'linkUrl' => $this->linkUrl,
		// 'isEllipsis' => $this->isEllipsis,
		// 'isCurrent' => $this->isCurrent,
		// 'linkCssClasses' => $this->getCssClasses(),
		// 'numberDataAttribute' => $this->numberDataAttribute,
		// 'linkDisabledAttribute' => $this->linkDisabledAttribute,
		// 'isDisabled' => $this->isDisabled,
		'link' => $this,
	];

	$template = self::DEFAULT_TEMPLATE;

	$html = Craft::$app->getView()->renderTemplate(
	    $template, 
	    $context,
	    Craft::$app->view::TEMPLATE_MODE_SITE
	);
	$html = Template::raw($html);
	return $html;
}

public function getContent(){
	return Template::raw($this->linkContent);
}

public function getAltText(){
	return Template::raw($this->linkAltText);
}

public function getUrl()
{
	return $this->linkUrl;
}

}
