<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:45:"./application/admin/template/system/thumb.htm";i:1557218887;s:79:"/home/o2design/public_html/EyouCMS/application/admin/template/public/layout.htm";i:1557218894;s:76:"/home/o2design/public_html/EyouCMS/application/admin/template/system/bar.htm";i:1557218887;s:79:"/home/o2design/public_html/EyouCMS/application/admin/template/public/footer.htm";i:1557218894;}*/ ?>
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
<body style="background-color: #FFF; overflow: auto;min-width:auto;">
<div id="toolTipLayer" style="position: absolute; z-index: 9999; display: none; visibility: visible; left: 95px; top: 573px;"></div>
<style>
    .span_1 {
        float: left;
        margin-left: 0px;
        height: 130px;
        line-height: 130px;
    }

    .span_1 ul {
        list-style: none;
        padding: 0px;
    }

    .span_1 ul li {
        border: 1px solid #CCC;
        height: 40px;
        padding: 0px 10px;
        margin-left: -1px;
        margin-top: -1px;
        line-height: 40px;
    }
    #thumb_color {
        /*margin:0;*/
        /*padding:0;*/
        border:solid 1px #ccc;
        width:70px;
        height:20px;
        border-right:40px solid green;
        /*line-height:20px;*/
    }
</style>
<script type="text/javascript" src="/EyouCMS/public/plugins/colpick/js/colpick.js"></script>
<link href="/EyouCMS/public/plugins/colpick/css/colpick.css" rel="stylesheet" type="text/css"/>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page" style="min-width:auto;">
    <?php if(\think\Request::instance()->param('tabase') != '-1'): ?>
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>基本信息</h3>
                <h5></h5>
            </div>
            <ul class="tab-base nc-row">
                <?php if(is_check_access(CONTROLLER_NAME.'@web') == '1'): ?>
                <li><a href="<?php echo url('System/web'); ?>" <?php if('web'==ACTION_NAME): ?>class="current"<?php endif; ?>><span>网站设置</span></a></li>
                <?php endif; if($main_lang == $admin_lang): if(is_check_access(CONTROLLER_NAME.'@web2') == '1'): ?>
                <li><a href="<?php echo url('System/web2'); ?>" <?php if('web2'==ACTION_NAME): ?>class="current"<?php endif; ?>><span>核心设置</span></a></li>
                <?php endif; endif; if(is_check_access(CONTROLLER_NAME.'@basic') == '1'): ?>
                <li><a href="<?php echo url('System/basic'); ?>" <?php if('basic'==ACTION_NAME): ?>class="current"<?php endif; ?>><span>附件设置</span></a></li>
                <?php endif; if($main_lang == $admin_lang): if(is_check_access(CONTROLLER_NAME.'@water') == '1'): ?>
                <li><a href="<?php echo url('System/water'); ?>" <?php if(in_array(ACTION_NAME, ['water','thumb'])): ?>class="current"<?php endif; ?>><span>图片水印</span></a></li>
                <?php endif; endif; if($main_lang == $admin_lang): if(is_check_access(CONTROLLER_NAME.'@smtp') == '1'): ?>
                <li><a href="<?php echo url('System/smtp'); ?>" <?php if(preg_match('/^smtp/i', ACTION_NAME)): ?>class="current"<?php endif; ?>><span>接口配置</span></a></li>
                <?php endif; endif; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>
    <div class="flexigrid">
        <?php if(-1 != \think\Request::instance()->param('tabase')): ?>
        <div class="mDiv">
            <div class="ftitle">
                <h3>缩略图配置</h3>
            </div>
            <div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div>
            <div class="sDiv">
                <div class="sDiv2 addartbtn fl" style="margin-right: 6px;">
                    <input type="button" class="btn" value="缩略图配置" onclick="window.location.href='<?php echo url("System/thumb", ['tabase'=>\think\Request::instance()->param('tabase')]); ?>';">
                </div>
            </div>
            <div class="sDiv">
                <div class="sDiv2 addartbtn fl" style="margin-right: 6px;">
                    <input type="button" class="btn current" value="水印配置" onclick="window.location.href='<?php echo url("System/water", ['tabase'=>\think\Request::instance()->param('tabase')]); ?>';">
                </div>
            </div>
        </div>
        <?php endif; ?>
        <form class="form-horizontal" method="post" id="handlepost" action="<?php echo url('System/thumb'); ?>">
            <div class="ncap-form-default">
                <dl class="row">
                    <dt class="tit">缩略图功能</dt>
                    <dd class="opt">
                        <div class="onoff">
                            <label for="thumb_open1" class="cb-enable <?php if(isset($config['thumb_open']) && $config['thumb_open'] == 1): ?>selected<?php endif; ?>" >开启</label>
                            <label for="thumb_open0" class="cb-disable <?php if(!isset($config['thumb_open']) || $config['thumb_open'] == 0): ?>selected<?php endif; ?>" >关闭</label>
                            <input id="thumb_open1" name="thumb_open" onclick="setthumb(this);" value="1" <?php if(isset($config['thumb_open']) && $config['thumb_open'] == 1): ?>checked<?php endif; ?> type="radio">
                            <input id="thumb_open0" name="thumb_open" onclick="setthumb(this);" value="0" <?php if(!isset($config['thumb_open']) || $config['thumb_open'] == 0): ?>checked<?php endif; ?> type="radio">
                        </div>
                        <p class="notic">全站图片进行缩略图处理</p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label>生成方式</label>
                    </dt>
                    <dd class="opt">
                        <label><input type="radio" name="thumb_mode" value="1" <?php if(isset($config['thumb_mode']) AND $config['thumb_mode'] == 1): ?>checked="checked"<?php endif; ?>>拉伸</label>&nbsp;
                        <br/>
                        <label><input type="radio" name="thumb_mode" value="2" <?php if(!isset($config['thumb_mode']) OR $config['thumb_mode'] == 2): ?>checked="checked"<?php endif; ?>>留白</label>&nbsp;
                        <br/>
                        <label><input type="radio" name="thumb_mode" value="3" <?php if(isset($config['thumb_mode']) AND $config['thumb_mode'] == 3): ?>checked="checked"<?php endif; ?>>截减</label>&nbsp;
                        <span class="err"></span>
                        <p class="notic"></p>
                    </dd>
                </dl>
                <dl class="row thumb_mode_2" style="display:none;">
                    <dt class="tit">
                        <label for="thumb_color">填充颜色</label>
                    </dt>
                    <dd class="opt">
                        <input type="text" name="thumb_color" value="<?php echo (isset($config['thumb_color']) && ($config['thumb_color'] !== '')?$config['thumb_color']:'#FFFFFF'); ?>" id="thumb_color" style="border-color: <?php echo (isset($config['thumb_color']) && ($config['thumb_color'] !== '')?$config['thumb_color']:'#FFFFFF'); ?>;" />
                        <p class="notic"></p>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit">
                        <label for="title">缩略图大小</label>
                    </dt>
                    <dd class="opt">
                        <input name="thumb_width" id="thumb_width" value="<?php echo $config['thumb_width']; ?>" type="text" style="width: 50px;" /> X <input name="thumb_height" id="thumb_height" value="<?php echo $config['thumb_height']; ?>" type="text" style="width: 50px;" /> (宽 X 高) (像素)
                        <p class="notic"></p>
                    </dd>
                </dl>
                <div class="bot">
                    <input type="hidden" name="tabase" value="<?php echo \think\Request::instance()->param('tabase'); ?>">
                    <a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="adsubmit();">确认提交</a>
                    &nbsp;&nbsp;<a href="javascript:void(0);" onclick="tag_call('thumb_open');">缩略图标签高级调用？</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        // 点击刷新数据
        $('.fa-refresh').click(function(){
            location.href = location.href;
        });

        setthumb('auto'); // 关闭状态下禁用表单录入

        // 颜色选择
        $('#thumb_color').colpick({
            flat:false,
            layout:'rgbhex',
            submit:0,
            colorScheme:'light',
            color:$('#thumb_color').val(),
            onChange:function(hsb,hex,rgb,el,bySetColor) {
                $(el).css('border-color','#'+hex);
                // Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
                if(!bySetColor) $(el).val('#'+hex);
            }
        }).keyup(function(){
            $(this).colpickSetColor('#'+this.value);
        });
    });

    function adsubmit(){
        layer_loading('正在处理');
        $('#handlepost').submit();
    }

    function setthumb(obj){
        if ('auto' == obj) {
            $('input[name="thumb_mode"]').attr('disabled', true);
            $('input[name="thumb_width"]').attr('disabled', true);
            $('input[name="thumb_height"]').attr('disabled', true);
            $('input[name="thumb_color"]').attr('disabled', true);
            var thumb_open = $('input[name="thumb_open"]:checked').val();
            if (1 == thumb_open) {
                $('input[name="thumb_mode"]').attr('disabled', false);
                $('input[name="thumb_width"]').attr('disabled', false);
                $('input[name="thumb_height"]').attr('disabled', false);
                $('input[name="thumb_color"]').attr('disabled', false);
            }
        } else {
            var thumb_open = $(obj).val();
            if (1 == thumb_open) {
                $('input[name="thumb_mode"]').attr('disabled', false);
                $('input[name="thumb_width"]').attr('disabled', false);
                $('input[name="thumb_height"]').attr('disabled', false);
                $('input[name="thumb_color"]').attr('disabled', false);
            } else {
                $('input[name="thumb_mode"]').attr('disabled', true);
                $('input[name="thumb_width"]').attr('disabled', true);
                $('input[name="thumb_height"]').attr('disabled', true);
                $('input[name="thumb_color"]').attr('disabled', true);
            } 
        }
    }

    function tag_call(name)
    {
        $.ajax({
            type: "POST",
            url: "<?php echo url('System/ajax_tag_call'); ?>",
            data: {name:name},
            dataType: 'json',
            success: function (res) {
                if(res.code == 1){
                    //询问框
                    var confirm = layer.confirm(res.data.msg, {
                            title: '缩略图标签高级调用'
                            ,area: ['60%','80%']
                            ,btn: ['关闭'] //按钮

                        }, function(){
                            layer.close(confirm);
                        }
                    );  
                }else{
                    layer.alert(res.msg, {icon: 2});
                }
            },
            error:function(){
                layer.alert('网络失败，请刷新页面后重试', {icon: 2});
            }
        });
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