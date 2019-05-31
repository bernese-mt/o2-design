<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:45:"./application/admin/template/system\basic.htm";i:1558672259;s:66:"D:\site\ada\o2-design\application\admin\template\public\layout.htm";i:1558672259;s:63:"D:\site\ada\o2-design\application\admin\template\system\bar.htm";i:1558667890;s:66:"D:\site\ada\o2-design\application\admin\template\public\footer.htm";i:1558667897;}*/ ?>
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
<link href="/o2-design/public/static/admin/css/main.css?v=<?php echo $version; ?>" rel="stylesheet" type="text/css">
<link href="/o2-design/public/static/admin/css/page.css?v=<?php echo $version; ?>" rel="stylesheet" type="text/css">
<link href="/o2-design/public/static/admin/font/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
  <link rel="stylesheet" href="/o2-design/public/static/admin/font/css/font-awesome-ie7.min.css">
<![endif]-->
<script type="text/javascript">
    var eyou_basefile = "<?php echo \think\Request::instance()->baseFile(); ?>";
    var module_name = "<?php echo MODULE_NAME; ?>";
    var GetUploadify_url = "<?php echo url('Uploadify/upload'); ?>";
    var __root_dir__ = "/o2-design";
    var __lang__ = "<?php echo $admin_lang; ?>";
</script>  
<link href="/o2-design/public/static/admin/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
<link href="/o2-design/public/static/admin/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css"/>
<style type="text/css">html, body { overflow: visible;}</style>
<script type="text/javascript" src="/o2-design/public/static/admin/js/jquery.js"></script>
<script type="text/javascript" src="/o2-design/public/static/admin/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="/o2-design/public/plugins/layer-v3.1.0/layer.js"></script>
<script type="text/javascript" src="/o2-design/public/static/admin/js/jquery.cookie.js"></script>
<script type="text/javascript" src="/o2-design/public/static/admin/js/admin.js?v=<?php echo $version; ?>"></script>
<script type="text/javascript" src="/o2-design/public/static/admin/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="/o2-design/public/static/admin/js/common.js?v=<?php echo $version; ?>"></script>
<script type="text/javascript" src="/o2-design/public/static/admin/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="/o2-design/public/static/admin/js/jquery.mousewheel.js"></script>
<script src="/o2-design/public/static/admin/js/myFormValidate.js"></script>
<script src="/o2-design/public/static/admin/js/myAjax2.js?v=<?php echo $version; ?>"></script>
<script src="/o2-design/public/static/admin/js/global.js?v=<?php echo $version; ?>"></script>

</head>
<body style="background-color: #FFF; overflow-y: scroll;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page atta">
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
    <form method="post" enctype="multipart/form-data" name="form1" id="handlepost" action="<?php echo U('System/basic'); ?>">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="basic_indexname">主页链接名</label>
                </dt>
                <dd class="opt">
                    <input id="basic_indexname" name="basic_indexname" value="<?php echo (isset($config['basic_indexname']) && ($config['basic_indexname'] !== '')?$config['basic_indexname']:'首页'); ?>" class="input-txt" type="text" />
                    <p class="notic"></p>
                </dd>
            </dl>
            <?php if($main_lang == $admin_lang): ?>
            <dl class="row">
                <dt class="tit">
                    <label for="list_symbol">栏目位置的间隔符号</label>
                </dt>
                <dd class="opt">
                    <input id="list_symbol" name="list_symbol" value="<?php echo (isset($config['list_symbol']) && ($config['list_symbol'] !== '')?$config['list_symbol']:' > '); ?>" class="input-txt" type="text" />
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="image_type">允许上传的图片类型</label>
                </dt>
                <dd class="opt">
                    <textarea rows="5" cols="60" id="image_type" name="image_type" style="height:40px;"><?php if(empty($config['image_type']) || (($config['image_type'] instanceof \think\Collection || $config['image_type'] instanceof \think\Paginator ) && $config['image_type']->isEmpty())): ?>jpg|gif|png|bmp|jpeg|ico<?php else: ?><?php echo $config['image_type']; endif; ?></textarea>
                    <p class="notic">禁止非图片的扩展名，比如：php</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="file_type">允许上传的软件类型</label>
                </dt>
                <dd class="opt">
                    <textarea rows="5" cols="60" id="file_type" name="file_type" style="height:40px;"><?php if(empty($config['file_type']) || (($config['file_type'] instanceof \think\Collection || $config['file_type'] instanceof \think\Paginator ) && $config['file_type']->isEmpty())): ?>zip|gz|rar|iso|doc|xsl|ppt|wps<?php else: ?><?php echo $config['file_type']; endif; ?></textarea>
                    <p class="notic">禁止非软件的扩展名，比如：php</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="media_type">允许多媒体文件类型</label>
                </dt>
                <dd class="opt">
                    <textarea rows="5" cols="60" id="media_type" name="media_type" style="height:40px;"><?php if(empty($config['media_type']) || (($config['media_type'] instanceof \think\Collection || $config['media_type'] instanceof \think\Paginator ) && $config['media_type']->isEmpty())): ?>swf|mpg|mp3|rm|rmvb|wmv|wma|wav|mid|mov|mp4<?php else: ?><?php echo $config['media_type']; endif; ?></textarea>
                    <p class="notic">禁止非媒体的扩展名，比如：php</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="file_size">附件上传大小</label>
                </dt>
                <dd class="opt">
                    <select id="file_size" name="file_size">
                        <option value="1" <?php if($config['file_size'] == 1): ?>selected="selected"<?php endif; ?>>1M</option>
                        <option value="2" <?php if($config['file_size'] == 2): ?>selected="selected"<?php endif; ?>>2M</option>
                        <option value="3" <?php if($config['file_size'] == 3): ?>selected="selected"<?php endif; ?>>3M</option>
                        <option value="5" <?php if($config['file_size'] == 4): ?>selected="selected"<?php endif; ?>>5M</option>
                        <option value="10" <?php if($config['file_size'] == 10): ?>selected="selected"<?php endif; ?>>10M</option>
                        <option value="50" <?php if($config['file_size'] == 50): ?>selected="selected"<?php endif; ?>>50M</option>
                        <option value="100" <?php if($config['file_size'] == 100): ?>selected="selected"<?php endif; ?>>100M</option>
                        <option value="300" <?php if($config['file_size'] == 300): ?>selected="selected"<?php endif; ?>>300M</option>
                        <option value="500" <?php if($config['file_size'] == 500): ?>selected="selected"<?php endif; ?>>500M</option>
                        <option value="1000" <?php if($config['file_size'] == 1000): ?>selected="selected"<?php endif; ?>>1000M</option>
                    </select>
                    <p class="notic">附件上传大小限制，如果空间不支持，请与空间商联系修改php.ini部分参数</p>
                </dd>
            </dl>
            <?php endif; ?>
            <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="adsubmit();">确认提交</a></div>
        </div>
    </form>
</div>

<script type="text/javascript">
    function adsubmit(){
        layer_loading("正在处理");
        $('#handlepost').submit();
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