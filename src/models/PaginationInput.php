<?php

namespace craftsnippets\paginationtoolbox\models;

use craftsnippets\paginationtoolbox\PaginationToolbox;

use Craft;
use craft\base\Model;
use craft\helpers\Template;


class PaginationInput extends Model
{

const DEFAULT_TEMPLATE = 'pagination-toolbox/pagination-input';
const ATTRIBUTE_ALL_LINKS = 'pagination-input-links';
const ATTRIBUTE_INPUT = 'pagination-input-widget';
const ATTRIBUTE_INPUT_MAX = 'pagination-max';

public $numberWidgetInstruction;
public $numberWidgetLabelOutOf;
public $number;
public $total;
public $allLinksAttribute;
public $inputAttribute;
public $inputAttributeMax;

public $numberDataAttribute;
public $cssClassNumberWidget;
public $cssClassNumberWidgetInput;
public $cssClassNumberWidgetInputOuter;
public $cssClassNumberWidgetLabel;
public $allLinks;

public $options;
public $pageInfo;

public function init(): void
{
	$this->allLinksAttribute = self::ATTRIBUTE_ALL_LINKS;
	$this->inputAttribute = self::ATTRIBUTE_INPUT;
	$this->inputAttributeMax = self::ATTRIBUTE_INPUT_MAX;

	$this->numberDataAttribute = PaginationToolbox::getInstance()->pagination::DEFAULT_NUMBER_DATA_ATTRIBUTE;
	$this->cssClassNumberWidget = $this->options->cssClassNumberWidget;
	$this->cssClassNumberWidgetInput = $this->options->cssClassNumberWidgetInput;
	$this->cssClassNumberWidgetInputOuter = $this->options->cssClassNumberWidgetInputOuter;
	$this->cssClassNumberWidgetLabel = $this->options->cssClassNumberWidgetLabel;

	$this->allLinks = $this->getLinks();
}

private function getLinks()
{
	$allLinks = [];
	foreach (range(1, $this->pageInfo->totalPages) as $pageNumber) {
		$allLinks[] = new PaginationLink(
			[
				'linkNumber' => $pageNumber,
				'linkUrl' => $this->pageInfo->getPageUrl($pageNumber),
			]
		);
	}
	return $allLinks;
}

public function render()
{
	$context = [
		// 'allLinks' => $this->allLinks,
		// 'numberWidgetInstruction' => $this->numberWidgetInstruction,
		// 'numberWidgetLabelOutOf' => $this->numberWidgetLabelOutOf,
		// 'number' => $this->number,
		// 'total' => $this->total,
		// 'allLinksAttribute' => $this->allLinksAttribute,
		// 'inputAttribute' => $this->inputAttribute,
		// 'numberDataAttribute' => $this->numberDataAttribute,
		// // css
		// 'cssClassNumberWidget' => $this->cssClassNumberWidget,
		// 'cssClassNumberWidgetInput' => $this->cssClassNumberWidgetInput,
		// 'cssClassNumberWidgetInputOuter' => $this->cssClassNumberWidgetInputOuter,
		// 'cssClassNumberWidgetLabel' => $this->cssClassNumberWidgetLabel,
		'inputWidget' => $this,
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

}
