<?php
/* Smarty version 3.1.36, created on 2020-05-04 09:25:45
  from 'D:\phpStudy\PHPTutorial\WWW\yaf_test\application\views\index\index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.36',
  'unifunc' => 'content_5eafdf992490b0_29227487',
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
function content_5eafdf992490b0_29227487 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list']->value, 'item', false, 'key');
$_smarty_tpl->tpl_vars['item']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->do_else = false;
?>
		<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
 == 	<?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>

		<br>
	<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

</body>
</html>
<?php }
}
