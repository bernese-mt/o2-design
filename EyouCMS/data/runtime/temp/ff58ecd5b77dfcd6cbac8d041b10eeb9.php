<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:50:"./application/admin/template/field/channel_add.htm";i:1557218871;s:79:"/home/o2design/public_html/EyouCMS/application/admin/template/public/layout.htm";i:1557218894;s:83:"/home/o2design/public_html/EyouCMS/application/admin/template/field/channel_bar.htm";i:1557218876;s:79:"/home/o2design/public_html/EyouCMS/application/admin/template/public/footer.htm";i:1557218894;}*/ ?>
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
            <?php if(in_array((ACTION_NAME), explode(',',"channel_index"))): ?>
            <a class="back" href="<?php echo url('Channeltype/index'); ?>" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <?php else: ?>
            <a class="back" href="javascript:history.back();" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <?php endif; ?>
            <div class="subject">
                <h3>模型管理</h3>
                <h5></h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?php echo url('Channeltype/edit', array('id'=>$channel_id)); ?>" class="tab"><span>编辑模型</span></a></li>
                <li><a href="<?php echo url('Field/channel_index', array('channel_id'=>$channel_id)); ?>" class="tab current"><span>内容字段</span></a></li>
            </ul>
        </div>
    </div>
    <form class="form-horizontal" id="post_form" action="<?php echo url('Field/channel_add'); ?>" method="post">
        <!-- 常规选项 -->
        <div class="ncap-form-default tab_div_1">
            <dl class="row">
                <dt class="tit">
                    <label for="title"><em>*</em>字段标题</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="" name="title" id="title" class="input-txt">
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="name"><em>*</em>字段名称</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="" name="name" id="name" placeholder="只允许字母、数字和下划线的任意组合" class="input-txt" onkeyup="this.value=this.value.replace(/[^0-9a-zA-Z_]/g,'');" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^0-9a-zA-Z_]/g,''));">
                    <p class="notic">保持唯一性，不可与主表、附加表重复</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="dtype"><em>*</em>字段类型</label>
                </dt>
                <dd class="opt">
                    <select name="dtype" id="dtype">
                    <?php if(is_array($fieldtype_list) || $fieldtype_list instanceof \think\Collection || $fieldtype_list instanceof \think\Paginator): $i = 0; $__LIST__ = $fieldtype_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo $vo['name']; ?>" data-ifoption="<?php echo (isset($vo['ifoption']) && ($vo['ifoption'] !== '')?$vo['ifoption']:0); ?>"><?php echo $vo['title']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row" id="dl_dfvalue">
                <dt class="tit">
                    <label id="label_dfvalue">默认值</label>
                </dt>
                <dd class="opt">
                    <textarea rows="5" cols="60" id="dfvalue" name="dfvalue" placeholder="如果定义字段类型为下拉框、单选项、多选项时，此处填写被选择的项目(用“,”分开，如“男,女,人妖”)。" style="height:60px;"></textarea>
                    <span class="err"></span>
                    <p class="notic">如果定义字段类型为下拉框、单选项、多选项时，此处填写被选择的项目(用“,”分开，如“男,女,人妖”)。</p>
                </dd>
            </dl>
            <dl class="row" id="dl_dfvalue_unit">
                <dt class="tit">
                    <label for="dfvalue_unit">数值单位</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="" name="dfvalue_unit" id="dfvalue_unit" placeholder="比如：元、个、件等等" class="input-txt">
                    <p class="notic">比如：元、个、件等等</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>提示文字</label>
                </dt>
                <dd class="opt">          
                    <textarea rows="5" cols="60" id="remark" name="remark" placeholder="问号提示文字" style="height:60px;"></textarea>
                    <span class="err"></span>
                    <p class="notic">问号提示文字</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="title">可见栏目</label>
                </dt>
                <dd class="opt">
                    <select name="typeids[]" id="typeid" style="width: 300px;" size="15" multiple="true">
                        <option value="0" selected="true">—所有栏目可见—</option>
                        <?php echo $select_html; ?>
                    </select>
                    <span class="err"></span>
                    <p class="red">(按 Ctrl 可以进行多选)</p>
                </dd>
            </dl>
<!--             <dl class="row">
                <dt class="tit">
                    <label for="sort_order">排序</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="100" name="sort_order" id="sort_order" class="input-txt">
                    <p class="notic">越小越靠前</p>
                </dd>
            </dl> -->
        </div>
        <!-- 常规选项 -->
        <div class="ncap-form-default">
            <div class="bot">
                <input type="hidden" name="channel_id" id="channel_id" value="<?php echo (isset($channel_id) && ($channel_id !== '')?$channel_id:''); ?>">
                <a href="JavaScript:void(0);" onclick="check_submit();" class="ncap-btn-big ncap-btn-green" id="submitBtn">确认提交</a>
            </div>
        </div> 
    </form>
</div>
<script type="text/javascript">
    $(function(){
        $('#dtype').change(function(){
            dtype_change(this);
        });

        function dtype_change(obj) {
            var dtype = $(obj).val();
            var ifoption = $(obj).find('option:selected').data('ifoption');
            if (0 <= $.inArray(dtype, ['datetime','switch','img','imgs','files'])) {
                $('#dl_dfvalue').hide();
            } else {
                if (1 == ifoption) {
                    $('#label_dfvalue').html('<em>*</em>默认值');
                } else {
                    $('#label_dfvalue').html('默认值');
                }
                $('#dl_dfvalue').show();
            }
            if (0 <= $.inArray(dtype, ['text','int','float','decimal'])) {
                $('#dl_dfvalue_unit').show();
            } else {
                $('#dl_dfvalue_unit').hide();
            }
        }
    });

    function check_submit(){
        if($('input[name="title"]').val() == ''){
            showErrorMsg('字段标题不能为空！');
            $('input[name=title]').focus();
            return false;
        }
        var name = $('input[name="name"]').val();
        var ret1 = /^[_]+$/;
        var ret2 = /^[\w]+$/;
        var ret3 = /^[0-9]+$/;
        if (ret1.test(name) || !ret2.test(name)) {
            showErrorMsg('字段名称格式不正确！');
            $('input[name=name]').focus();
            return false;
        } else if (ret3.test(name)) {
            showErrorMsg('字段名称不能纯数字！');
            $('input[name=name]').focus();
            return false;
        }
        if($('#dtype').val() == ''){
            showErrorMsg('请选择字段类型！');
            $('input[name=dtype]').focus();
            return false;
        } else {
            var ifoption = $('#dtype').find('option:selected').data('ifoption');
            console.log(ifoption)
            if (1 == ifoption) {
                if ($.trim($('#dfvalue').val()) == '') {
                    showErrorMsg('默认值不能为空！');
                    $('#dfvalue').focus();
                    return false;
                }
            }
        }
        if (0 >= parseInt($('#typeid').find('option:selected').length)) {
            showErrorMsg('请选择可见栏目！');
            $('#typeid').focus();
            return false;
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