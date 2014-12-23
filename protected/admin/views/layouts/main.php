<?php 
Yii::app()->clientScript->registerCoreScript('jquery');
$admincpnav = implode('&nbsp;&raquo;&nbsp;', $this->breadcrumbs);
$jsContent = <<<EOF
if (parent.$('#admincpnav')[0]) parent.$('#admincpnav').html('首页&nbsp;&raquo;&nbsp;{$admincpnav}');

$(document).ready(function(){   
    $('#operateForm').bind('submit', function(e) {
        e.preventDefault();

        var opval = $("input[name='operation']:checked").val();
        if(opval == undefined){
            alert('请选择要执行的操作！');
            return;
        } else {
            var ids = $.fn.yiiGridView.getChecked($('.grid-view').attr('id'),'id[]');
            if(ids.length == 0){
                alert('请点选要操作的项目！');
                return;
            };
            if(confirm('确定要执行选定的操作吗？')){
                $(this).ajaxSubmit({
                    data: {'id[]': ids},
                    success: function(msg){
                           $.fn.yiiGridView.update($('.grid-view').attr('id'));
                           alert(msg);
                       },
                       error: function(obj){
                            alert(obj.responseText);
                       }
                });
            }
        }                                     
    });           

    //detailview的图片自动缩放
    $('table[id^=yw] img').each(function(){
        if($(this).width() > 500) {
            $(this).width(500);
            $(this).height('auto');
        }
        if($(this).height() > 500) {
            $(this).height(500);
            $(this).width('auto');
        }
    });

    $('textarea.editor').each(function(){
        CKEDITOR.replace($(this).attr('id'), {
            'filebrowserUploadUrl' : '<?php echo Yii::app()->baseUrl ?>/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
            'filebrowserImageUploadUrl' : '<?php echo Yii::app()->baseUrl ?>/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
            'filebrowserFlashUploadUrl' : '<?php echo Yii::app()->baseUrl ?>/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
        });
    });
});

EOF
?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="<?php echo Yii::app()->params['author']; ?>" />
    <link href="<?php echo Yii::app()->baseUrl ?>/images/admin/admincp.css" rel="stylesheet" type="text/css" />
    <title><?php echo $this->pageTitle; ?> - <?php echo Yii::app()->name; ?></title>
    <script charset="utf-8" src="<?php echo Yii::app()->baseUrl ?>/images/admin/admincp.js"></script>
    <script charset="utf-8" src="<?php echo Yii::app()->baseUrl ?>/js/jquery.form.js" type="text/javascript"></script>
    <script charset="utf-8" src="<?php echo Yii::app()->baseUrl ?>/js/ckeditor/ckeditor.js" type="text/javascript"></script>
</head>
<body>
<div id="append_parent"></div>

<div class="container" id="cpcontainer">
<script type="text/JavaScript" charset="utf-8"></script>
<div class="floattop">
  <div class="itemtitle" id="tabbar-div">
    <h3><?php echo $this->breadcrumbs[0]; ?></h3>
    <?php 
    $this->widget('zii.widgets.CMenu', array(
        'items'=>$this->menu,
        'htmlOptions'=>array('class'=>'tab1'),
        'linkLabelWrapper'=>'span',
        'activeCssClass' => 'current',
    ));
    ?>
  </div>
</div>
<div class="floattopempty"></div>

<?php  ?>


<?php echo $content; ?>

</div>
<?php echo CHtml::script($jsContent); ?>
<?php if(Yii::app()->user->hasFlash('error')):?>
    <?php $this->widget('ext.toastMessage.toastMessageWidget',
         array(
            'message'=>Yii::app()->user->getFlash('error'),
            'type'=>'Error',
            'options'=>array('sticky'=>false, 'stayTime'=>10000),
            )
         );
     ?>
     <?php endif; ?>
     <?php if(Yii::app()->user->hasFlash('success')):?>
     <?php $this->widget('ext.toastMessage.toastMessageWidget',
         array(
            'message'=>Yii::app()->user->getFlash('success'),
            'type'=>'Success',
            'options'=>array('sticky'=>false),
            )
         );
     ?>
    <?php endif; ?>
</body>
</html>
