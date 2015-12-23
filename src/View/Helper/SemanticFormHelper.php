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
class SemanticFormHelper extends FormHelper
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'idPrefix' => null,
        'errorClass' => 'form-error',
        'typeMap' => [
            'string' => 'text', 'datetime' => 'datetime', 'boolean' => 'checkbox',
            'timestamp' => 'datetime', 'text' => 'textarea', 'time' => 'time',
            'date' => 'date', 'float' => 'number', 'integer' => 'number',
            'decimal' => 'number', 'binary' => 'file', 'uuid' => 'string'
        ],
        'templates' => [
            'button' => '<button{{attrs}}>{{text}}</button>',
            'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}}>',
            'checkboxFormGroup' => '{{label}}',
            'checkboxWrapper' => '<div class="checkbox">{{label}}</div>',
            'dateWidget' => '{{year}}{{month}}{{day}}{{hour}}{{minute}}{{second}}{{meridian}}',
            'error' => '<div class="ui error message">{{content}}</div>',
            'errorList' => '<ul>{{content}}</ul>',
            'errorItem' => '<li>{{text}}</li>',
            'file' => '<input type="file" name="{{name}}"{{attrs}}>',
            'fieldset' => '<fieldset{{attrs}}>{{content}}</fieldset>',
            'formStart' => '<form{{attrs}}>',
            'formEnd' => '</form>',
            'formGroup' => '{{label}}{{input}}',
            'hiddenBlock' => '<div style="display:none;">{{content}}</div>',
            'input' => '<input type="{{type}}" name="{{name}}"{{attrs}}/>',
            'inputSubmit' => '<input type="{{type}}"{{attrs}}/>',
            'inputContainer' => '<div class="input field {{type}}{{required}}">{{content}}</div>',
            'inputContainerError' => '<div class="input {{type}}{{required}} error">{{content}}{{error}}</div>',
            'label' => '<label{{attrs}}>{{text}}</label>',
            'nestingLabel' => '{{hidden}}<label{{attrs}}>{{input}}{{text}}</label>',
            'legend' => '<legend>{{text}}</legend>',
            'option' => '<option value="{{value}}"{{attrs}}>{{text}}</option>',
            'optgroup' => '<optgroup label="{{label}}"{{attrs}}>{{content}}</optgroup>',
            'select' => '<select name="{{name}}"{{attrs}}>{{content}}</select>',
            'selectMultiple' => '<select name="{{name}}[]" multiple="multiple"{{attrs}}>{{content}}</select>',
            'radio' => '<input type="radio" name="{{name}}" value="{{value}}"{{attrs}}>',
            'radioWrapper' => '{{label}}',
            'textarea' => '<textarea name="{{name}}"{{attrs}}>{{value}}</textarea>',
            'submitContainer' => '<div class="submit">{{content}}</div>',
        ]
    ];



    /**
    * @ Override
    *
    **/
    public function create($model = null, array $options = [])
    {
        $append = '';

        if (empty($options['context'])) {
            $options['context'] = [];
        }
        $options['context']['entity'] = $model;
        $context = $this->_getContext($options['context']);
        unset($options['context']);

        $isCreate = $context->isCreate();

        $options += [
            'type' => $isCreate ? 'post' : 'put',
            'action' => null,
            'url' => null,
            'encoding' => strtolower(Configure::read('App.encoding')),
            'templates' => null,
            'idPrefix' => null,
            'class' => "ui form"
        ];

        if ($options['idPrefix'] !== null) {
            $this->_idPrefix = $options['idPrefix'];
        }
        $templater = $this->templater();

        if (!empty($options['templates'])) {
            $templater->push();
            $method = is_string($options['templates']) ? 'load' : 'add';
            $templater->{$method}($options['templates']);
        }
        unset($options['templates']);

        if ($options['action'] === false || $options['url'] === false) {
            $url = $this->request->here(false);
            $action = null;
        } else {
            $url = $this->_formUrl($context, $options);
            $action = $this->Url->build($url);
        }

        $this->_lastAction($url);
        unset($options['url'], $options['action'], $options['idPrefix']);

        $htmlAttributes = [];
        switch (strtolower($options['type'])) {
            case 'get':
                $htmlAttributes['method'] = 'get';
                break;
            // Set enctype for form
            case 'file':
                $htmlAttributes['enctype'] = 'multipart/form-data';
                $options['type'] = ($isCreate) ? 'post' : 'put';
            // Move on
            case 'post':
            // Move on
            case 'put':
            // Move on
            case 'delete':
            // Set patch method
            case 'patch':
                $append .= $this->hidden('_method', [
                    'name' => '_method',
                    'value' => strtoupper($options['type']),
                    'secure' => static::SECURE_SKIP
                ]);
            // Default to post method
            default:
                $htmlAttributes['method'] = 'post';
        }
        $this->requestType = strtolower($options['type']);

        if (!empty($options['encoding'])) {
            $htmlAttributes['accept-charset'] = $options['encoding'];
        }
        unset($options['type'], $options['encoding']);

        $htmlAttributes += $options;

        $this->fields = [];
        if ($this->requestType !== 'get') {
            $append .= $this->_csrfField();
        }

        if (!empty($append)) {
            $append = $templater->format('hiddenBlock', ['content' => $append]);
        }

        $actionAttr = $templater->formatAttributes(['action' => $action, 'escape' => false]);
        return $templater->format('formStart', [
            'attrs' => $templater->formatAttributes($htmlAttributes) . $actionAttr
        ]) . $append;
    }

     public function button($title, array $options = [])
    {
        $options += ['type' => 'submit', 'escape' => false, 'secure' => false];
        $options['text'] = $title;
        $options['class'] = 'ui button';
        return $this->widget('button', $options);
    }

    public function input($fieldName, array $options = [])
    {
        $options += [
            'type' => null,
            'label' => null,
            'error' => null,
            'required' => null,
            'options' => null,
            'templates' => [],
            'templateVars' => [],
            'class' => 'field'
        ];
        $options = $this->_parseOptions($fieldName, $options);
        $options += ['id' => $this->_domId($fieldName)];

        $templater = $this->templater();
        $newTemplates = $options['templates'];

        if ($newTemplates) {
            $templater->push();
            $templateMethod = is_string($options['templates']) ? 'load' : 'add';
            $templater->{$templateMethod}($options['templates']);
        }
        unset($options['templates']);

        $error = null;
        $errorSuffix = '';
        if ($options['type'] !== 'hidden' && $options['error'] !== false) {
            $error = $this->error($fieldName, $options['error']);
            $errorSuffix = empty($error) ? '' : 'Error';
            unset($options['error']);
        }

        $label = $options['label'];
        unset($options['label']);

        $nestedInput = false;
        if ($options['type'] === 'checkbox') {
            $nestedInput = true;
        }
        $nestedInput = isset($options['nestedInput']) ? $options['nestedInput'] : $nestedInput;

        if ($nestedInput === true && $options['type'] === 'checkbox' && !array_key_exists('hiddenField', $options) && $label !== false) {
            $options['hiddenField'] = '_split';
        }

        $input = $this->_getInput($fieldName, $options);
        if ($options['type'] === 'hidden' || $options['type'] === 'submit') {
            if ($newTemplates) {
                $templater->pop();
            }
            return $input;
        }

        $label = $this->_getLabel($fieldName, compact('input', 'label', 'error', 'nestedInput') + $options);
        $result = $this->_groupTemplate(compact('input', 'label', 'error', 'options'));
        $result = $this->_inputContainerTemplate([
            'content' => $result,
            'error' => $error,
            'errorSuffix' => $errorSuffix,
            'options' => $options
        ]);

        if ($newTemplates) {
            $templater->pop();
        }

        return $result;
    }

    public function checkbox($fieldName, array $options = [])
    {
        $options += ['hiddenField' => true, 'value' => 1];

        // Work around value=>val translations.
        $value = $options['value'];
        unset($options['value']);
        $options = $this->_initInputField($fieldName, $options);
        $options['value'] = $value;

        $output = '';
        if ($options['hiddenField']) {
            $hiddenOptions = [
                'name' => $options['name'],
                'value' => ($options['hiddenField'] !== true && $options['hiddenField'] !== '_split' ? $options['hiddenField'] : '0'),
                'form' => isset($options['form']) ? $options['form'] : null,
                'secure' => false
            ];
            if (isset($options['disabled']) && $options['disabled']) {
                $hiddenOptions['disabled'] = 'disabled';
            }
            $output = $this->hidden($fieldName, $hiddenOptions);
        }

        if ($options['hiddenField'] === '_split') {
            unset($options['hiddenField'], $options['type']);
            return ['hidden' => $output, 'input' => $this->widget('checkbox', $options)];
        }
        unset($options['hiddenField'], $options['type']);
        return $output . $this->widget('checkbox', $options);
    }

    public function select($fieldName, $options = [], array $attributes = [])
    {

        $attributes += [
            'disabled' => null,
            'escape' => true,
            'hiddenField' => true,
            'multiple' => null,
            'secure' => true,
            'empty' => false,
        ];

        if ($attributes['multiple'] === 'checkbox') {
            unset($attributes['multiple'], $attributes['empty']);
            return $this->multiCheckbox($fieldName, $options, $attributes);
        }

        // Secure the field if there are options, or it's a multi select.
        // Single selects with no options don't submit, but multiselects do.
        if ($attributes['secure'] &&
            empty($options) &&
            empty($attributes['empty']) &&
            empty($attributes['multiple'])
        ) {
            $attributes['secure'] = false;
        }

        $attributes = $this->_initInputField($fieldName, $attributes);
        $attributes['options'] = $options;
        $attributes['class'] = 'ui dropdown';
        $hidden = '';
        if ($attributes['multiple'] && $attributes['hiddenField']) {
            $hiddenAttributes = [
                'name' => $attributes['name'],
                'value' => '',
                'form' => isset($attributes['form']) ? $attributes['form'] : null,
                'secure' => false,
            ];
            $hidden = $this->hidden($fieldName, $hiddenAttributes);
        }
        unset($attributes['hiddenField'], $attributes['type']);
        return $hidden . $this->widget('select', $attributes);
    }

}
