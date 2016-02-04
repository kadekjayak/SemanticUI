<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title><?= @$page_title; ?></title>
    <?= $this->Html->css('SemanticUI.semantic.min'); ?>
    <?= $this->Html->css('SemanticUI.custom'); ?>
    <?= $this->Html->css('blueimp-jquery-file-upload/css/jquery.fileupload-ui'); ?>
    <?= $this->Html->css('blueimp-jquery-file-upload/css/jquery.fileupload'); ?>


    

    <?= $this->Html->script('SemanticUI.jquery.min'); ?>



    <?= $this->Html->script('blueimp-jquery-file-upload/js/vendor/jquery.iframe-transport'); ?>
    <?= $this->Html->script('blueimp-jquery-file-upload/js/vendor/jquery.ui.widget'); ?>
    <?= $this->Html->script('blueimp-jquery-file-upload/js/jquery.fileupload'); ?>
    <?= $this->Html->script('blueimp-jquery-file-upload/js/jquery.fileupload-ui'); ?>
    <?= $this->Html->script('blueimp-jquery-file-upload/js/jquery.fileupload-process'); ?>
    <?= $this->Html->script('blueimp-jquery-file-upload/js/jquery.fileupload-image'); ?>
    <?= $this->Html->script('blueimp-jquery-file-upload/js/jquery.fileupload-audio'); ?>
    <?= $this->Html->script('blueimp-jquery-file-upload/js/jquery.fileupload-video'); ?>
    <?= $this->Html->script('blueimp-jquery-file-upload/js/jquery.fileupload-validate'); ?>



    
    <?= $this->Html->script('chunked'); ?>
</head>
<body>
	<div class="page">
		<?= $this->fetch('content'); ?>
	</div>
	
	<?= $this->Html->script('SemanticUI.semantic.min'); ?>
</body>
</html>