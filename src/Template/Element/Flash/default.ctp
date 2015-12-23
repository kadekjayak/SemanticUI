<?php
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
?>
<div class="ui message">
  	<i class="close icon"></i>
  	<div class="header">
    	Welcome back!
  	</div>
  	<p><?= h($message) ?></p>
</div>