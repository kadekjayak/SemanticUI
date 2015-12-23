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
        return !in_array($schema->columnType($field), ['binary', 'text']);
    })
    ->take(7);

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
                <?= $this->Html->link(__('<i class="plus icon"></i> New <%= $singularHumanName %>'), ['action' => 'add'], ['escape' => false, 'class' => 'item']) ?>
                <%
                    $done = [];
                    foreach ($associations as $type => $data):
                        foreach ($data as $alias => $details):
                            if (!empty($details['navLink']) && $details['controller'] !== $this->name && !in_array($details['controller'], $done)):
                %>
                        <?= $this->Html->link(__('<i class="list icon"></i> List <%= $this->_pluralHumanName($alias) %>'), ['controller' => '<%= $details['controller'] %>', 'action' => 'index'], ['escape' => false, 'class' => 'item']) ?>
                        <?= $this->Html->link(__('<i class="plus icon"></i> New <%= $this->_singularHumanName($alias) %>'), ['controller' => '<%= $details['controller'] %>', 'action' => 'add'], ['escape' => false, 'class' => 'item']) ?>
                <%
                                $done[] = $details['controller'];
                            endif;
                        endforeach;
                    endforeach;
                %>
            </div>
        </div>
        <div class="twelve wide column <%= $pluralVar %> content">
            <?= $this->Flash->render() ?>
            <table class="ui celled table"cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
        <% foreach ($fields as $field): %>
                        <th><?= $this->Paginator->sort('<%= $field %>') ?></th>
        <% endforeach; %>
                        <th class="actions"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($<%= $pluralVar %> as $<%= $singularVar %>): ?>
                    <tr>
        <%        foreach ($fields as $field) {
                    $isKey = false;
                    if (!empty($associations['BelongsTo'])) {
                        foreach ($associations['BelongsTo'] as $alias => $details) {
                            if ($field === $details['foreignKey']) {
                                $isKey = true;
        %>
                        <td><?= $<%= $singularVar %>->has('<%= $details['property'] %>') ? $this->Html->link($<%= $singularVar %>-><%= $details['property'] %>-><%= $details['displayField'] %>, ['controller' => '<%= $details['controller'] %>', 'action' => 'view', $<%= $singularVar %>-><%= $details['property'] %>-><%= $details['primaryKey'][0] %>]) : '' ?></td>
        <%
                                break;
                            }
                        }
                    }
                    if ($isKey !== true) {
                        if (!in_array($schema->columnType($field), ['integer', 'biginteger', 'decimal', 'float'])) {
        %>
                        <td><?= h($<%= $singularVar %>-><%= $field %>) ?></td>
        <%
                        } else {
        %>
                        <td><?= $this->Number->format($<%= $singularVar %>-><%= $field %>) ?></td>
        <%
                        }
                    }
                }

                $pk = '$' . $singularVar . '->' . $primaryKey[0];
        %>
                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['action' => 'view', <%= $pk %>]) ?>
                            <?= $this->Html->link(__('Edit'), ['action' => 'edit', <%= $pk %>]) ?>
                            <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', <%= $pk %>], ['confirm' => __('Are you sure you want to delete # {0}?', <%= $pk %>)]) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="paginator">
                <ul class="pagination">
                    <?= $this->Paginator->prev('< ' . __('previous')) ?>
                    <?= $this->Paginator->numbers() ?>
                    <?= $this->Paginator->next(__('next') . ' >') ?>
                </ul>
                <p><?= $this->Paginator->counter() ?></p>
            </div>
        </div>

    </div>
</div>