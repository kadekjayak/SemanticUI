<?php
namespace SemanticUI\View\Helper;

use Cake\Utility\Inflector;
use Cake\View\Helper;
use Cake\View\Helper\PaginatorHelper;
use Cake\View\StringTemplateTrait;
use Cake\View\View;

/**
 * SemanticPaginator helper
 */
class SemanticPaginatorHelper extends PaginatorHelper
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'options' => [],
        'templates' => [
            'nextActive' => '<a class="item active" rel="next" href="{{url}}">{{text}}</a>',
            'nextDisabled' => '<a class="item active" href="" onclick="return false;">{{text}}</a>',
            'prevActive' => '<a class="item active" rel="prev" href="{{url}}">{{text}}</a>',
            'prevDisabled' => '<a class="item disabled" href="" onclick="return false;">{{text}}</a>',
            'counterRange' => '{{start}} - {{end}} of {{count}}',
            'counterPages' => '{{page}} of {{pages}}',
            'first' => '<li class="first"><a href="{{url}}">{{text}}</a></li>',
            'last' => '<li class="last"><a href="{{url}}">{{text}}</a></li>',
            'number' => '<a class="item" href="{{url}}">{{text}}</a>',
            'current' => '<a class="active item" href="{{url}}">{{text}}</a></li>',
            'ellipsis' => '<li class="ellipsis">...</li>',
            'sort' => '<a href="{{url}}">{{text}}</a>',
            'sortAsc' => '<a class="asc" href="{{url}}">{{text}}</a>',
            'sortDesc' => '<a class="desc" href="{{url}}">{{text}}</a>',
            'sortAscLocked' => '<a class="asc locked" href="{{url}}">{{text}}</a>',
            'sortDescLocked' => '<a class="desc locked" href="{{url}}">{{text}}</a>',
        ]
    ];

}
