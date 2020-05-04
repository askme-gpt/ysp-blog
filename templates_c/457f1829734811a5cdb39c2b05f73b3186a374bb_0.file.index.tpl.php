<?php
/* Smarty version 3.1.36, created on 2020-05-04 09:45:38
  from 'D:\phpStudy\PHPTutorial\WWW\yaf_test\application\views\index\index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.36',
  'unifunc' => 'content_5eafe442a62a27_88229303',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '457f1829734811a5cdb39c2b05f73b3186a374bb' => 
    array (
      0 => 'D:\\phpStudy\\PHPTutorial\\WWW\\yaf_test\\application\\views\\index\\index.tpl',
      1 => 1588584343,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5eafe442a62a27_88229303 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	{{ foreach from=$list item=item key=key }}
		{{ $item['id'] }} == 	{{ $item['name'] }}
		<br>
	{{ /foreach }}

</body>
</html>
<?php }
}
