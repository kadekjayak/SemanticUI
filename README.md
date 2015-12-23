# SemanticUI plugin for CakePHP

## Installation

Download and extract it into plugin directory on cakephp.
and load plugin on bootstrap

	Plugin::load('SemanticUI', ['bootstrap' => false, 'routes' => true]);

##Requirements
* CakePHP 3.1+


## Example
### Using Theme for Bake
you can bake with Semantic UI theme by using --theme options :
	./bin/cake bake template all --theme SemanticUI

and use the layout that included from plugin, for example change on your src/Controller/AppController.php. (actualy i din't know if it's the correct way)

	public function beforeRender(Event $event)
    {
        $this->viewBuilder()->layout('SemanticUI.semantic');
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }


to get the Input form Styled you should use helper on this plugin, you can point it using "className" options on src/View/AppView.php

	public function initialize()
    {
        $this->loadHelper('Form', ['className' => 'SemanticUI.SemanticForm']);
    }