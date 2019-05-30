<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:54:"./application/admin/template/system/customvar_edit.htm";i:1558667891;s:71:"/home/o2design/public_html/application/admin/template/public/layout.htm";i:1558672259;s:71:"/home/o2design/public_html/application/admin/template/public/footer.htm";i:1558667897;}*/ ?>
<!doctype html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-capable" content="yes">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link href="/public/static/admin/css/main.css?v=<?php echo $version; ?>" rel="stylesheet" type="text/css">
<link href="/public/static/admin/css/page.css?v=<?php echo $version; ?>" rel="stylesheet" type="text/css">
<link href="/public/static/admin/font/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
  <link rel="stylesheet" href="/public/static/admin/font/css/font-awesome-ie7.min.css">
<![endif]-->
<script type="text/javascript">
    var eyou_basefile = "<?php echo \think\Request::instance()->baseFile(); ?>";
    var module_name = "<?php echo MODULE_NAME; ?>";
    var GetUploadify_url = "<?php echo url('Uploadify/upload'); ?>";
    var __root_dir__ = "";
    var __lang__ = "<?php echo $admin_lang; ?>";
</script>  
<link href="/public/static/admin/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
<link href="/public/static/admin/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css"/>
<style type="text/css">html, body { overflow: visible;}</style>
<script type="text/javascript" src="/public/static/admin/js/jquery.js"></script>
<script type="text/javascript" src="/public/static/admin/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="/public/plugins/layer-v3.1.0/layer.js"></script>
<script type="text/javascript" src="/public/static/admin/js/jquery.cookie.js"></script>
<script type="text/javascript" src="/public/static/admin/js/admin.js?v=<?php echo $version; ?>"></script>
<script type="text/javascript" src="/public/static/admin/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="/public/static/admin/js/common.js?v=<?php echo $version; ?>"></script>
<script type="text/javascript" src="/public/static/admin/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="/public/static/admin/js/jquery.mousewheel.js"></script>
<script src="/public/static/admin/js/myFormValidate.js"></script>
<script src="/public/static/admin/js/myAjax2.js?v=<?php echo $version; ?>"></script>
<script src="/public/static/admin/js/global.js?v=<?php echo $version; ?>"></script>

</head>

<body style="background-color: #FFF; overflow-x:hidden;">
<div id="toolTipLayer" style="position: absolute; z-index: 9999; display: none; visibility: visible; left: 95px; top: 573px;"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>自定义变量 - 编辑</h3>
                <h5></h5>
            </div>
        </div>
    </div>
    <form class="form-horizontal" id="post_form" action="<?php echo U('System/customvar_edit'); ?>" method="post">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="ac_name"><em>*</em>变量名称</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="attr_name" value="<?php echo (isset($field['attr_name']) && ($field['attr_name'] !== '')?$field['attr_name']:''); ?>" id="attr_name" class="input-txt">
                    <span class="err" id="err_attr_name" style="color:#F00; display:none;"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="attr_input_type"><em>*</em>表单类型</label>
                </dt>
                <dd class="opt">
                    <label><input type="radio" value="0" name="attr_input_type" <?php if(!isset($field['attr_input_type']) || $field['attr_input_type'] == 0): ?>checked="checked"<?php endif; ?> />单行文本</label>
                    <br/>
                    <label><input type="radio" value="2" name="attr_input_type" <?php if(isset($field['attr_input_type']) && $field['attr_input_type'] == 2): ?>checked="checked"<?php endif; ?> />多行文本</label>
                    <br/>
                    <label><input type="radio" value="3" name="attr_input_type" <?php if(isset($field['attr_input_type']) && $field['attr_input_type'] == 3): ?>checked="checked"<?php endif; ?> />上传图片</label>
                    <span class="err" id="err_attr_input_type" style="color:#F00; display:none;"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row attr_input_typeall <?php if(isset($field['attr_input_type']) and $field['attr_input_type'] == 3): ?>none<?php endif; ?>"">
                <dt class="tit">
                    <label for="attr_values">变量值</label>
                </dt>
                <dd class="opt">
                    <textarea rows="10" cols="30" name="attr_values" id="attr_values" class="input-txt" style="height:36px;"><?php echo (isset($field['value']) && ($field['value'] !== '')?$field['value']:''); ?></textarea>
                    <span id="err_attr_values" class="err" style="color:#F00; display:none;"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row attr_input_type3 <?php if(!isset($field['attr_input_type']) or $field['attr_input_type'] != 3): ?>none<?php endif; ?>">
                <dt class="tit">
                  <label>上传图片</label>
                </dt>
                <dd class="opt">
                    <div class="input-file-show div_value_local" <?php if(isset($field['is_remote']) AND $field['is_remote'] == 1): ?>style="display: none;"<?php endif; ?>>
                        <span class="show">
                            <a id="img_a" target="_blank" class="nyroModal" rel="gal" href="<?php echo (isset($field['value']) && ($field['value'] !== '')?$field['value']:'javascript:void(0);'); ?>" target="_blank">
                                <i id="img_i" class="fa fa-picture-o" <?php if(!(empty($field['value']) || (($field['value'] instanceof \think\Collection || $field['value'] instanceof \think\Paginator ) && $field['value']->isEmpty()))): ?>onmouseover="layer_tips=layer.tips('<img src=<?php echo (isset($field['value']) && ($field['value'] !== '')?$field['value']:''); ?> class=\'layer_tips_img\'>',this,{tips: [1, '#fff']});"<?php endif; ?> onmouseout="layer.close(layer_tips);"></i>
                            </a>
                        </span>
                        <span class="type-file-box">
                            <input type="text" id="value_local" name="value_local" value="<?php echo (isset($field['value']) && ($field['value'] !== '')?$field['value']:''); ?>" class="type-file-text">
                            <input type="button" name="button" id="button1" value="选择上传..." class="type-file-button">
                            <input class="type-file-file" onClick="GetUploadifyFrame(1,'','allimg','img_call_back','<?php echo U('Uploadify/upload_frame'); ?>')" size="30" hidefocus="true" nc_type="change_site_logo"
                                 title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
                        </span>
                    </div>
                    <input type="text" id="value_remote" name="value_remote" value="<?php echo (isset($field['value_remote']) && ($field['value_remote'] !== '')?$field['value_remote']:''); ?>" placeholder="http://" class="input-txt" <?php if(!isset($field['is_remote']) OR empty($field['is_remote'])): ?>style="display: none;"<?php endif; ?>>
                    &nbsp;
                    <label><input type="checkbox" name="is_remote" id="is_remote" value="1" <?php if(isset($field['is_remote']) AND $field['is_remote'] == 1): ?>checked="checked"<?php endif; ?> onClick="clickRemote(this, 'value');">远程图片</label>
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
        </div>
        <!-- 常规信息 -->
        <div class="ncap-form-default">
            <div class="bot" style="padding-bottom: 0px;">
                <input type="hidden" name="inc_type" value="<?php echo (isset($inc_type) && ($inc_type !== '')?$inc_type:''); ?>" />
                <input type="hidden" name="id" value="<?php echo (isset($field['id']) && ($field['id'] !== '')?$field['id']:''); ?>" />
                <input type="hidden" name="attr_id" value="<?php echo (isset($field['attr_id']) && ($field['attr_id'] !== '')?$field['attr_id']:''); ?>" />
                <input type="hidden" name="name" value="<?php echo (isset($field['name']) && ($field['name'] !== '')?$field['name']:''); ?>" />
                <a href="JavaScript:void(0);" onclick="check_submit();" class="ncap-btn-big ncap-btn-green" id="submitBtn">确认提交</a>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">

    var parentObj = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引

    $(function(){
        $('input[name=attr_input_type]').click(function(){
            var val = $(this).val();
            if (val == 3) {
                $('#litpic').val('');
                $('.attr_input_typeall').hide();
                $('.attr_input_type3').show();
            } else {
                $('.attr_input_type3').hide();
                $('.attr_input_typeall').show();
            }
        });
    });

    // 判断输入框是否为空
    function check_submit(){
        if($.trim($('input[name=attr_name]').val()) == ''){
            showErrorMsg('变量名称不能为空！');
            $('input[name=attr_name]').focus();
            return false;
        }
        if ($('input[name=attr_input_type]').is(':checked') == false) {
            showErrorMsg('请选择表单类型！');
            $($('input[name=attr_input_type]')[0]).focus();
            return false;
        }
        if ($('input[name=attr_input_type]:checked').val() == 1 && $.trim($('#attr_values').val()) == '') {
            showErrorMsg('可选值列表不能为空！');
            $('#attr_values').focus();
            return false;
        }

        layer_loading('正在处理');

        $.ajax({
            url: "<?php echo U('System/customvar_edit'); ?>",
            type: 'POST',
            dataType: 'JSON',
            data: $('#post_form').serialize(),
            success: function(res){
                layer.closeAll();
                if (res.code == 1) {
                    parent.layer.msg(res.msg, {shade: 0.3, time: 500});
                    parent.window.location.reload();
                    parent.layer.close(parentObj);
                } else {
                    parent.showErrorMsg(res.msg);
                }
                return false;
            },
            error: function(e){
                layer.closeAll();
                layer.msg('操作失败', {icon:5, time:1500});
                return false;
            }
        });

        return false;
    }

    function img_call_back(fileurl_tmp)
    {
      $("#value_local").val(fileurl_tmp);
      $("#img_a").attr('href', fileurl_tmp);
      $("#img_i").attr('onmouseover', "layer_tips=layer.tips('<img src="+fileurl_tmp+" class=\\'layer_tips_img\\'>',this,{tips: [1, '#fff']});");
    }

    /**
     * 封装的加载层
     */
    function layer_loading(msg){
        var loading = layer.msg(
        msg+'...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请勿刷新页面', 
        {
            icon: 1,
            time: 3600000, //1小时后后自动关闭
            shade: [0.2] //0.1透明度的白色背景
        });
        //loading层
        var index = layer.load(3, {
            shade: [0.1,'#fff'] //0.1透明度的白色背景
        });

        return loading;
    }
</script>

<br/>
<div id="goTop">
    <a href="JavaScript:void(0);" id="btntop">
        <i class="fa fa-angle-up"></i>
    </a>
    <a href="JavaScript:void(0);" id="btnbottom">
        <i class="fa fa-angle-down"></i>
    </a>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#think_page_trace_open').css('z-index', 99999);
    });
</script>
</body>
</html>