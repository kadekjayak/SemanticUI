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

$associations += ['BelongsTo' => [], 'HasOne' => [], 'HasMany' => [], 'BelongsToMany' => []];
$immediateAssociations = $associations['BelongsTo'] + $associations['HasOne'];
$associationFields = collection($fields)
    ->map(function($field) use ($immediateAssociations) {
        foreach ($immediateAssociations as $alias => $details) {
            if ($field === $details['foreignKey']) {
                return [$field => $details];
            }
        }
    })
    ->filter()
    ->reduce(function($fields, $value) {
        return $fields + $value;
    }, []);

$groupedFields = collection($fields)
    ->filter(function($field) use ($schema) {
        return $schema->columnType($field) !== 'binary';
    })
    ->groupBy(function($field) use ($schema, $associationFields) {
        $type = $schema->columnType($field);
        if (isset($associationFields[$field])) {
            return 'string';
        }
        if (in_array($type, ['integer', 'float', 'decimal', 'biginteger'])) {
            return 'number';
        }
        if (in_array($type, ['date', 'time', 'datetime', 'timestamp'])) {
            return 'date';
        }
        return in_array($type, ['text', 'boolean']) ? $type : 'string';
    })
    ->toArray();

$groupedFields += ['number' => [], 'string' => [], 'boolean' => [], 'date' => [], 'text' => []];
$pk = "\$$singularVar->{$primaryKey[0]}";
%>
<div class="ui grid">
    <div class="four wide column">
        <div class="ui vertical fluid visible menu">
            <a class="active item"><%= $singularHumanName %></a>
            <?= $this->Html->link(__('New <%= $singularHumanName %>'), ['action' => 'add'], ['class' => 'item']) ?>
            <%
                $done = [];
                foreach ($associations as $type => $data):
                    foreach ($data as $alias => $details):
                        if (!empty($details['navLink']) && $details['controller'] !== $this->name && !in_array($details['controller'], $done)):
            %>
                    <?= $this->Html->link(__('List <%= $this->_pluralHumanName($alias) %>'), ['controller' => '<%= $details['controller'] %>', 'action' => 'index'], ['class' => 'item']) ?>
                    <?= $this->Html->link(__('New <%= $this->_singularHumanName($alias) %>'), ['controller' => '<%= $details['controller'] %>', 'action' => 'add'], ['class' => 'item']) ?>
            <%
                            $done[] = $details['controller'];
                        endif;
                    endforeach;
                endforeach;
            %>
        </div>
    </div>
    <div class="twelve wide column <%= $pluralVar %>">
        <h1 class="ui header"><?php echo $title_for_layout; ?></h1>
        <div class="ui stacked segment">
            <table class="ui definition table">
                <% if ($groupedFields['string']) : %>
                <% foreach ($groupedFields['string'] as $field) : %>
                <% if (isset($associationFields[$field])) :
                            $details = $associationFields[$field];
                %>
                        <tr>
                            <td><?= __('<%= Inflector::humanize($details['property']) %>') ?></td>
                            <td><?= $<%= $singularVar %>->has('<%= $details['property'] %>') ? $this->Html->link($<%= $singularVar %>-><%= $details['property'] %>-><%= $details['displayField'] %>, ['controller' => '<%= $details['controller'] %>', 'action' => 'view', $<%= $singularVar %>-><%= $details['property'] %>-><%= $details['primaryKey'][0] %>]) : '' ?></td>
                        </tr>
                <% else : %>
                        <tr>
                            <td><?= __('<%= Inflector::humanize($field) %>') ?></td>
                            <td><?= h($<%= $singularVar %>-><%= $field %>) ?></td>
                        </tr>
                <% endif; %>
                <% endforeach; %>
                <% endif; %>
                <% if ($groupedFields['number']) : %>
                <% foreach ($groupedFields['number'] as $field) : %>
                        <tr>
                            <td><?= __('<%= Inflector::humanize($field) %>') ?></td>
                            <td><?= $this->Number->format($<%= $singularVar %>-><%= $field %>) ?></td>
                        </tr>
                <% endforeach; %>
                <% endif; %>
                <% if ($groupedFields['date']) : %>
                <% foreach ($groupedFields['date'] as $field) : %>
                        <tr>
                            <td><%= "<%= __('" . Inflector::humanize($field) . "') %>" %></td>
                            <td><?= h($<%= $singularVar %>-><%= $field %>) ?></td>
                        </tr>
                <% endforeach; %>
                <% endif; %>
                <% if ($groupedFields['boolean']) : %>
                <% foreach ($groupedFields['boolean'] as $field) : %>
                        <tr>
                            <td><?= __('<%= Inflector::humanize($field) %>') ?></td>
                            <td><?= $<%= $singularVar %>-><%= $field %> ? __('Yes') : __('No'); ?></td>
                         </tr>
                <% endforeach; %>
                <% endif; %>
                    </table>
                <% if ($groupedFields['text']) : %>
                <% foreach ($groupedFields['text'] as $field) : %>
                    <div class="row">
                        <h4><?= __('<%= Inflector::humanize($field) %>') ?></h4>
                        <?= $this->Text->autoParagraph(h($<%= $singularVar %>-><%= $field %>)); ?>
                    </div>
                <% endforeach; %>
                <% endif; %>
                <%
                $relations = $associations['HasMany'] + $associations['BelongsToMany'];
                foreach ($relations as $alias => $details):
                    $otherSingularVar = Inflector::variable($alias);
                    $otherPluralHumanName = Inflector::humanize(Inflector::underscore($details['controller']));
                    %>
                    <div class="related">
                        <h4 class="ui dividing header"><?= __('Related <%= $otherPluralHumanName %>') ?></h4>
                        <?php if (!empty($<%= $singularVar %>-><%= $details['property'] %>)): ?>
                        <table class="ui celled table" cellpadding="0" cellspacing="0">
                            <thead>
                                <tr>
                    <% foreach ($details['fields'] as $field): %>
                                    <th><?= __('<%= Inflector::humanize($field) %>') ?></th>
                    <% endforeach; %>
                                    <th class="actions"><?= __('Actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($<%= $singularVar %>-><%= $details['property'] %> as $<%= $otherSingularVar %>): ?>
                                <tr>
                                <%- foreach ($details['fields'] as $field): %>
                                    <td><?= h($<%= $otherSingularVar %>-><%= $field %>) ?></td>
                                <%- endforeach; %>
                                <%- $otherPk = "\${$otherSingularVar}->{$details['primaryKey'][0]}"; %>
                                    <td class="actions">
                                        <?= $this->Html->link(__('View'), ['controller' => '<%= $details['controller'] %>', 'action' => 'view', <%= $otherPk %>]) %>
                                        <?= $this->Html->link(__('Edit'), ['controller' => '<%= $details['controller'] %>', 'action' => 'edit', <%= $otherPk %>]) %>
                                        <?= $this->Form->postLink(__('Delete'), ['controller' => '<%= $details['controller'] %>', 'action' => 'delete', <%= $otherPk %>], ['confirm' => __('Are you sure you want to delete # {0}?', <%= $otherPk %>)]) %>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                    </div>
                <% endforeach; %>
        </div>
    </div>
</div>
