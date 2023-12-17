<?php

namespace craftsnippets\paginationtoolbox\services;

use Craft;
use craft\base\Component;

use craft\helpers\Template;
use craft\helpers\Html;

use craftsnippets\paginationtoolbox\models\PaginationLink;
use craftsnippets\paginationtoolbox\models\PaginationInput;
use craftsnippets\paginationtoolbox\models\PaginationWidget;


use craftsnippets\paginationtoolbox\PaginationToolbox;
use craftsnippets\paginationtoolbox\assetbundles\PaginationToolboxAsset;
use craftsnippets\paginationtoolbox\helpers\Common;

use craft\web\twig\variables\Paginate;

class PaginationService extends Component
{
	// common
	const DEFAULT_TEXT_PREV = '&#10094;';
	const DEFAULT_TEXT_NEXT = '&#10095;';
	const DEFAULT_TEXT_ELLIPSIS = '&hellip;';
	const DEFAULT_NEIGHBOUS_NUMBER = 2;
	const SHOW_INACTIVE_PREV_NEXT = false;

	const DEFAULT_SHOW_NUMBERS = true;
	const DEFAULT_SHOW_ELLIPSIS = true;
	const DEFAULT_SHOW_PREV_NEXT = true;

	const ADD_PAGINATION_SEO_TAGS = true;
	const INCLUDE_DEFAULT_CSS = true;

	// ellipsis type
	const SHOW_NUMBER_INPUT = false;

	// logarythmic type
	const LOGARITMIC_DEFAULT_GROUPS = 7;
	const LOGARITMIC_DEFAULT_SHOW_ELLIPSIS = true;

	// dynamic
	const DEFAULT_PAGINATION_CONTAINER_DATA_ATTRIBUTE = 'dynamic-pagination-container';
	// const DEFAULT_PAGINATION_LIST_DATA_ATTRIBUTE = 'dynamic-pagination-list';
	const DEFAULT_NUMBER_DATA_ATTRIBUTE = 'dynamic-pagination-link-number';
	const DEFAULT_LINK_DISABLED_ATTRIBUTE = 'dynamic-pagination-link-disabled';
	const DYNAMIC_PAGINATION_ENABLE_SCROLL = true;	
	const DYNAMIC_PAGINATION_ENABLE_PRELOADER = true;

	// css
	const DEFAULT_CSS_CLASS_NAV = 'pagination';
	const DEFAULT_CSS_CLASS_UL = 'pagination-list';
	const DEFAULT_CSS_CLASS_LI = 'pagination-single';
	const DEFAULT_CSS_CLASS_LOADING = 'pagination-loading';

	// link
	const DEFAULT_TEMPLATE = 'pagination-toolbox/pagination-link';
	const DEFAULT_CSS_CLASS_CURRENT = 'is-current';
	const DEFAULT_CSS_CLASS_LINK = 'pagination-link';
	const DFAULT_CSS_CLASS_ELLIPSIS = 'pagination-ellipsis';

	// input
	const DEFAULT_CSS_CLASS_INPUT_WIDGET = 'pagination-input';
	const DEFAULT_CSS_CLASS_INPUT_ELEMENT = 'pagination-input__element';
	const DEFAULT_CSS_CLASS_INPUT_ELEMENT_OUTER = 'pagination-input__element-outer';
	const DEFAULT_CSS_CLASS_INPUT_LABEL = 'pagination-input__label';
	const DEFAULT_CSS_CLASS_INPUT_TOTAL = 'pagination-input__total';

	// types
	const PAGINATION_TYPE_ELLIPSIS = 'ellipsis';
	const PAGINATION_TYPE_LOGARITHMIC = 'logarithmic';

	public function init(): void
	{

	}

	public function getPagination(Paginate $pageInfo, array $options): PaginationWidget
	{
		$defaultOptions = [
			'textPrev' => self::DEFAULT_TEXT_PREV,
			'textNext' => self::DEFAULT_TEXT_NEXT,
			'showNumberWidget' => self::SHOW_NUMBER_INPUT,
			'showNumbers' => self::DEFAULT_SHOW_NUMBERS,
			'showEllipsis' => self::DEFAULT_SHOW_ELLIPSIS,
			'showPrevNext' => self::DEFAULT_SHOW_PREV_NEXT,
			'showInactivePrevNext' => self::SHOW_INACTIVE_PREV_NEXT,

			// link
			'cssClassCurrent' => self::DEFAULT_CSS_CLASS_CURRENT,
			'cssClassLink' => self::DEFAULT_CSS_CLASS_LINK,
			'cssClassEllipsis' => self::DFAULT_CSS_CLASS_ELLIPSIS,

			// number widget
			'cssClassNumberWidget' => self::DEFAULT_CSS_CLASS_INPUT_WIDGET,
			'cssClassNumberWidgetInput' => self::DEFAULT_CSS_CLASS_INPUT_ELEMENT,
			'cssClassNumberWidgetInputOuter' => self::DEFAULT_CSS_CLASS_INPUT_ELEMENT_OUTER,
			'cssClassNumberWidgetLabel' => self::DEFAULT_CSS_CLASS_INPUT_LABEL,
			'numberWidgetInstruction' => Craft::t('pagination-toolbox', 'Enter the page number you want to visit'),
			'numberWidgetLabelOutOf' => Craft::t('pagination-toolbox', 'out of'),

			// type
			'type' => self::PAGINATION_TYPE_ELLIPSIS,

			// only ellipsis
			'neighbours' => self::DEFAULT_NEIGHBOUS_NUMBER,
			'textEllipsisBefore' => self::DEFAULT_TEXT_ELLIPSIS,
			'textEllipsisAfter' => self::DEFAULT_TEXT_ELLIPSIS,

			// only logarithmic			
			'groups' => self::LOGARITMIC_DEFAULT_GROUPS,
			'textEllipsis' => self::DEFAULT_TEXT_ELLIPSIS,

		];
		$options = array_merge($defaultOptions, $options);
		$options = (object) $options;	

		// get pagination object
		if($options->type == self::PAGINATION_TYPE_ELLIPSIS){
			$paginationObject = $this->ellipsisTypeGetData($pageInfo, $options);
		}elseif($options->type == self::PAGINATION_TYPE_LOGARITHMIC){
			$paginationObject = $this->logarithmicTypeGetData($pageInfo, $options);
		}else{
			$paginationObject = $this->ellipsisTypeGetData($pageInfo, $options);
		}

		return $paginationObject;
	}

	private function ellipsisTypeGetData(Paginate $pageInfo, object $options): PaginationWidget
	{

		$links = [];

		// previous
		if($options->showPrevNext && (!is_null($pageInfo->prevUrl) || $options->showInactivePrevNext)){
			$links[] = new PaginationLink(
				[
					'linkContent' => $options->textPrev,
					'linkAltText' => Craft::t('pagination-toolbox', 'previous page'),
					'linkNumber' => ($pageInfo->currentPage - 1),
					'linkUrl' => $pageInfo->prevUrl,
					'isDisabled' => is_null($pageInfo->prevUrl),
					'options' => $options,
				]
			);
		}

		// first
		if($options->showNumbers && ($pageInfo->currentPage - $options->neighbours > 1)){
			$links[] = new PaginationLink(
				[
					'linkContent' => '1',
					'linkAltText' => Craft::t('pagination-toolbox', 'go to page {number}', ['number' => '1']),
					'linkNumber' => 1,
					'linkUrl' => $pageInfo->firstUrl,
					'options' => $options,
				]
			);
		}

		// ellipsis before current
		if($options->showEllipsis && ($pageInfo->currentPage - $options->neighbours > 2)){
			$links[] = new PaginationLink(
				[
					'linkContent' => $options->textEllipsisBefore,
					'isEllipsis' => true,
					'options' => $options,
				]
			);
		}

		// links before current
		if($options->showNumbers){
			foreach ($pageInfo->getPrevUrls($options->neighbours) as $linkNumber => $singleLinkUrl) {
				$links[] = new PaginationLink(
					[
						'linkContent' => $linkNumber,
						'linkAltText' => Craft::t('pagination-toolbox', 'go to page {number}', ['number' => $linkNumber]),
						'linkNumber' => $linkNumber,
						'linkUrl' => $singleLinkUrl,
						'options' => $options,
					]
				);			
			}			
		}


		// current
		if($options->showNumbers){
			$links[] = new PaginationLink(
				[
					'linkContent' => $pageInfo->currentPage,
					'linkAltText' => Craft::t('pagination-toolbox', 'current page'),
					'linkNumber' => $pageInfo->currentPage,
					'linkUrl' => null,
					'isCurrent' => true,
					'options' => $options,
				]
			);
		}

		// links after current
		if($options->showNumbers){
			foreach ($pageInfo->getNextUrls($options->neighbours) as $linkNumber => $singleLinkUrl) {
				$links[] = new PaginationLink(
					[
						'linkContent' => $linkNumber,
						'linkAltText' => Craft::t('pagination-toolbox', 'go to page {number}', ['number' => $linkNumber]),
						'linkNumber' => $linkNumber,
						'linkUrl' => $singleLinkUrl,
						'options' => $options,
					]
				);			
			}
		}

		// foreach ($pageInfo->getDynamicRangeUrls($options->neighbours) as $linkNumber => $singleLinkUrl) {
		// 	$links[] = new PaginationLink(
		// 		[
		// 			'linkContent' => $linkNumber,
		// 			'linkAltText' => Craft::t('pagination-toolbox', 'go to page {number}', ['number' => $linkNumber]),
		// 			'linkNumber' => $linkNumber,
		// 			'linkUrl' => $singleLinkUrl,
		// 			'isCurrent' => $linkNumber == $pageInfo->currentPage,
		// 		]
		// 	);			
		// }

		// ellipsis after current
		if($options->showEllipsis && ($pageInfo->totalPages - $pageInfo->currentPage > $options->neighbours + 1)){
			$links[] = new PaginationLink(
				[
					'linkContent' => $options->textEllipsisAfter,
					'isEllipsis' => true,
					'options' => $options,
				]
			);
		}

		// last
		if($options->showNumbers && ($pageInfo->currentPage + $options->neighbours < $pageInfo->totalPages)){
			$links[] = new PaginationLink(
				[
					'linkContent' => $pageInfo->totalPages,
					'linkAltText' => Craft::t('pagination-toolbox', 'go to page {number}', ['number' => $pageInfo->totalPages]),
					'linkNumber' => $pageInfo->totalPages,
					'linkUrl' => $pageInfo->lastUrl,
					'options' => $options,
				]
			);
		}

		// input numer
		if($options->showNumberWidget){
			$links[] = $this->getPaginationInput($pageInfo, $options);
		}

		// next
		if($options->showPrevNext && (!is_null($pageInfo->nextUrl) || $options->showInactivePrevNext)){
			$links[] = new PaginationLink(
				[
					'linkContent' => $options->textNext,
					'linkAltText' => Craft::t('pagination-toolbox', 'next page'),
					'linkNumber' => ($pageInfo->currentPage + 1),
					'linkUrl' => $pageInfo->nextUrl,
					'isDisabled' => is_null($pageInfo->nextUrl),
					'options' => $options,
				]
			);
		}		

		$widget = new PaginationWidget(
				[
					'links' => $links,
					'options' => $options,
					'pageInfo' => $pageInfo,
				]
			);	
		return $widget;
	}


	private function logarithmicTypeGetData(Paginate $pageInfo, object $options): PaginationWidget
	{
		// get groups
		$lastPage = $pageInfo->totalPages;
		$currentPage = $pageInfo->currentPage;
		$steps = $options->groups;
		$firstPage = 1;
		$addGaps = $options->showEllipsis;

		$pagesList = \Forrest79\Pagination\PagesFactory::logarithmic(
			$lastPage,
			$currentPage,
			$steps,
			$firstPage,
			true, // addGaps in this liblary dosnt actually do anything for some reason so its hardcoded here
		);

		$links = [];

		// previous
		if($options->showPrevNext && (!is_null($pageInfo->prevUrl) || $options->showInactivePrevNext)){
				$links[] = new PaginationLink(
					[
						'linkContent' => $options->textPrev,
						'linkAltText' => Craft::t('pagination-toolbox', 'previous page'),
						'linkNumber' => ($pageInfo->currentPage - 1),
						'linkUrl' => $pageInfo->prevUrl,
						'isDisabled' => is_null($pageInfo->prevUrl),
						'options' => $options,
					]
				);
		}

		foreach ($pagesList as $singlePage) {
			if(is_null($singlePage) && $addGaps == true){
				if($options->showEllipsis){
					$links[] = new PaginationLink(
						[
							'linkContent' => $options->textEllipsis,
							'isEllipsis' => true,
							'options' => $options,
						]
					);
				}
			}
			if(is_numeric($singlePage)){
				if($options->showNumbers){
					$links[] = new PaginationLink(
						[
							'linkContent' => $singlePage,
							'linkAltText' => Craft::t('pagination-toolbox', 'go to page {number}', ['number' => $singlePage]),
							'linkNumber' => $singlePage,
							'linkUrl' => $pageInfo->getPageUrl($singlePage),
							'isCurrent' => $pageInfo->currentPage == $singlePage,
							'options' => $options,
						]
					);					
				}
			}
		}

		// input numer
		if($options->showNumberWidget){
			$links[] = $this->getPaginationInput($pageInfo, $options);
		}

		// next
		if($options->showPrevNext && (!is_null($pageInfo->nextUrl) || $options->showInactivePrevNext)){
			$links[] = new PaginationLink(
				[
					'linkContent' => $options->textNext,
					'linkAltText' => Craft::t('pagination-toolbox', 'next page'),
					'linkNumber' => ($pageInfo->currentPage + 1),
					'linkUrl' => $pageInfo->nextUrl,
					'isDisabled' => is_null($pageInfo->nextUrl),
					'options' => $options,
				]
			);
		}

		$widget = new PaginationWidget(
				[
					'links' => $links,
					'options' => $options,
					'pageInfo' => $pageInfo,
				]
			);	
		return $widget;
	}


	public function enableDynamicPagination(string $template, array $options): void
	{			
		$defaultOptions = [
			'scrollOnRequest' => self::DYNAMIC_PAGINATION_ENABLE_SCROLL,
			'cssClassLoading' => self::DEFAULT_CSS_CLASS_LOADING,
			'usePreloader' => self::DYNAMIC_PAGINATION_ENABLE_PRELOADER,
		];
		$options = array_merge($defaultOptions, $options);
		$options = (object) $options;		

		// register js - if not ajax request
		if(!Craft::$app->getRequest()->getIsAjax()){
			$this->injectDynamicPaginationJs($template, $options);
		}
	}

    public function getBaseUrl()
    {
        $request = Craft::$app->request;
        $pageTrigger = Craft::$app->getConfig()->getGeneral()->getPageTrigger();
        $baseUrl = $request->getAbsoluteUrl();
        $baseUrl = explode('?', $baseUrl)[0];
        $baseUrl = preg_replace('/\/' . $pageTrigger . '\d+/', '', $baseUrl);
        return $baseUrl;
    }

	private function injectDynamicPaginationJs(string $template, object $options): void
	{
        if(Craft::$app->getRequest()->getIsSiteRequest() == false){
            return;
        }

		$wrapperSelector = '[data-' . self::DEFAULT_PAGINATION_CONTAINER_DATA_ATTRIBUTE . ']';
		// $listSelector = '[data-' . self::DEFAULT_PAGINATION_LIST_DATA_ATTRIBUTE . ']';

		// data to send with ajax request
		$requestData = [];

//		$baseUrl = $pageInfo->getPageUrl(1);
//		$baseUrl = explode('?', $baseUrl)[0];

        $baseUrl = $this->getBaseUrl();
		$queryParams = Craft::$app->getRequest()->queryStringWithoutPath;
		if($queryParams != ''){
			$queryParams = '?' . $queryParams;
		}

		$requestData[PaginationToolbox::$plugin->getSettings()->paramBaseUrl] = $baseUrl;
		$requestData[PaginationToolbox::$plugin->getSettings()->paramTemplate] = Craft::$app->getSecurity()->hashData($template);
		$requestData[PaginationToolbox::$plugin->getSettings()->paramQueryParams] = $queryParams;

		// include also url params already existing in url, but ignore default page param
		$initialRequest = Craft::$app->getRequest()->get();
		unset($initialRequest[Craft::$app->getConfig()->getGeneral()->pathParam]);
		$requestData = array_merge($requestData, $initialRequest);

		$dynamicPaginationSettings = [
			'requestData' => $requestData,
			'endpointUrl' => \craft\helpers\UrlHelper::actionUrl('pagination-toolbox/pagination/get-paginated-page'),
			'initialPage' => Craft::$app->getRequest()->getPageNum(),
			'pageTrigger' => Craft::$app->getConfig()->getGeneral()->getPageTrigger(),
			'wrapperSelector' => $wrapperSelector,
			'cssClassLoading' => $options->cssClassLoading,
			'usePreloader' => $options->usePreloader,
			// 'listSelector' => $listSelector,
			'linkNumberDataAttribute' => self::DEFAULT_NUMBER_DATA_ATTRIBUTE,
			'linkDisabledAttribute' => self::DEFAULT_LINK_DISABLED_ATTRIBUTE,
			'scrollOnRequest' => $options->scrollOnRequest,
		];


		$jsString = '
			let dynamicPaginationSettings = ' . json_encode($dynamicPaginationSettings) .';
		';

		Craft::$app->view->registerJs(
		    $jsString,
		    Craft::$app->view::POS_END
		);

//        $obj = new PaginationToolboxAsset;
//        $path = $obj->sourcePath . '/' . $obj->js[0];
//        $jsString = file_get_contents($path);
//        Craft::$app->view->registerJs(
//            $jsString,
//            Craft::$app->view::POS_END
//        );

        Common::insertAssetBundle(PaginationToolboxAsset::class);

	}

	public function getContainerAttribute(): string
	{
		$attribute = 'data-' . self::DEFAULT_PAGINATION_CONTAINER_DATA_ATTRIBUTE;
		return $attribute;
	}

	public function renderPaginatedPage()
	{
	    $request = Craft::$app->getRequest();
	    $templateEncoded = $request->get(PaginationToolbox::$plugin->getSettings()->paramTemplate);
	    $templateDecoded = Craft::$app->security->validateData($templateEncoded);
	    if($templateDecoded === false){
	        throw new ServerErrorHttpException();
	    }

	    $context = [
	        
	    ];

	    $html = Craft::$app->getView()->renderTemplate(
	        $templateDecoded, 
	        $context,
	        Craft::$app->view::TEMPLATE_MODE_SITE
	    );
	    $html = Template::raw($html);
	    return $html;
	}


	private function getPaginationInput(Paginate $pageInfo, object $options): PaginationInput
	{
		$paginationInput = new PaginationInput(
			[
				'pageInfo' => $pageInfo,
				'numberWidgetInstruction' => $options->numberWidgetInstruction,
				'numberWidgetLabelOutOf' => $options->numberWidgetLabelOutOf,
				'number' => $pageInfo->currentPage,
				'total' => $pageInfo->totalPages,
				'options' => $options,
			]
		);

		return $paginationInput;	
	}

}
