<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo Yii::app()->name ?> 网站管理系统</title>
<style type="text/css">
* { word-break: break-all; word-wrap: break-word; }
body { background: #FFF; color: #000; text-align: center; line-height: 1.5em; }
body, h1, h2, h3, h4, h5, p, ul, dl, ol, form, fieldset { margin: 0; padding: 0; }
body, td, input, textarea, select, button { font-size: 12px; font-family: Verdana,Arial,Helvetica,sans-serif; }
ul { list-style: none; }
cite { font-style: normal; }
a { color: #46498E; text-decoration: none; }
a:hover { text-decoration: underline; }
a img { border: none; }

/*布局*/
#wrap { margin: 0 auto; padding: 0 2px; width: 1000px; text-align: left; }
#header { position: relative; height: 80px; border-bottom: 5px solid #B7C6F5; }
#header h2, #topmenu, #menu { position: absolute; }
#header h2 { left: 0; bottom: 10px; }
#topmenu { right: 1em; bottom: 3.5em; }
#menu { right: 1em; bottom: -5px; line-height: 28px; }
#menu li { float: left; padding: 2px 1em; }
#menu li.active { padding-top: 0; border: solid #B7C6F5; border-width: 2px 1px 0; background: #FFF; }
.mainarea { float: right; width: 100%; margin-left: -150px; }
.maininner { margin-left: 170px; }
.side { float: left; width: 150px; }

#content { margin: 1em 0;}
.title { margin-bottom: 10px; padding-bottom: 0.5em; border-bottom: 1px solid #B7C6F5;}
.title h1, .title h3 { padding: 0.6em 0 0.2em 0; font-size: 1.17em; }
.footactions { margin: 0 0 1em; padding: 0.5em; border: 2px solid #B7C6F5; border-top: 0px; }
/*\*/ * html .footactions { height: 1%; } /**/ * > .footactions { overflow: hidden; }
.footactions .pages { float: right; }
.footactions a { margin-right:12px;}

/*细线边框区域*/
.bdrcontent { padding: 1em; border: 2px solid #B7C6F5; zoom: 1; }

#footer { clear: both; padding: 1em 0; color: #939393; text-align: center; }
#footer p { font-size: 0.83em; }
#footer .menu a { padding: 0 1em; }
</style>
<script type="text/javascript">
if (self != top)
{
    top.location = self.location;
}
$(function(){
    $('#LoginForm_username').focus();
});
</script>
</head>
<body style="background: #FFF; color: #000; font: 75% Arial, Helvetica, sans-serif;">
<div style="position: absolute; left: 50%; top: 50%; width: 500px; height: 230px; margin-left: -250px; margin-top: -115px;">
<div style="border: 1px solid #CCC; background: #EEE; padding: 5px; text-align:left;">
 <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'login-form',
    'enableAjaxValidation'=>false,
    'htmlOptions'=> array('style'=>'background: #FFF url(/images/admin/logo.jpg) no-repeat 0 50%; margin: 0; padding: 20px 0 20px 180px;'),
)); ?>
<fieldset style="border: none; border-left: 1px solid #EEE; padding-left: 3em;">
<p style="margin: 0.5em 0;">用户名： <?php echo $form->textField($model,'username', array('style'=>'width: 10em; border: 1px solid #CCC; padding: 4px 2px;','tabindex'=>1)); ?><?php echo $form->error($model,'username', array('id'=>'username')); ?></p>
<p style="margin: 0.5em 0;">密　码： <?php echo $form->passwordField($model,'password', array('style'=>'width: 10em; border: 1px solid #CCC; padding: 4px 2px;','tabindex'=>2)); ?> <?php echo $form->error($model,'password'); ?></p><p style="margin: 0.5em 0;margin-left:52px;"><?php echo CHtml::submitButton('登录管理平台',array('class'=>'button','style'=>'background: #DDD; border-top: 1px solid #EEE; border-right: 1px solid #BBB; border-bottom: 1px solid #BBB; border-left: 1px solid #EEE; padding: 3px; cursor: pointer;')); ?></p>
</fieldset>
<?php $this->endWidget(); ?>
</div>
<p style="margin: 0.5em 0; text-align: center; font-size: 10px;">
Copyright &copy; <a href="http://<?php echo Yii::app()->params['domain'] ?>" target="_blank"><?php echo Yii::app()->params['domain'] ?></a> Powered by <a href="#" target="_blank" style="color: #006"><b><?php echo Yii::app()->name; ?>.</b></a>

</p>
</div>
</body>
</html>