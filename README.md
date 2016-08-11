# SemanticUI plugin for CakePHP
This is Semantic UI plugin for CakePhp, it contain Theme for Bake, modified component and Helper.

## Installation
Download and extract it into plugin directory on cakephp.
and load plugin on bootstrap

	Plugin::load('SemanticUI', ['bootstrap' => false, 'routes' => true]);

##Requirements
* CakePHP 3+


## Example
### Using Theme for Bake
you can bake with Semantic UI theme by using `--theme` options, you can use the command below on terminal

	/bin/cake bake template all --theme SemanticUI


and use the layout that included from plugin, for example change on your `src/Controller/AppController.php`. (actualy i didn't know if it's the correct way)

	public function beforeRender(Event $event)
    {
        $this->viewBuilder()->layout('SemanticUI.semantic');
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }


to get the Input form Styled you should use Form Helper on this plugin, you can use Form helper on this plugin by passing `className` options when loading `FormHelper` on your `src/View/AppView.php`

	public function initialize()
    {
        $this->loadHelper('Form', ['className' => 'SemanticUI.SemanticForm']);
        $this->loadHelper('Paginator', ['className' => 'SemanticUI.SemanticPaginator']);
        $this->loadHelper('Html', ['className' => 'SemanticUI.SemanticHtml']);
    }


##Notes
For more info about Semantic UI visit their official website on : http://semantic-ui.com/