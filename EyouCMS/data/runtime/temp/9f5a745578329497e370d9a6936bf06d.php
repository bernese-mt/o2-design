<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:56:"./application/admin/template/archives/release_iframe.htm";i:1557218861;s:79:"/home/o2design/public_html/EyouCMS/application/admin/template/public/layout.htm";i:1557218894;s:79:"/home/o2design/public_html/EyouCMS/application/admin/template/public/footer.htm";i:1557218894;}*/ ?>
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

<body style="background-color: #FFF; overflow: auto; min-width: auto;">
<div id="toolTipLayer" style="position: absolute; z-index: 9999; display: none; visibility: visible; left: 95px; top: 573px;"></div>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page ksedit" style="min-width: auto;">
    <form class="form-horizontal" id="post_form" action="<?php echo url('Archives/release'); ?>" method="post">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="title"><em>*</em>发布至</label>
                </dt>
                <dd class="opt" style="width: auto;">
                    <select name="typeid" id="typeid" style="width: 300px;" size="22">
                        <?php echo $select_html; ?>
                    </select>
                    <!-- <input type="hidden" name="gourl" value=""> -->
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(function () {
        $('#typeid').find('option').click(function(){
            if (!$('#typeid').val()) {
                layer.alert('请选择栏目…！', {icon:5}, function(index){
                    $('#typeid').val('');
                    layer.close(index);
                });
                return false;
            }

            var current_channel = $('#typeid').find('option:selected').attr('data-current_channel');
            current_channel = parseInt(current_channel);
            var js_allow_channel_arr = <?php echo $js_allow_channel_arr; ?>;
            if ($.inArray(current_channel, js_allow_channel_arr) == -1) {
                layer.alert('该栏目模型不允许发布文档！', {icon:5}, function(index){
                    $('#typeid').val('');
                    layer.close(index);
                });
                return false;
            }

            var parentObj = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            parent.layer.close(parentObj);

            var url = "<?php echo url('Archives/release'); ?>";
            if (url.indexOf('?') > -1) {
                url += '&';
            } else {
                url += '?';
            }
            url += 'typeid=' + $('#typeid').val();

            <?php if($iframe == '2'): ?>
            parent.window.location = url;
            <?php else: ?>
            parent.content_body.location = url;
            <?php endif; ?>
        });
    });
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