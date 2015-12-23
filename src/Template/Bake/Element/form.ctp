<%
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.1.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Utility\Inflector;

$fields = collection($fields)
    ->filter(function($field) use ($schema) {
        return $schema->columnType($field) !== 'binary';
    });

if (isset($modelObject) && $modelObject->behaviors()->has('Tree')) {
    $fields = $fields->reject(function ($field) {
        return $field === 'lft' || $field === 'rght';
    });
}
%>
<div class="ui pageHeader vertical segment">
    <div class="ui container">
        <div class="introduction">
            <h1 class="ui header"><?= __('<%= $pluralHumanName %>') ?></h1>
        </div>
    </div>
</div>
<div class="ui main container">
    <div class="ui grid stackable">
        <div class="four wide column" id="actions-sidebar">
            <div class="ui vertical pointing menu fluid" id="actions-sidebar">
        <% if (strpos($action, 'add') === false): %>
                <?= $this->Form->postLink(
                        __('<i class="minus icon"></i> Delete'),
                        ['action' => 'delete', $<%= $singularVar %>-><%= $primaryKey[0] %>],
                        [
                            'confirm' => __('Are you sure you want to delete # {0}?', $<%= $singularVar %>-><%= $primaryKey[0] %>),
                            'escape' => false,
                            'class' => 'item'
                        ]
                    )
                ?>
        <% endif; %>
                <?= $this->Html->link(__('<i class="list icon"></i> List <%= $pluralHumanName %>'), ['action' => 'index'], ['escape' => false, 'class' => 'item']) ?>
                <%
                        $done = [];
                        foreach ($associations as $type => $data) {
                            foreach ($data as $alias => $details) {
                                if ($details['controller'] !== $this->name && !in_array($details['controller'], $done)) {
                %>
                        <?= $this->Html->link(__('<i class="list icon"></i> List <%= $this->_pluralHumanName($alias) %>'), ['controller' => '<%= $details['controller'] %>', 'action' => 'index'], ['class' => 'item', 'escape' => false]) %>
                        <?= $this->Html->link(__('<i class="plus icon"></i> New <%= $this->_singularHumanName($alias) %>'), ['controller' => '<%= $details['controller'] %>', 'action' => 'add'], ['class' => 'item', 'escape' => false]) %>
                <%
                                    $done[] = $details['controller'];
                                }
                            }
                        }
                %>
            </div>
        </div>
        <div class="twelve wide column <%= $pluralVar %> content">
            <?= $this->Flash->render() ?>
            <?= $this->Form->create($<%= $singularVar %>) ?>
                
                    <h4 class="ui dividing header"><?= __('<%= Inflector::humanize($action) %> <%= $singularHumanName %>') ?></h4>
                    <?php
            <%
                    foreach ($fields as $field) {
                        if (in_array($field, $primaryKey)) {
                            continue;
                        }
                        if (isset($keyFields[$field])) {
                            $fieldData = $schema->column($field);
                            if (!empty($fieldData['null'])) {
            %>
                        echo $this->Form->input('<%= $field %>', ['options' => $<%= $keyFields[$field] %>, 'empty' => true]);
            <%
                            } else {
            %>
                        echo $this->Form->input('<%= $field %>', ['options' => $<%= $keyFields[$field] %>]);
            <%
                            }
                            continue;
                        }
                        if (!in_array($field, ['created', 'modified', 'updated'])) {
                            $fieldData = $schema->column($field);
                            if (($fieldData['type'] === 'date') && (!empty($fieldData['null']))) {
            %>
                        echo $this->Form->input('<%= $field %>', ['empty' => true]);
            <%
                            } else {
            %>
                        echo $this->Form->input('<%= $field %>');
            <%
                            }
                        }
                    }
                    if (!empty($associations['BelongsToMany'])) {
                        foreach ($associations['BelongsToMany'] as $assocName => $assocData) {
            %>
                        echo $this->Form->input('<%= $assocData['property'] %>._ids', ['options' => $<%= $assocData['variable'] %>]);
            <%
                        }
                    }
            %>
                    ?>
                
                <?= $this->Form->button(__('Submit')) ?>
                <?= $this->Form->end() ?>
        </div>
    </div>
</div>