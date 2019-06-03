<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:56:"./application/admin/template/archives\index_archives.htm";i:1559529923;s:66:"D:\site\ada\o2-design\application\admin\template\public\layout.htm";i:1559529926;s:70:"D:\site\ada\o2-design\application\admin\template\archives\tags_btn.htm";i:1559529923;s:66:"D:\site\ada\o2-design\application\admin\template\public\footer.htm";i:1559529926;}*/ ?>
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

<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;min-width:auto;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page" style="min-width:auto;">
    <div class="flexigrid" style="margin-top: 0px;">
        <div class="mDiv" style="padding: 5px 0px;">
            <div class="ftitle">
                <h3><?php echo (isset($arctype_info['typename']) && ($arctype_info['typename'] !== '')?$arctype_info['typename']:'所有文件'); ?></h3>
                <h5>(共<?php echo $pager->totalRows; ?>條記錄)</h5>
            </div>
            <div title="重新整理數據" class="pReload"><i class="fa fa-refresh"></i></div>
            <form id="search_form" class="navbar-form form-inline" action="<?php echo url('Archives/index_archives'); ?>" method="get" onsubmit="layer_loading('正在處理');">
                <?php echo (isset($searchform['hidden']) && ($searchform['hidden'] !== '')?$searchform['hidden']:''); ?>
                <div class="sDiv">
                    <?php if(is_check_access('Archives@add') == '1'): ?>
                        <div class="fbutton" style="float: none;">
                              <a href="javascript:void(0);" onClick="<?php if(\think\Request::instance()->param('typeid') > '0'): ?>window.location.href='<?php echo url("Archives/add", array("typeid"=>\think\Request::instance()->param('typeid'))); ?>';<?php else: ?>quick_release();<?php endif; ?>">
                                  <div class="add">
                                      <span><i class="fa fa-plus"></i>發佈文件</span>
                                  </div>
                              </a>
                        </div>
                    <?php endif; ?>
                    <!-- 擴充套件 -->
                    <?php if(is_check_access('Tags@index') == '1'): ?>
                        <div class="fbutton" style="float: none; display: none;">
                              <a href="javascript:void(0);" onClick="tags_list();">
                                  <div class="add">
                                      <span><i class="fa fa-tag"></i>TAG標籤管理</span>
                                  </div>
                              </a>
                        </div>
                        <script type="text/javascript">
                            function tags_list(obj)
                            {
                                var url = "<?php echo url('Tags/index'); ?>";
                                //iframe窗
                                layer.open({
                                    type: 2,
                                    title: 'TAG標籤管理',
                                    fixed: true, //不固定
                                    shadeClose: false,
                                    shade: 0.3,
                                    maxmin: true, //開啟最大化最小化按鈕
                                    area: ['80%', '80%'],
                                    content: url
                                });
                            }
                        </script>
                    <?php endif; ?>
<!--                     <div class="sDiv2">  
                        <select name="typeid" id="typeid" class="select" style="margin:0px 5px;">
                            <option value="">--所有文件--</option>
                            <?php echo $arctype_html; ?>
                        </select>
                    </div> -->
                    <div class="sDiv2">
                        <input type="text" size="30" name="keywords" value="<?php echo \think\Request::instance()->param('keywords'); ?>" class="qsbox" placeholder="標題搜索...">
                        <input type="submit" class="btn" value="搜索">
                    </div>
                    <!-- <div class="sDiv2">
                        <input type="button" class="btn" value="重置" onClick="window.location.href='<?php echo url('Archives/index_archives', array("typeid"=>\think\Request::instance()->param('typeid'))); ?>';">
                    </div> -->
                </div>
            </form>
        </div>
        <div class="hDiv">
            <div class="hDivBox">
                <table cellspacing="0" cellpadding="0" style="width: 100%">
                    <thead>
                    <tr>
                        <th class="sign w40" axis="col0">
                            <div class="tc">選擇</div>
                        </th>
                        <th abbr="article_title" axis="col3" class="w40">
                            <div class="tc">ID</div>
                        </th>
                        <th abbr="article_title" axis="col3" class="">
                            <div style="text-align: left; padding-left: 10px;" class="">標題</div>
                        </th>
                        <th abbr="article_time" axis="col6" class="w100">
                            <div class="tc">所屬欄目</div>
                        </th>
                        <th abbr="article_time" axis="col6" class="w50">
                            <div class="tc">推薦</div>
                        </th>
                        <th abbr="article_time" axis="col6" class="w60">
                            <div class="tc">瀏覽量</div>
                        </th>
                        <th abbr="article_time" axis="col6" class="w100">
                            <div class="tc">更新時間</div>
                        </th>
                        <th axis="col1" class="w180">
                            <div class="tc">操作</div>
                        </th>
                        <th abbr="article_time" axis="col6" class="w60">
                            <div class="tc">排序</div>
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="bDiv" style="height: auto;">
            <div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
                <table style="width: 100%;">
                    <tbody>
                    <?php if(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty())): ?>
                        <tr>
                            <td class="no-data" align="center" axis="col0" colspan="50">
                                <i class="fa fa-exclamation-circle"></i>沒有符合條件的記錄
                            </td>
                        </tr>
                    <?php else: if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $k=>$vo): ?>
                        <tr>
                            <td class="sign">
                                <div class="tc w40"><input type="checkbox" name="ids[]" value="<?php echo $vo['aid']; ?>"></div>
                            </td>
                           
                            <td class="sort">
                                <div class="tc w40">
                                    <?php echo $vo['aid']; ?>
                                </div>
                            </td>
                            <td class="" style="width: 100%;">
                                <div class="tl" style="padding-left: 10px;">
                                    <?php if(!(empty($vo['litpic']) || (($vo['litpic'] instanceof \think\Collection || $vo['litpic'] instanceof \think\Paginator ) && $vo['litpic']->isEmpty()))): ?>
                                        <img src="/o2-design/public/static/admin/images/pic.gif" onmouseover="layer_tips=layer.tips('<img src=<?php echo $vo['litpic']; ?> class=\'layer_tips_img\'>',this,{tips: [1, '#fff']});" onmouseout="layer.close(layer_tips);">&nbsp;
                                    <?php endif; if(is_check_access('Archives@edit') == '1'): ?>
                                    <a href="<?php echo url($channelRow[$vo['channel']]['ctl_name'].'/edit',array('id'=>$vo['aid'],'typeid'=>\think\Request::instance()->param('typeid'),'gourl'=>$gourl)); ?>" style="<?php if($vo['is_b'] == '1'): ?> font-weight: bold;<?php endif; ?>"><?php echo $vo['title']; ?></a>
                                    <?php else: ?>
                                    <?php echo $vo['title']; endif; ?>
                                </div>
                            </td>
                            <td class="">
                                <div class="w100 tc"><a href="<?php echo url('Archives/index_archives', array('typeid'=>$vo['typeid'])); ?>"><?php echo (isset($vo['typename']) && ($vo['typename'] !== '')?$vo['typename']:'<i class="red">數據出錯！</i>'); ?></a></div>
                            </td>
                            <td>
                                <div class="tc w50">
                                    <?php if($vo['is_recom'] == 1): ?>
                                        <span class="yes" <?php if(is_check_access('Archives@edit') == '1'): ?>onClick="changeTableVal('archives','aid','<?php echo $vo['aid']; ?>','is_recom',this);"<?php endif; ?> ><i class="fa fa-check-circle"></i>是</span>
                                    <?php else: ?>
                                        <span class="no" <?php if(is_check_access('Archives@edit') == '1'): ?>onClick="changeTableVal('archives','aid','<?php echo $vo['aid']; ?>','is_recom',this);"<?php endif; ?> ><i class="fa fa-ban"></i>否</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="tc w60">
                                    <?php echo $vo['click']; ?>
                                </div>
                            </td>
                            <td>
                                <div class="w100 tc">
                                    <?php echo date('Y-m-d',$vo['update_time']); ?>
                                </div>
                            </td>
                            <td class="">
                                <div class="w180 tc">
                                    <?php if(is_check_access('Archives@edit') == '1'): ?>
                                    <a href="<?php echo url($channelRow[$vo['channel']]['ctl_name'].'/edit',array('id'=>$vo['aid'],'typeid'=>\think\Request::instance()->param('typeid'),'gourl'=>$gourl)); ?>" class="btn blue"><i class="fa fa-pencil-square-o"></i>編輯</a>
                                    <?php endif; if(is_check_access('Archives@del') == '1'): ?>
                                    <a class="btn red"  href="javascript:void(0);" data-url="<?php echo url('Archives/del'); ?>" data-id="<?php echo $vo['aid']; ?>" data-deltype="pseudo" onClick="delfun(this);"><i class="fa fa-trash-o"></i>刪除</a>
                                    <a href="<?php echo $vo['arcurl']; ?>" target="_blank" class="btn blue"><i class="fa fa-pencil-square-o"></i>瀏覽</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                             <td class="sort">
                                <div class="w60 tc">
                                    <?php if(is_check_access('Archives@edit') == '1'): ?>
                                    <input type="text" onchange="changeTableVal('archives','aid','<?php echo $vo['aid']; ?>','sort_order',this);"  size="4"  value="<?php echo $vo['sort_order']; ?>" />
                                    <?php else: ?>
                                    <?php echo $vo['sort_order']; endif; ?>
                                </div>
                            </td>
                            
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="iDiv" style="display: none;"></div>
        </div>
        <div class="tDiv">
            <div class="tDiv2">
                <div class="fbutton checkboxall">
                    <input type="checkbox" onclick="javascript:$('input[name*=ids]').prop('checked',this.checked);">
                </div>
                <?php if(is_check_access('Archives@del') == '1'): ?>
                <div class="fbutton">
                    <a onclick="batch_del(this, 'ids');" data-url="<?php echo url('Archives/del'); ?>" data-deltype="pseudo">
                        <div class="add" title="批量刪除">
                            <span><i class="fa fa-close"></i>批量刪除</span>
                        </div>
                    </a>
                </div>
                <?php endif; if(is_check_access('Archives@move') == '1'): ?>
                <div class="fbutton">
                    <a onclick="func_move(this, 'ids');" data-url="<?php echo url('Archives/move', array('typeid'=>\think\Request::instance()->param('typeid'))); ?>">
                        <div class="add" title="批量移動">
                            <span><i class="fa fa-hdd-o"></i>批量移動</span>
                        </div>
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <div style="clear:both"></div>
        </div>
        <!--分頁位置-->
        <?php echo $page; ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){

        // 表格行點選選中切換
        $('#flexigrid > table>tbody >tr').click(function(){
            $(this).toggleClass('trSelected');
        });

        // 點選重新整理數據
        $('.fa-refresh').click(function(){
            location.href = location.href;
        });

        // 選擇欄目檢索出相應文件列表
        $('#typeid').change(function(){
          $('#search_form').submit();
        });
    });

    var aids = '';
    function func_move(obj, name)
    {
        var a = [];
        var k = 0;
        aids = '';
        $('input[name^='+name+']').each(function(i,o){
            if($(o).is(':checked')){
                a.push($(o).val());
                if (k > 0) {
                    aids += ',';
                }
                aids += $(o).val();
                k++;
            }
        })
        if(a.length == 0){
            layer.alert('請至少選擇一項', {icon: 2, title:false});
            return;
        }

        var url = $(obj).attr('data-url');
        //iframe窗
        layer.open({
            type: 2,
            title: '移動文件',
            fixed: true, //不固定
            shadeClose: false,
            shade: 0.3,
            maxmin: false, //開啟最大化最小化按鈕
            area: ['350px', '260px'],
            content: url
        });
    }

    function quick_release()
    {
        //iframe窗
        layer.open({
            type: 2,
            title: '快捷發佈文件',
            fixed: true, //不固定
            shadeClose: false,
            shade: 0.3,
            maxmin: true, //開啟最大化最小化按鈕
            area: ['600px', '520px'],
            content: "<?php echo url('Archives/release', array('iframe'=>2)); ?>",
            success: function(layero, index){
                // var body = layer.getChildFrame('body', index);
                // var gourl = $('.curSelectedNode').attr('href');
                // if (!$.trim(gourl)) {
                //     gourl = "<?php echo url('Archives/index_archives'); ?>";
                // }
                // body.find('input[name=gourl]').val(gourl);
            }
        });
    }

    /**
     * 獲取修改之前的內容
     */
    function get_aids()
    {
        return aids;
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