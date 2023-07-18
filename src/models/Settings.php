<?php

namespace craftsnippets\paginationtoolbox\models;

use craftsnippets\paginationtoolbox\PaginationToolbox;

use Craft;
use craft\base\Model;


class Settings extends Model
{
	public $paramBaseUrl = 'dynamicPaginationBaseUrl';
	public $paramTemplate = 'dynamicPaginationTemplate';
	public $paramQueryParams = 'dynamicPaginationQueryParams';
}
