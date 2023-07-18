<?php

namespace craftsnippets\paginationtoolbox\variables;

use craftsnippets\paginationtoolbox\PaginationToolbox;

use Craft;


class PaginationVariable
{

    public function getPagination($pageInfo, $options = [])
    {
        return PaginationToolbox::getInstance()->pagination->getPagination($pageInfo, $options);
    }

    public function enableDynamicPagination($pageInfo, $template, $options = [])
    {
        return PaginationToolbox::getInstance()->pagination->enableDynamicPagination($pageInfo, $template, $options);
    }

    public function getContainerAttribute()
    {
        return PaginationToolbox::getInstance()->pagination->getContainerAttribute();
    }

}
