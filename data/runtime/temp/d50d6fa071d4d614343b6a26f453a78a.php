<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:56:"./application/admin/template/product\attribute_index.htm";i:1558667867;s:66:"D:\site\ada\o2-design\application\admin\template\public\layout.htm";i:1558672259;s:64:"D:\site\ada\o2-design\application\admin\template\product\bar.htm";i:1558667866;s:66:"D:\site\ada\o2-design\application\admin\template\public\footer.htm";i:1558667897;}*/ ?>
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

<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    
    <div class="fixed-bar">
        <div class="item-title">
            <a class="back" href="javascript:history.back();" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>内容管理</h3>
                <h5></h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?php if(\think\Request::instance()->action() == 'index'): ?>javascript:void(0);<?php else: ?><?php echo U('Product/index', array('typeid'=>\think\Request::instance()->param('typeid'))); endif; ?>" data-index='1' class="tab <?php if(\think\Request::instance()->action() == 'index'): ?>current<?php endif; ?>"><span>产品列表</span></a></li>
                <li><a href="<?php if(\think\Request::instance()->action() == 'attribute_index'): ?>javascript:void(0);<?php else: ?><?php echo U('Product/attribute_index', array('typeid'=>\think\Request::instance()->param('typeid'))); endif; ?>" data-index='2' class="tab <?php if(\think\Request::instance()->action() == 'attribute_index'): ?>current<?php endif; ?>"><span>参数列表</span></a></li>
            </ul>
        </div>
    </div>
    <div class="flexigrid">
        <div class="mDiv">
            <div class="ftitle">
                <h3><?php echo (isset($arctype_info['typename']) && ($arctype_info['typename'] !== '')?$arctype_info['typename']:''); ?></h3>
                <h5>(共<?php echo $pager->totalRows; ?>条记录)</h5>
            </div>
            <div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div>
            <form class="navbar-form form-inline" action="<?php echo U('Product/attribute_index'); ?>" method="get" onsubmit="layer_loading('正在处理');">
                <?php echo (isset($searchform['hidden']) && ($searchform['hidden'] !== '')?$searchform['hidden']:''); ?>
                <div class="sDiv">
                    <!-- <div class="sDiv2">  
                        <select name="typeid" class="select" style="margin:0px 5px;">
                            <option value="">--全部栏目--</option>
                            <?php echo (isset($select_html) && ($select_html !== '')?$select_html:''); ?>
                        </select>
                    </div> -->
                    <div class="sDiv2">
                        <input type="text" size="30" name="keywords" value="<?php echo \think\Request::instance()->param('keywords'); ?>" class="qsbox" placeholder="名称搜索...">
                        <input type="submit" class="btn" value="搜索">
                    </div>
                    <!-- <div class="sDiv2">
                        <input type="button" class="btn" value="重置" onClick="window.location.href='<?php echo U('Product/attribute_index', array('typeid'=>\think\Request::instance()->param('typeid'))); ?>';">
                    </div> -->
                </div>
            </form>
        </div>
        <div class="hDiv">
            <div class="hDivBox">
                <table cellspacing="0" cellpadding="0" style="width: 100%">
                    <thead>
                    <tr>
                        <?php if($main_lang == $admin_lang): ?>
                        <th class="sign w40" axis="col0">
                            <div class="tc">选择</div>
                        </th>
                        <?php endif; ?>
                        <th abbr="article_title" axis="col3" class="w40">
                            <div class="tc">ID</div>
                        </th>
                        <th abbr="article_title" axis="col3">
                            <div style="padding-left: 10px;" class="tl">参数名称</div>
                        </th>
                        <th abbr="article_time" axis="col6" class="w120">
                            <div class="tc">字段名称</div>
                        </th>
                        <th abbr="article_time" axis="col6" class="w120">
                            <div class="tc">表单类型</div>
                        </th>
                        <th abbr="article_time" axis="col6" class="w120">
                            <div class="tc">所属栏目</div>
                        </th>
                        <th abbr="article_time" axis="col6" class="w100">
                            <div class="tc">更新时间</div>
                        </th>
                        <th axis="col1" class="w120">
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
                                <i class="fa fa-exclamation-circle"></i>没有符合条件的记录
                            </td>
                        </tr>
                    <?php else: if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                        <tr>
                            <?php if($main_lang == $admin_lang): ?>
                            <td class="sign">
                                <div class="tc w40"><input type="checkbox" name="ids[]" value="<?php echo $vo['attr_id']; ?>"></div>
                            </td>
                            <?php endif; ?>
                            <td class="sort">
                                <div class="tc w40">
                                    <?php echo $vo['attr_id']; ?>
                                </div>
                            </td>
                            <td align="left" style="width: 100%">
                                <div class="tl" style="padding-left: 10px;">
                                    <?php if(is_check_access(CONTROLLER_NAME.'@attribute_edit') == '1'): ?>
                                    <a href="<?php echo U('Product/attribute_edit',array('id'=>$vo['attr_id'])); ?>"><?php echo $vo['attr_name']; ?></a>
                                    <?php else: ?>
                                    <?php echo $vo['attr_name']; endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="w120 tc" style="white-space: nowrap;"><?php echo (isset($vo['fieldname']) && ($vo['fieldname'] !== '')?$vo['fieldname']:''); ?></div>
                            </td>
                            <td>
                                <div class="w120 tc" style="white-space: nowrap;">
                                    <?php echo (isset($attrInputTypeArr[$vo['attr_input_type']]) && ($attrInputTypeArr[$vo['attr_input_type']] !== '')?$attrInputTypeArr[$vo['attr_input_type']]:''); ?>
                                </div>
                            </td>
                            <td>
                                <div class="w120 tc" style="white-space: nowrap;"><?php echo (isset($vo['typename']) && ($vo['typename'] !== '')?$vo['typename']:'<i class="red">数据出错！</i>'); ?></div>
                            </td>
                            <td>
                                <div class="w100 tc">
                                    <?php echo date('Y-m-d',$vo['update_time']); ?>
                                </div>
                            </td>
                            <td>
                                <div class="w120 tc">
                                    <?php if(is_check_access(CONTROLLER_NAME.'@attribute_edit') == '1'): ?>
                                    <a href="<?php echo U('Product/attribute_edit',array('id'=>$vo['attr_id'])); ?>" class="btn blue"><i class="fa fa-pencil-square-o"></i>编辑</a>
                                    <?php endif; if(is_check_access(CONTROLLER_NAME.'@attribute_del') == '1'): if($main_lang == $admin_lang): ?>
                                    <a class="btn red"  href="javascript:void(0);" data-url="<?php echo U('Product/attribute_del'); ?>" data-id="<?php echo $vo['attr_id']; ?>" data-deltype="pseudo" onClick="delfun(this);"><i class="fa fa-trash-o"></i>删除</a>
                                    <?php endif; endif; ?>
                                    <!-- <a class="btn blue" href="javascript:void(0);" onclick="copyToClipBoard('<?php echo $vo['fieldname']; ?>', '<?php echo $vo['attr_id']; ?>', <?php echo (isset($vo['attr_input_type']) && ($vo['attr_input_type'] !== '')?$vo['attr_input_type']:'0'); ?>)">标签调用</a> -->
                                </div>
                            </td>
                            <td class="sort">
                                <div class="w60 tc">
                                    <?php if(is_check_access(CONTROLLER_NAME.'@attribute_edit') == '1'): ?>
                                    <input style="text-align: left;" type="text" onchange="changeTableVal('product_attribute','attr_id','<?php echo $vo['attr_id']; ?>','sort_order',this);"  size="4"  value="<?php echo $vo['sort_order']; ?>" />
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
                <?php if($main_lang == $admin_lang): ?>
                <div class="fbutton checkboxall">
                    <input type="checkbox" onclick="javascript:$('input[name*=ids]').prop('checked',this.checked);">
                </div>
                <?php if(is_check_access(CONTROLLER_NAME.'@attribute_del') == '1'): ?>
                <div class="fbutton">
                    <a onclick="batch_del(this, 'ids');" data-url="<?php echo U('Product/attribute_del'); ?>" data-deltype="pseudo">
                        <div class="add" title="批量删除">
                            <span><i class="fa fa-close"></i>批量删除</span>
                        </div>
                    </a>
                </div>
                <?php endif; if(is_check_access(CONTROLLER_NAME.'@attribute_add') == '1'): ?>
                <div class="fbutton">
                    <a href="<?php echo U('Product/attribute_add', array('typeid'=>\think\Request::instance()->param('typeid'))); ?>">
                        <div class="add" title="新增参数">
                            <span class="red"><i class="fa fa-plus"></i>新增参数</span>
                        </div>
                    </a>
                </div>
                <?php endif; endif; ?>
            </div>
            <div style="clear:both"></div>
        </div>
        <!--分页位置-->
        <?php echo $page; ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){

        // 表格行点击选中切换
        $('#flexigrid > table>tbody >tr').click(function(){
            $(this).toggleClass('trSelected');
        });

        // 点击刷新数据
        $('.fa-refresh').click(function(){
            location.href = location.href;
        });
    });

    /**
     * 代码调用js
     * @param id  id
     * @param limit 条数
     */
    function copyToClipBoard(fieldname, attr_id, attr_input_type) {
        var contentdiv = '<div class="dialog_content" style="margin: 0px; padding: 0px;"><dl style="padding:10px 30px;line-height:30px;">';
        var liststr = "eyou:attr name='name_"+attr_id+" /";
        contentdiv += '<dd>list标签/arclist标签/内容页：</dd>';
        contentdiv += '<dd><input type="text" style=" width:400px;margin-bottom:10px;" value="{'+liststr+'}"></dd>';

        var otherstr = "eyou:attr aid='文档ID' name='name_"+attr_id+" /";
        contentdiv += '<dd>其他调法：</dd>';
        contentdiv += '<dd><input type="text" style=" width:400px;margin-bottom:10px;" value="{'+otherstr+'}"></dd>';

        contentdiv += '<dd style="border-top: dotted 1px #E7E7E7; color: #F60;">请将相应标签复制并粘贴到对应模板文件中！</dd></dl></div>'
        layer.open({
            title: '标签调用',
            type: 1,
            skin: 'layui-layer-demo',
            area: ['480px', '330px'], //宽高
            content: contentdiv
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