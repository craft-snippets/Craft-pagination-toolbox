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

    public function includePaginatedList(string $template, array $variables = [], array $options = [])
    {
        return PaginationToolbox::getInstance()->pagination->includePaginatedList($template, $variables, $options);
    }

}
