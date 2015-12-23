<?php
namespace SemanticUI\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\Component\FlashComponent;
use Cake\Controller\ComponentRegistry;
use Cake\Network\Exception\InternalErrorException;
use Cake\Utility\Inflector;
use Exception;

/**
 * SemanticFlash component
 */
class SemanticFlashComponent extends FlashComponent
{

    /**
     * Default configuration.
     *
     * @var array
     */
    


    public function __call($name, $args)
    {
        $element = Inflector::underscore($name);

        if (count($args) < 1) {
            throw new InternalErrorException('Flash message missing.');
        }

        $options = ['element' => 'SemanticUI.' . $element];

        if (!empty($args[1])) {
            if (!empty($args[1]['plugin'])) {
                $options = ['element' => 'SemanticUI.Flash/' . $element];
                unset($args[1]['plugin']);
            }
            $options += (array)$args[1];
        }
        $this->set($args[0], $options);
    }
}
