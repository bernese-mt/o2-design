<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:46:"./application/admin/template/index\welcome.htm";i:1559529925;s:66:"D:\site\ada\o2-design\application\admin\template\public\footer.htm";i:1559529926;}*/ ?>
<!doctype html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-capable" content="yes">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link href="/o2-design/public/static/admin/css/main.css?v=<?php echo $version; ?>" rel="stylesheet" type="text/css">
<link href="/o2-design/public/static/admin/font/css/font-awesome.min.css" rel="stylesheet" />
<link href="/o2-design/public/static/admin/css/index.css?v=<?php echo $version; ?>" rel="stylesheet" type="text/css">
<!--[if IE 7]>
  <link rel="stylesheet" href="/o2-design/public/static/admin/font/css/font-awesome-ie7.min.css">
<![endif]-->
<script type="text/javascript">
    var eyou_basefile = "<?php echo \think\Request::instance()->baseFile(); ?>";
    var module_name = "<?php echo MODULE_NAME; ?>";
</script>  
<script type="text/javascript" src="/o2-design/public/static/admin/js/jquery.js"></script>
<script type="text/javascript" src="/o2-design/public/plugins/layer-v3.1.0/layer.js"></script>
<script src="/o2-design/public/static/admin/js/upgrade.js?v=<?php echo $version; ?>"></script>

</head>
<body style="background-color: #FFF; overflow: auto;">
<div id="toolTipLayer" style="position: absolute; z-index: 9999; display: none; visibility: visible; left: 95px; top: 573px;"></div>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<?php if(empty($system_explanation_welcome) || (($system_explanation_welcome instanceof \think\Collection || $system_explanation_welcome instanceof \think\Paginator ) && $system_explanation_welcome->isEmpty())): ?>
<div class="page" id="explanation_welcome">
    <!-- 操作說明 -->
    <div id="explanation" class="explanation" style="color: rgb(44, 188, 163); background-color: rgb(237, 251, 248); width: 99%; height: 100%;">
        <div id="checkZoom" class="title"><i class="fa fa-lightbulb-o"></i>
            <h4>系統提示</h4>
            <span title="收起提示" id="explanationZoom" style="display: block;"></span>
        </div>
        <ul>
            <li style="color: red;">系統檢測當前版本尚未備份數據庫，請點擊<a href="<?php echo url('Tools/index'); ?>">【數據備份】</a>進入操作。</li>
        </ul>
    </div>
</div>
<?php endif; ?>
<div class="warpper">
    <div class="title">管理中心</div>
    <div class="content start_content">
        <div class="contentWarp">
            <div class="index_box">
                <section class="index_point hidden-xs">
                    <h3>快捷入口</h3>
                    <div class="container-fluid">
                        <ul>
                            <?php if(is_check_access('System@index') == '1'): ?>
                            <li>
                                <a href="<?php echo url('System/index', array('inc_type'=>'web')); ?>">
                                    <i class="fa fa-newspaper-o"></i>
                                    基本資訊
                                </a>
                                <i class="fa fa-angle-right"></i>
                            </li>
                            <?php endif; if(is_check_access('Arctype@index') == '1'): ?>
                            <li>
                                <a href="<?php echo url('Arctype/index'); ?>" >
                                    <i class="fa fa-sitemap"></i>
                                    欄目管理
                                </a>
                                <i class="fa fa-angle-right"></i>
                            </li>
                            <?php endif; if(is_check_access('Filemanager@index') == '1'): ?>
                            <li>
                                <a href="<?php echo url('Filemanager/index'); ?>">
                                    <i class="fa fa-folder-open"></i>
                                    版型管理
                                </a>
                                <i class="fa fa-angle-right"></i>
                            </li>
                            <?php endif; if(is_check_access('Archives@release') == '1'): ?>
                            <li>
                                <a href="<?php echo url('Archives/release'); ?>">
                                    <i class="fa fa-plus"></i>
                                    發布內容
                                </a>
                                <i class="fa fa-angle-right" style="display:none"></i>
                            </li>
                            <?php endif; ?>
                            <li style="display: none;">
                                <div class="">
                                    <a href="javascript:void(0);" class="bds_more bdsharebuttonbox" data-cmd="more">
                                        <i class="fa fa-share-alt"></i>分享成果
                                    </a>
                                </div>
                                <script>
                                    window._bd_share_config = {
                                        "common":{
                                            "bdSnsKey":{},
                                            "bdText":"<?php echo $share['bdText']; ?>",
                                            "bdMini":"2",
                                            "bdMiniList":false,
                                            "bdPic":"",
                                            "bdStyle":"2",
                                            "bdSize":"32",
                                            "bdUrl":"<?php echo $share['bdUrl']; ?>",
                                            "bdRootPath":"/o2-design"
                                        },
                                        "share":{}
                                    };
                                    with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='public/plugins/share/api/js/share.js?v=<?php echo $version; ?>.js?cdnversion='+~(-new Date()/36e5)];
                                </script>
                            </li>
                        </ul>
                    </div>
                </section>
            </div>
            <div class="section system_section" style="float: none;width: inherit;">
                <div class="system_section_con hide">
                    <div class="sc_title" style="padding: 26px 0 14px;border-bottom: 1px solid #e4eaec;">
                        <i class="sc_icon"></i>
                        <h3>程式資訊</h3>
                    </div>
                    <div class="sc_warp" id="system_warp" style="display: block;">
                        <table cellpadding="0" cellspacing="0" class="system_table">
                            <tbody>
                                <tr>
                                    <td class="gray_bg">系統更新：</td>
                                    <td id="td_upgrade_msg">
                                        <div id="upgrade_filelist" style="display:none;"></div> 
                                        <div id="upgrade_intro" style="display:none;"></div> 
                                        <div id="upgrade_notice" style="display:none;"></div> 
                                        <a href="javascript:void(0);" id="a_upgrade" data-version="" data-max_version="" data-iframe="workspace" title="" data-tips_url="<?php echo url('Upgrade/setPopupUpgrade'); ?>" data-upgrade_url="<?php echo url('Upgrade/OneKeyUpgrade'); ?>" data-check_authority="<?php echo url('Upgrade/check_authority'); ?>">正在版本檢測中……</a>
                                    </td>
                                    <td class="gray_bg">當前版本：</td>
                                    <td><?php echo (isset($sys_info['curent_version']) && ($sys_info['curent_version'] !== '')?$sys_info['curent_version']:'v1.0'); ?></td>
                                </tr>
                                <tr>
                                    <td class="gray_bg">程式名稱：</td>
                                    <td><?php echo (isset($sys_info['web_name']) && ($sys_info['web_name'] !== '')?$sys_info['web_name']:'Eyoucms企業網站管理系統'); ?></td>
                                    <td class="gray_bg">版權所有：</td>
                                    <td><?php if(!(empty($is_eyou_authortoken) || (($is_eyou_authortoken instanceof \think\Collection || $is_eyou_authortoken instanceof \think\Paginator ) && $is_eyou_authortoken->isEmpty()))): ?>盜版必究<?php else: ?>正版軟件<?php endif; ?></td>
                                </tr>
                                <?php if(!(empty($is_eyou_authortoken) || (($is_eyou_authortoken instanceof \think\Collection || $is_eyou_authortoken instanceof \think\Paginator ) && $is_eyou_authortoken->isEmpty()))): ?>
                                <tr>
                                    <td class="gray_bg">更新日誌：</td>
                                    <td><a href="http://www.eyoucms.com/news/rizhi/" target="_blank">檢視</a></td>
                                    <td class="gray_bg">幫助中心:</td>
                                    <td><a href="http://www.eyoucms.com/bbs/" target="_blank">檢視</a></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="system_section_con">
                    <div class="sc_title" style="padding: 26px 0 14px;border-bottom: 1px solid #e4eaec;">
                        <i class="sc_icon"></i>
                        <h3>
                            服務器資訊
                        </h3>
                    </div>
                    <div class="sc_warp" id="system_warp" style="display: block;padding-bottom: 20px;">
                        <table cellpadding="0" cellspacing="0" class="system_table">
                            <tbody><tr>
                                <td class="gray_bg">服務器操作系統：</td>
                                <td><?php echo $sys_info['os']; ?></td>
                                <td class="gray_bg">服務器域名/IP：</td>
                                <td><?php echo $sys_info['domain']; ?> [ <?php echo $sys_info['ip']; ?> ]</td>
                            </tr>
                            <tr>
                                <td class="gray_bg">服務器環境：</td>
                                <td><?php echo $sys_info['web_server']; ?></td>
                                <td class="gray_bg">PHP 版本：</td>
                                <td><?php echo $sys_info['phpv']; ?></td>
                            </tr>
                            <tr>
                                <td class="gray_bg">Mysql 版本：</td>
                                <td><?php echo $sys_info['mysql_version']; ?></td>
                                <td class="gray_bg">GD 版本：</td>
                                <td><?php echo $sys_info['gdinfo']; ?></td>
                            </tr>
                            <tr>
                                <td class="gray_bg">檔案上傳限制：</td>
                                <td><?php echo $sys_info['fileupload']; ?></td>
                                <td class="gray_bg">最大佔用記憶體：</td>
                                <td><?php echo $sys_info['memory_limit']; ?></td>
                            </tr>
                            <tr>
                                <td class="gray_bg">POST限制：</td>
                                <td><?php echo (isset($sys_info['postsize']) && ($sys_info['postsize'] !== '')?$sys_info['postsize']:'unknown'); ?></td>
                                <td class="gray_bg">最大執行時間：</td>
                                <td><?php echo $sys_info['max_ex_time']; ?></td>
                            </tr>
                            <tr>
                                <td class="gray_bg">Zip支援：</td>
                                <td><?php echo $sys_info['zip']; ?></td>
                                <td class="gray_bg">Zlib支援：</td>
                                <td><?php echo $sys_info['zlib']; ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="footer" style="position: static; bottom: 0px; font-size:14px;">
    <p>
        <b><?php echo htmlspecialchars_decode($global['web_copyright']); ?></b>
    </p>
</div>
<script type="text/javascript">
    $(function () {
        check_upgrade_version();
        $.get("<?php echo url('Ajax/welcome_handle'); ?>"); // 進入歡迎頁面需要異步處理的業務
        // 版本檢測更新彈窗
        function check_upgrade_version(){
            $.ajax({
                type : "GET",
                url  : "<?php echo url('Ajax/check_upgrade_version'); ?>",
                data : {},
                dataType : "JSON",
                success: function(res) {
                    if (1 == res.code) {
                        if (2 == res.data.code) {
                            $('#upgrade_filelist').html(res.data.msg.upgrade);
                            $('#upgrade_intro').html(res.data.msg.intro);
                            $('#upgrade_notice').html(res.data.msg.notice);
                            $('#a_upgrade').attr('data-version', res.data.msg.key_num)
                            .attr('data-max_version', res.data.msg.max_version)
                            .attr('title', res.data.msg.tips);
                            $('#a_upgrade').html('檢測到新版本'+res.data.msg.key_num+'[點擊檢視]').css('color', '#F00');

                            <?php if(-1 != $web_show_popup_upgrade AND (0 >= \think\Session::get('admin_info.role_id') OR 1 == \think\Session::get('admin_info.auth_role_info.online_update'))): ?>
                                btn_upgrade($("#a_upgrade"), 1);
                            <?php endif; ?>
                        } else {
                            $('#td_upgrade_msg').html(res.data.msg);
                        }
                    }
                }
            }); 
        }
    });

    $(function() {
        //操作提示縮放動畫
        $("#checkZoom").click(function(){
            $("#explanation").animate({
                color: "#FFF",
                backgroundColor: "#4FD6BE",             
                width: "0",
                height: "0",                             
            },300,function(){
                $("#explanation_welcome").hide();
            });
            $("#explanationZoom").hide();
            $.get("<?php echo url('Ajax/explanation_welcome'); ?>");
        });

        checkInlet(); // 自動檢測隱藏index.php
    });

    // 自動檢測隱藏index.php
    function checkInlet() {
        layer.open({
            type: 2,
            title: false,
            area: ['0px', '0px'],
            shade: 0.0,
            closeBtn: 0,
            shadeClose: true,
            content: '//<?php echo \think\Request::instance()->host(); ?>/o2-design/api/Rewrite/setInlet.html',
            success: function(layero, index){
                layer.close(index);
                var body = layer.getChildFrame('body', index);
                var content = body.html();
                if (content.indexOf("Congratulations on passing") == -1)
                {
                    $.ajax({
                        type : "POST",
                        url  : "/o2-design/index.php?m=api&c=Rewrite&a=setInlet",
                        data : {seo_inlet:0},
                        dataType : "JSON",
                        success: function(res) {

                        }
                    }); 
                }
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