<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:44:"./application/admin/template/seo\sitemap.htm";i:1558667940;s:66:"D:\site\ada\o2-design\application\admin\template\public\layout.htm";i:1558672259;s:60:"D:\site\ada\o2-design\application\admin\template\seo\bar.htm";i:1558667939;s:66:"D:\site\ada\o2-design\application\admin\template\public\footer.htm";i:1558667897;}*/ ?>
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
<body style="background-color: #FFF; overflow: auto;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page atta">
        <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>SEO设置</h3>
                <h5></h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?php echo url('Seo/index',['inc_type'=>'seo']); ?>" <?php if('seo'==$inc_type): ?>class="current"<?php endif; ?>><span>SEO基础</span></a></li>

                <li><a href="<?php echo url('Seo/index',['inc_type'=>'sitemap']); ?>" <?php if('sitemap'==$inc_type): ?>class="current"<?php endif; ?>><span>Sitemp</span></a></li>

                <li id="tab_base_html" <?php if(isset($config['seo_pseudo']) && $config['seo_pseudo'] != 2): ?>style="display: none!important;"<?php endif; ?>><a href="<?php echo url('Seo/index',['inc_type'=>'html']); ?>" <?php if('html'==$inc_type): ?>class="current"<?php endif; ?>><span>生成静态</span></a></li>

                <!-- <li id="tab_base_rewrite" <?php if(isset($config['seo_pseudo']) && $config['seo_pseudo'] != 3): ?>style="display: none!important;"<?php endif; ?>><a href="<?php echo url('Seo/index',['inc_type'=>'rewrite']); ?>" <?php if('rewrite'==$inc_type): ?>class="current"<?php endif; ?>><span>伪静态</span></a></li> -->
            </ul>
        </div>
    </div>
    <form method="post" id="handlepost" action="<?php echo U('Seo/handle'); ?>" enctype="multipart/form-data" name="form1">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">自动生成</dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="sitemap_auto1" class="cb-enable <?php if(isset($config['sitemap_auto']) && $config['sitemap_auto'] == 1): ?>selected<?php endif; ?>" >开启</label>
                        <label for="sitemap_auto0" class="cb-disable <?php if(!isset($config['sitemap_auto']) || $config['sitemap_auto'] == 0): ?>selected<?php endif; ?>" >关闭</label>
                        <input id="sitemap_auto1" name="sitemap_auto" value="1" <?php if(isset($config['sitemap_auto']) && $config['sitemap_auto'] == 1): ?>checked<?php endif; ?> type="radio">
                        <input id="sitemap_auto0" name="sitemap_auto" value="0" <?php if(!isset($config['sitemap_auto']) || $config['sitemap_auto'] == 0): ?>checked<?php endif; ?> type="radio">
                    </div>
                    <p class="notic">更新内容时候自动更新网站地图</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>过滤栏目及内容</label>
                </dt>
                <dd class="opt">          
                    <label><input type="checkbox" name="sitemap_not1" value="1" <?php if(isset($config['sitemap_not1']) && $config['sitemap_not1'] == 1): ?>checked="checked"<?php endif; ?>>过滤隐藏栏目</label>&nbsp;
                    <label><input type="checkbox" name="sitemap_not2" value="1" <?php if(isset($config['sitemap_not2']) && $config['sitemap_not2'] == 1): ?>checked="checked"<?php endif; ?>>过滤外部模块</label>&nbsp;
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>Sitemap类型</label>
                </dt>
                <dd class="opt">          
                    <label><input type="checkbox" name="sitemap_xml" value="1" <?php if(isset($config['sitemap_xml']) && $config['sitemap_xml'] == 1): ?>checked="checked"<?php endif; ?>>Xml网站地图</label>&nbsp;
                    <br/>适合谷歌和百度，地图网址 <a href="http://ada.t420s.test:8080/o2-design/sitemap.xml" target="_blank">http://ada.t420s.test:8080/o2-design/sitemap.xml</a>
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row zzbaidu">
                <dt class="tit">
                    <label for="sitemap_zzbaidutoken">实时推送Url的token</label>
                </dt>
                <dd class="opt">
                    <input id="sitemap_zzbaidutoken" name="sitemap_zzbaidutoken" value="<?php echo (isset($config['sitemap_zzbaidutoken']) && ($config['sitemap_zzbaidutoken'] !== '')?$config['sitemap_zzbaidutoken']:''); ?>" class="input-txt" type="text" />
                    <p class="notic">这里填入 <a href="https://ziyuan.baidu.com" target="_blank" style="color: red;">百度搜索资源平台</a> 的准入密钥，才能第一时间推送文章链接给百度蜘蛛。</p>
                </dd>
            </dl>
            <div class="bot">
                <input type="hidden" name="inc_type" value="<?php echo $inc_type; ?>">
                <a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="adsubmit();">确认提交</a>
            </div>
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