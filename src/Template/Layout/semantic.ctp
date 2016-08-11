<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title><?= @$page_title; ?></title>
    <?= $this->Html->css('SemanticUI.semantic.min'); ?>
    <?= $this->Html->css('SemanticUI.custom'); ?>

    <?= $this->Html->script('SemanticUI.jquery.min'); ?>

    <?= $this->Html->script('chunked'); ?>
</head>
<body>
    <header id="header">
        <div class="ui inverted menu">
            <div class="ui container">
                <div class="item">Semantic Ui Theme</div>
            </div>
        </div>
    </header>
	<div class="page">
        <div class="ui main container">
		  <?= $this->fetch('content'); ?>
        </div>
	</div>
	
	<?= $this->Html->script('SemanticUI.semantic.min'); ?>
    <?= $this->Html->script('SemanticUI.script'); ?>
</body>
</html>