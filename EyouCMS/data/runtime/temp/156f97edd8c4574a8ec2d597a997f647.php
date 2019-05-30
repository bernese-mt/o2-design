<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:48:"./application/admin/template/channeltype/add.htm";i:1557218825;s:79:"/home/o2design/public_html/EyouCMS/application/admin/template/public/layout.htm";i:1557218894;s:79:"/home/o2design/public_html/EyouCMS/application/admin/template/public/footer.htm";i:1557218894;}*/ ?>
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
<link href="/EyouCMS/public/static/admin/css/main.css?v=<?php echo $version; ?>" rel="stylesheet" type="text/css">
<link href="/EyouCMS/public/static/admin/css/page.css?v=<?php echo $version; ?>" rel="stylesheet" type="text/css">
<link href="/EyouCMS/public/static/admin/font/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
  <link rel="stylesheet" href="/EyouCMS/public/static/admin/font/css/font-awesome-ie7.min.css">
<![endif]-->
<script type="text/javascript">
    var eyou_basefile = "<?php echo \think\Request::instance()->baseFile(); ?>";
    var module_name = "<?php echo MODULE_NAME; ?>";
    var GetUploadify_url = "<?php echo url('Uploadify/upload'); ?>";
    var __root_dir__ = "/EyouCMS";
</script>  
<link href="/EyouCMS/public/static/admin/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
<link href="/EyouCMS/public/static/admin/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css"/>
<style type="text/css">html, body { overflow: visible;}</style>
<script type="text/javascript" src="/EyouCMS/public/static/admin/js/jquery.js"></script>
<script type="text/javascript" src="/EyouCMS/public/static/admin/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="/EyouCMS/public/plugins/layer-v3.1.0/layer.js"></script>
<script type="text/javascript" src="/EyouCMS/public/static/admin/js/jquery.cookie.js"></script>
<script type="text/javascript" src="/EyouCMS/public/static/admin/js/admin.js?v=<?php echo $version; ?>"></script>
<script type="text/javascript" src="/EyouCMS/public/static/admin/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="/EyouCMS/public/static/admin/js/common.js?v=<?php echo $version; ?>"></script>
<script type="text/javascript" src="/EyouCMS/public/static/admin/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="/EyouCMS/public/static/admin/js/jquery.mousewheel.js"></script>
<script src="/EyouCMS/public/static/admin/js/myFormValidate.js"></script>
<script src="/EyouCMS/public/static/admin/js/myAjax2.js?v=<?php echo $version; ?>"></script>
<script src="/EyouCMS/public/static/admin/js/global.js?v=<?php echo $version; ?>"></script>

</head>
<body style="background-color: #FFF; overflow: auto;">
<div id="toolTipLayer" style="position: absolute; z-index: 9999; display: none; visibility: visible; left: 95px; top: 573px;"></div>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <a class="back" href="javascript:history.back();" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>模型管理</h3>
                <h5></h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?php echo url('Channeltype/index'); ?>" class="tab <?php if(\think\Request::instance()->controller() == 'Channeltype'): ?>current<?php endif; ?>"><span>新增模型</span></a></li>
            </ul>
        </div>
    </div>
    <form class="form-horizontal" id="post_form" action="<?php echo url('Channeltype/add'); ?>" method="post">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="title"><em>*</em>模型名称</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="title" value="" id="title" class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="nid"><em>*</em>模型标识</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="nid" value="" id="nid" class="input-txt" onkeyup="this.value=this.value.replace(/[^a-z0-9]/g,'');" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^a-z0-9]/g,''));">
                    <span class="err"></span>
                    <p class="">与文档的模板相关连，建议由小写字母、数字组成，因为部份Unix系统无法识别中文文件。<br/>列表模板是：lists_模型标识.htm<br/>文档模板是：view_模型标识.htm</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>文档标题重复</label>
                </dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="is_repeat_title1" class="cb-enable selected">允许</label>
                        <label for="is_repeat_title0" class="cb-disable">不允许</label>
                        <input id="is_repeat_title1" name="is_repeat_title" value="1" type="radio" checked="checked">
                        <input id="is_repeat_title0" name="is_repeat_title" value="0" type="radio">
                    </div>
                    <p class="notic">新增/编辑文档时，是否允许标题的重复</p>
                </dd>
            </dl>
            <div class="bot">
                <a href="JavaScript:void(0);" onclick="checkForm();" class="ncap-btn-big ncap-btn-green" id="submitBtn">确认提交</a>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    // 判断输入框是否为空
    function checkForm(){
        if($.trim($('input[name=title]').val()) == ''){
            showErrorMsg('模型名称不能为空！');
            $('input[name=title]').focus();
            return false;
        }
        var nid = $.trim($('input[name=nid]').val());
        if(nid == ''){
            showErrorMsg('模型标识不能为空！');
            $('input[name=nid]').focus();
            return false;
        } else {
            var reg = /^([a-z]+)([a-z0-9]*)$/i;
            if(!reg.test(nid)){
                showErrorMsg('模型标识必须以小写字母开头！');
                $('input[name=nid]').focus();
                // return false;
            }
        }

        layer_loading('正在处理');
        $('#post_form').submit();
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