<?php
/* Smarty version 3.1.36, created on 2020-05-04 08:36:55
  from 'D:\phpStudy\PHPTutorial\WWW\yaf_test\application\views\error\error.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.36',
  'unifunc' => 'content_5eafd427ae9892_13908002',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9a6cee691cf39885a0a1001f24a891dafa533df0' => 
    array (
      0 => 'D:\\phpStudy\\PHPTutorial\\WWW\\yaf_test\\application\\views\\error\\error.tpl',
      1 => 1588581414,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5eafd427ae9892_13908002 (Smarty_Internal_Template $_smarty_tpl) {
?><html>
<head>
<title>500 Error</title>
<body>
<div>Yaf Caught exception : </div>
<p style="background-color: #CCFFFF;"><?php echo $_smarty_tpl->tpl_vars['exception']->value->getMessage();?>
 </p>
</body>
<html><?php }
}
