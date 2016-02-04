<?php
namespace SemanticUI\View\Helper;

use Cake\View\Helper;
use Cake\View\Helper\FormHelper;
use Cake\View\View;

use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Datasource\EntityInterface;
use Cake\Form\Form;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Cake\Utility\Security;
use Cake\View\Form\ArrayContext;
use Cake\View\Form\ContextInterface;
use Cake\View\Form\EntityContext;
use Cake\View\Form\FormContext;
use Cake\View\Form\NullContext;
use Cake\View\Helper\SecureFieldTokenTrait;
use Cake\View\StringTemplateTrait;
use Cake\View\Widget\WidgetRegistry;
use DateTime;
use RuntimeException;
use Traversable;
/**
 * SemanticForm helper
 */
class SemanticMenuHelper extends FormHelper
{
    public $helpers = ['Html', 'Form', 'Url'];


     /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'template' => [
            'container' => '<div class="ui fluid vertical text visible menu">{{items}}</div>',
            'itemContainer' => '{{item}}',
            'childMenuContainer' => '<div class="content menu">{{items}}</div>',
            'mainMenuItem' => '<a class="item" href="{{url}}">{{text}}</a>',
            'menuItem' => '<a class="item {{class}}" href="{{url}}">{{text}}</a>',
            'menuItemActive' => '<a class="item active" href="{{url}}">{{text}}</a>'
        ]
    ];

    public function render($data, $options = null){
        return $this->_parseMenu($data);
    }

    private function _parseMenu($menu){
        $template = $this->_defaultConfig['template'];
        $menuResult = '';
        foreach($menu as $item) {
            $menuResult .= str_replace('{{item}}', $this->_parseItem($item, ['template' => $this->_defaultConfig['template']['mainMenuItem']]), $template['itemContainer']); ;
        }
        $menuResult = str_replace('{{items}}', $menuResult, $template['container']);

        return $menuResult;
    }

    private function _parseItem($item, $options = array()){
        $template = $this->_defaultConfig['template'];
        if(isset($options['template'])) {
            $template['menuItem'] = $options['template'];
        }
        $active = false;
        $currentParams = $this->request->params;
        $url = $item['url'];

        if(is_array($url)) {

            if(
                ($currentParams['plugin'] === @$url['plugin'])
                AND
                ($currentParams['controller'] === @$url['controller'])
                AND
                ($currentParams['action'] === @$url['action'])
            ) {
                $active =  true;
            }
            $url = Router::url($url);
        }

        if($active) {
            $itemResult = str_replace('{{url}}', $url, $template['menuItemActive']);
        } else {
            $itemResult = str_replace('{{url}}', $url, $template['menuItem']);
        }

        $itemResult = str_replace('{{text}}', $item['text'], $itemResult);
        if(isset($item['child'])) {
            $itemResult .= $this->_parseChildMenu($item['child']);
        }
        return $itemResult;
    }

    private function _parseChildMenu($childItems){
        $template = $this->_defaultConfig['template'];
        $items = '';
        foreach($childItems as $item) {
            $items .= $this->_parseItem($item);
        }
        $item = str_replace('{{items}}', $items, $template['childMenuContainer']);

        return $item;
    }
}
