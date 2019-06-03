<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:49:"./application/admin/template/ad_position\edit.htm";i:1559529923;s:66:"D:\site\ada\o2-design\application\admin\template\public\layout.htm";i:1559529926;s:66:"D:\site\ada\o2-design\application\admin\template\public\footer.htm";i:1559529926;}*/ ?>
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
<body class="ad_postion" style="overflow-y: scroll;">
<div id="toolTipLayer" style="position: absolute; z-index: 9999; display: none; visibility: visible; left: 95px; top: 573px;"></div>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title"><a class="back" href="javascript:history.back();" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>編輯廣告</h3>
                <h5></h5>
            </div>
        </div>
    </div>
    <form class="form-horizontal" id="post_form" action="<?php echo url('AdPosition/edit'); ?>" method="post">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="title"><em>*</em>廣告名稱</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="title" value="<?php echo $field['title']; ?>" id="title" class="input-txt">
                    <span class="err"></span>
                    <p class="notic">保持唯一性，不可重複</p>
                </dd>
            </dl>
           
            <dl class="row" style="z-index: 2">
                <dt class="tit">
                    <label>廣告內容</label>
                </dt>
                <dd class="opt">          
                    <div class="tab-pane" id="tab_imgupload">
                        <?php if($main_lang == $admin_lang): ?>
                        <a href="javascript:void(0);" onClick="GetUploadify(30,'','allimg','imgupload_call_back');" class="imgupload">
                            <i class="fa fa-photo"></i>上傳圖片
                        </a>
                        <?php endif; ?>
                        <input type="hidden" id="ImagesId">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <td class="sort-list">
                                    <?php if(is_array($ad_data) || $ad_data instanceof \think\Collection || $ad_data instanceof \think\Paginator): $k = 0; $__LIST__ = $ad_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                                        <div class="images_upload">
                                            <div class="ic">
                                                <div class='upimg' title="拖動修改排序" >
                                                    <div class='icaction' style="display: none" >
                                                        <span class="load_images" onclick="Images('<?php echo $vo['litpic']; ?>');">
                                                            <a href="javascript:void(0);" style="color:white">
                                                                <i class='fa fa-search-plus'></i>檢視大圖
                                                            </a>
                                                        </span>
                                                        <span class="load_images mr5" onclick="LoadImagesId('<?php echo $vo['id']; ?>');">
                                                            <i class='fa fa-photo'></i>更換圖片
                                                        </span>
                                                    </div>
                                                    <div class='cover-bg' style="display: none" ></div>
                                                    <img id="<?php echo $vo['id']; ?>_Id" src="<?php echo $vo['litpic']; ?>"/>
                                                </div>

                                                <input type="hidden" name="img_id[]" value="<?php echo $vo['id']; ?>"/>
                                                <span class="span_input">
                                                    <input type="hidden" id="<?php echo $vo['id']; ?>_Litpic" name="img_litpic[]" value="<?php echo $vo['litpic']; ?>"/>
                                                </span>
                                                <textarea name="img_title[]" placeholder="請輸入標題..."><?php echo $vo['title']; ?></textarea>
                                                <textarea name="img_links[]" placeholder="請輸入鏈接網址..."><?php echo $vo['links']; ?></textarea>

                                                <div class="operation">
                                                    <a href="javascript:void(0);">
                                                        <label>
                                                            <input type="checkbox" <?php if($vo['target'] == '1'): ?> checked="checked" <?php endif; ?> title="在新視窗打開" onclick="CheckedTarget(this)"/>新視窗
                                                            <input type="hidden" name="img_target[]" value="<?php echo $vo['target']; ?>">
                                                        </label>
                                                    </a>
                                                    <a href="javascript:void(0);" onclick="copyToClipBoard(<?php echo $vo['id']; ?>)">
                                                        <i class="fa fa-file-code-o"></i>標籤呼叫
                                                    </a>
                                                    <?php if($main_lang == $admin_lang): ?>
                                                        <a href="javascript:void(0)" onclick="ClearPicArr(this,'<?php echo $vo['litpic']; ?>','<?php echo $vo['id']; ?>')">
                                                            <i class="fa fa-remove"></i>刪除
                                                        </a>
                                                    <?php endif; ?>
                                                </div>

                                            </div>
                                        </div>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                    <div class="images_upload">
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- 上傳圖片顯示的樣板 start -->
                    <div class="images_upload_tpl none">
                        <div class="images_upload ic">
                            <div class="ic">
                                <div class='upimg' title="拖動修改排序" >
                                    <div class='icaction' style="display: none">
                                        <span class="load_images" onclick="">
                                            <a href="javascript:void(0);" style="color: white">
                                                <i class='fa fa-search-plus'></i>檢視大圖
                                            </a>
                                        </span>
                                    </div>
                                    <div class='cover-bg' style="display: none"></div>
                                    <img src="/o2-design/public/static/admin/images/add-button.jpg"/>
                                </div>
                                <input type="hidden"/>
                                <span class="span_input">
                                    <input type="hidden"/>
                                </span>
                                <textarea placeholder="請輸入標題..."></textarea>
                                <textarea placeholder="請輸入鏈接網址..."></textarea>
                                <div class="operation">
                                    <a href="javascript:void(0)">&nbsp;&nbsp;</a>
                                    <a href="javascript:void(0)">&nbsp;&nbsp;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 上傳圖片顯示的樣板 end -->
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>廣告備註</label>
                </dt>
                <dd class="opt">          
                    <textarea rows="5" cols="60" id="intro" name="intro" style="height:60px;"><?php echo $field['intro']; ?></textarea>
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <div class="bot">
                <input type="hidden" name="id" value="<?php echo $field['id']; ?>">
                <a href="JavaScript:void(0);" onclick="checkForm();" class="ncap-btn-big ncap-btn-green" id="submitBtn">確認提交</a>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    // 勾選新視窗打開鏈接
    function CheckedTarget(t){
        if ($(t).is(':checked')) {
            $(t).parent().find('input[name="img_target[]"]').val(1);
        }else{
            $(t).parent().find('input[name="img_target[]"]').val(0);
        }
    }

    // 滑鼠事件，載入檢視大圖和更新圖片
    $(document).ready(function(){
       $(".upimg").live('mouseover', function(){
            $(this).find('div.icaction').show();
            $(this).find('div.cover-bg').show();
       }).live('mouseout', function(){
            $(this).find('div.icaction').hide();
            $(this).find('div.cover-bg').hide();
       });
    });

    // 檢視大圖
    function Images(links){
        var max_width = 900;
        var max_height = 600;
        var img = "<img src='"+links+"'/>";
        $(img).load(function() {
            width  = this.width;
            height = this.height;
            if (width > height) {
                if (width > max_width) {
                    width = max_width;
                }
                width += 'px';
            } else {
                width = 'auto';
            }
            if (width < height) {
                if (height > max_height) {
                    height = max_height;
                }
                height += 'px';
            } else {
                height = 'auto';
            }

            var links_img = "<img style='width:"+width+";height:"+height+";' src="+links+">";
            
            layer.open({
                type: 1,
                title: false,
                closeBtn: true,
                area: [width, height],
                skin: 'layui-layer-nobg', //沒有背景色
                content: links_img
            });
        });
    }

    // 判斷輸入框是否為空
    function checkForm(){
        if($('input[name=title]').val() == ''){
            layer.msg('廣告位名稱不能為空！', {icon: 2,time: 1000});
            return false;
        }
        layer_loading('正在處理');
        $('#post_form').submit();
    }

    // 獲取點選更新圖片的ID並載入隱藏域
    function LoadImagesId(id){
        // 載入ID到隱藏域
        $('#ImagesId').val(id);
        // 呼叫圖片上傳JS
        GetUploadify(1,'','allimg','UpdataImages');
    }

    // 更新圖片
    function UpdataImages(path){
        // 獲取點選的ID
        var id = $('#ImagesId').val();
        // 載入圖片到顯示層
        $("#"+id+"_Id").attr('src', path);
        // 載入圖片到提交的隱藏域
        $("#"+id+"_Litpic").val(path);
    }

    // 上傳圖集相簿回撥函式
    function imgupload_call_back(paths){
        var last_div = $(".images_upload_tpl").html();
        var inputs   = $('.span_input input');
        // 圖片數據處理
        for (var i=0;i<paths.length ;i++){
            $(".images_upload:eq(0)").before(last_div);  // 插入一個 新圖片
            // 修改他的鏈接地址
            $(".images_upload:eq(0)").find('span:eq(0)').attr('onclick',"Images('"+paths[i]+"');");
            // 修改他的圖片路徑
            $(".images_upload:eq(0)").find('img').attr('src',paths[i]);
            // 處理圖片路徑及隱藏域
            if (inputs.length > '0') {
                // 修改隱藏域
                $(".images_upload:eq(0)").find('input:eq(0)').attr('name','img_id[]').attr('value','');
                $(".span_input:eq(0)").find('input:eq(0)').attr('name','img_litpic[]').attr('value',paths[i]);
                // 處理標題
                $(".images_upload:eq(0)").find('textarea:eq(0)').attr('name','img_title[]');
                // 處理跳轉鏈接
                $(".images_upload:eq(0)").find('textarea:eq(1)').attr('name','img_links[]');
                // 提交新視窗選項
                $(".images_upload:eq(0)").find('div.operation a:eq(0)').html("<label><input type='checkbox' title='在新視窗打開' onclick='CheckedTarget(this)'/>新視窗<input type='hidden' name='img_target[]' value='0'></label>");
                // 刪除按鈕
                $(".images_upload:eq(0)").find('div.operation a:eq(1)').attr('onclick',"ClearPicArr(this,'"+paths[i]+"')").html("<i class='fa fa-remove'></i>刪除");
            }
        }
    }

    // 上傳之後刪除組圖input
    function ClearPicArr(obj,path,id='')
    {
        // 刪除數據庫記錄
        $.ajax({
            type:'POST',
            url:"<?php echo url('AdPosition/del_imgupload'); ?>",
            data:{del_id:id},
            success:function(){
                $(obj).parent().parent().parent().remove(); // 刪除完伺服器的, 再刪除 html上的圖片
                $.ajax({
                    type:'POST',
                    url:"<?php echo url('Uploadify/delupload'); ?>",
                    data:{action:"del", filename:path},
                    success:function(){}
                }); 
            }
        });
    }

    // 程式碼呼叫js
    function copyToClipBoard(id) {
        var adstr = "{eyou:ad aid='" + id + "'}\r\n   <a href='{$"+"field.links}' {$"+"field.target}><img src='{$"+"field.litpic}' alt='{$"+"field.title}' /></a>\r\n{/eyou:ad";
        var contentdiv = '<div class="dialog_content" style="margin: 0px; padding: 0px;"><dl style="padding:10px 30px;line-height:30px"><dd>標籤 ad 呼叫:</dd>'
        contentdiv += '<textarea rows="4" cols="60" style="width:400px;height:80px;">' + adstr + '}</textarea>'
        contentdiv += '<dd style="border-top: dotted 1px #E7E7E7; color: #F60;">請將對應標籤程式碼複製並貼上到對應模板檔案中！</dd></dl></div>'
        layer.open({
            title: '程式碼呼叫',
            type: 1,
            skin: 'layui-layer-demo',
            area: ['480px', '240px'], //寬高
            content: contentdiv
        });
    }

    // 圖集相簿的拖動排序相關 js
    $( ".sort-list" ).sortable({
        start: function( event, ui) {
        
        }
        ,stop: function( event, ui ) {

        }
    });
    $( ".sort-list" ).disableSelection();
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