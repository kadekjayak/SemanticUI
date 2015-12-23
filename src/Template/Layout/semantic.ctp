<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title><?= @$page_title; ?></title>
    <?= $this->Html->css('SemanticUI.semantic.min'); ?>
    <?= $this->Html->css('SemanticUI.custom'); ?>
</head>
<body>
	<div class="page">
		<?= $this->fetch('content'); ?>
	</div>
	<?= $this->Html->script('jquery.min'); ?>
	<?= $this->Html->script('semantic.min'); ?>
</body>
</html>