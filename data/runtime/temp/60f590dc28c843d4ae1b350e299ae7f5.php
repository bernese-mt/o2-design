<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:46:"./application/admin/template/arctype\index.htm";i:1559290247;s:66:"D:\site\ada\o2-design\application\admin\template\public\layout.htm";i:1558672259;s:66:"D:\site\ada\o2-design\application\admin\template\public\footer.htm";i:1558667897;}*/ ?>
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
<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; overflow-y: scroll;">
<style type="text/css">
  .tb_child {
    background:#FFFFDF;
  }
</style>
<div class="page">
  <form method="post">
    <div class="flexigrid">
      <div class="mDiv">
        <div class="ftitle">
          <h3>欄目列表</h3>
          <h5>(共<?php echo count($arctype_list); ?>條記錄)</h5>
        </div>
        <div title="刷新數據" class="pReload"><i class="fa fa-refresh"></i></div>
        <?php if($main_lang == $admin_lang): ?>
        <div class="fbutton">
              <a href="<?php if(in_array(\think\Request::instance()->action(), array('add','edit'))): ?>javascript:void(0);<?php else: ?><?php echo url("Arctype/add"); endif; ?>">
                  <div class="add">
                      <span><i class="fa fa-plus"></i>增加頂級欄目</span>
                  </div>
              </a>
        </div>
        <?php endif; ?>
      </div>
      <div class="hDiv">
        <div class="hDivBox">
          <table cellpadding="0" cellspacing="0" style="width: 100%">
            <thead>
              <tr>
                <th axis="col3" class="w60">
                  <div class="sundefined tc">ID</div>
                </th>
                <th axis="col3" class="">
                  <div class="sundefined" style="padding-left: 15px">
                    <img src="/o2-design/public/static/admin/images/tv-expandable.gif" id="all_treeclicked" title="展開所有子欄目" style="float: none;" data-status="close" onClick="treeClicked(this,'all',0);">
                    類別名稱
                  </div>
                </th>
                <th axis="col2" class="w100">
                  <div class="tc">所屬模型</div>
                </th>
                <th axis="col2" class="w60">
                  <div>隐藏</div>
                </th>
                <th axis="col1" class="w300">
                    <div class="tl" style="text-indent: 6px">操作</div>
                </th>
                <th axis="col2" class="w60">
                  <div class="tc">排序</div>
                </th>
              </tr>
            </thead>
          </table>
        </div>
      </div>

      <div id="flexigrid" class="bDiv" style="height: auto;">
        <?php if(empty($arctype_list) || (($arctype_list instanceof \think\Collection || $arctype_list instanceof \think\Paginator ) && $arctype_list->isEmpty())): ?>
        <div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
            <table>
                <tbody>
                    <tr>
                        <td class="no-data" align="center" axis="col0" colspan="50">
                            <i class="fa fa-exclamation-circle"></i>沒有符合條件的記錄
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="iDiv" style="display: none;"></div>
        <?php else: ?>
        <table class="flex-table autoht" cellpadding="0" cellspacing="0" border="0" id="arctype_table" style="width: 100%">
          <tbody id="treet1">
          <?php if(is_array($arctype_list) || $arctype_list instanceof \think\Collection || $arctype_list instanceof \think\Paginator): if( count($arctype_list)==0 ) : echo "" ;else: foreach($arctype_list as $k=>$vo): ?>
            <tr nctype="0" <?php if($vo['parent_id'] > 0): ?> style="display:none;"<?php endif; ?> class="parent_id_<?php echo $vo['parent_id']; ?>" data-level="<?php echo $vo['level']; ?>" data-id="<?php echo $vo['id']; ?>">
              <td class="name">
                <div class="w60 tc">
                  <?php echo $vo['id']; ?>
                </div>
              </td>
              <td class="typename" style="width: 100%">
                <div>
                  <?php if($vo['level'] > '0'): 
                  if (1 == $vo['level']) {
                    echo '<span class="w40x"></span>';
                  } elseif (2 == $vo['level']) {
                    echo '<span class="w40x w40xc"></span>';
                  }
                   endif; if($vo['has_children'] > '0'): ?>
                  <img src="/o2-design/public/static/admin/images/tv-expandable.gif" style="float: none;" fieldid="2" status="open" nc_type="flex" onClick="treeClicked(this,<?php echo $vo['id']; ?>,0);" class="has_children">
                  <?php else: ?>
                  <img src="/o2-design/public/static/admin/images/tv-collapsable-last.gif" style="float: none;" fieldid="2" status="open" nc_type="flex">
                  <?php endif; if($vo['current_channel'] == 6): ?>
                  <a href="<?php echo url('Arctype/single_edit',array('typeid'=>$vo['id'])); ?>"><?php echo $vo['typename']; ?></a>
                  <?php else: ?>
                  <a href="<?php echo url($channeltype_list[$vo['current_channel']]['ctl_name'].'/index',array('typeid'=>$vo['id'], 'tab'=>3)); ?>"><?php echo $vo['typename']; ?></a>
                  <i class="arctotal">（文檔：<?php echo get_total_arc($vo['id']); ?>條）</i>
                  <?php endif; ?>
                </div>
              </td>
              <td class="sort">
                <div class="w100 tc">
                  <?php echo (isset($channeltype_list[$vo['current_channel']]['title']) && ($channeltype_list[$vo['current_channel']]['title'] !== '')?$channeltype_list[$vo['current_channel']]['title']:''); ?>
                </div>
              </td>

              <td align="center" class="">
                  <div class="w60">
                  <?php if($vo['is_hidden'] == 1): ?>
                      <span class="yes" <?php if(is_check_access(CONTROLLER_NAME.'@edit') == '1'): ?>onClick="changeTableVal('arctype','id','<?php echo $vo['id']; ?>','is_hidden',this);"<?php endif; ?> ><i class="fa fa-check-circle"></i>是</span>
                  <?php else: ?>
                      <span class="no" <?php if(is_check_access(CONTROLLER_NAME.'@edit') == '1'): ?>onClick="changeTableVal('arctype','id','<?php echo $vo['id']; ?>','is_hidden',this);"<?php endif; ?> ><i class="fa fa-ban"></i>否</span>
                  <?php endif; ?>
                  </div>
              </td>
              <td>
                  <div class="w300 tl pb0">
                      <?php if(is_check_access('Archives@index') == '1'): if($vo['current_channel'] == 6): ?>
                        <a href="<?php echo url('Arctype/single_edit',array('typeid'=>$vo['id'])); ?>" class="btn blue"><i class="fa fa-pencil-square-o"></i>内容</a>
                        <?php else: ?>
                        <a href="<?php echo url($channeltype_list[$vo['current_channel']]['ctl_name'].'/index',array('typeid'=>$vo['id'], 'tab'=>3)); ?>" class="btn blue"><i class="fa fa-pencil-square-o"></i>内容</a>
                        <?php endif; endif; ?>
                      <a href="<?php echo url('Arctype/edit',array('id'=>$vo['id'])); ?>" class="btn blue"><i class="fa fa-pencil-square-o"></i>編輯</a>
                      <?php if(is_check_access('Arctype@add') == '1'): if($main_lang == $admin_lang): if($vo['grade'] < ($arctype_max_level - 1)): ?>
                          <a href="<?php echo url('Arctype/add',array('parent_id'=>$vo['id'])); ?>" class="btn blue"><i class="fa fa-pencil-square-o"></i>增加子欄目</a>
                        <?php else: ?>
                          <a class="btn blue" title="不支持增加四級欄目"><i class="fa fa-pencil-square-o"></i>不支持增加</a>
                        <?php endif; endif; endif; if(is_check_access('Arctype@pseudo_del') == '1'): if($main_lang == $admin_lang): ?>
                      <a class="btn red"  href="javascript:void(0);" data-url="<?php echo url('Arctype/pseudo_del'); ?>" data-id="<?php echo $vo['id']; ?>" data-typename="<?php echo $vo['typename']; ?>" data-deltype="pseudo" onClick="delfun(this);"><i class="fa fa-trash-o"></i>删除</a>
                      <?php endif; endif; ?>
                      <a href="<?php echo get_typeurl($vo); ?>" class="btn blue" target="_blank"><i class="fa fa-pencil-square-o"></i>浏覽</a>
                  </div>
              </td>
              <td class="sort">
                <div class="w60 tc pb0">
                  <?php if(is_check_access('Arctype@edit') == '1'): ?>
                  <input type="text" onChange="changeTableVal('arctype','id','<?php echo $vo['id']; ?>','sort_order',this);" size="4" value="<?php echo $vo['sort_order']; ?>" class="tc" />
                  <?php else: ?>
                  <?php echo $vo['sort_order']; endif; ?>
                </div>
              </td>
              
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>                
          </tbody>
        </table>
        <?php endif; ?>
      </div> 
    </div>
  </form>
  <script type="text/javascript">
    $(document).ready(function(){
        // 表格行點擊選中切換
        $('#flexigrid > table>tbody >tr').click(function(){
            $(this).toggleClass('trSelected');
        });

        // 點擊刷新數據
        $('.fa-refresh').click(function(){
            location.href = location.href;
        });

        // treeClicked($('#all_treeclicked'), 'all', 1);
    });

     // 點擊展開 收縮節點
    function treeClicked(obj,id,reload){
        if (id == 'all') {
          if (1 == reload) {
            var status = getCookie('admin-treeClicked');
            if (!status) {
              status = $(obj).attr('data-status');
            }
          } else {
            var status = $(obj).attr('data-status');
          }
          if (status == 'close') {
            $('tr[class^=parent_id_]').show().find('img').attr('src', '/o2-design/public/static/admin/images/tv-collapsable-last.gif');
            $(obj).attr('data-status', 'open').attr('title','關閉所有子欄目').attr('src','/o2-design/public/static/admin/images/tv-collapsable-last.gif');
          } else {
            $('tr[data-level=0]').find('img.has_children').attr('src', '/public/static/admin/images/tv-collapsable-last.gif').trigger('click');
            $('tr[class^=parent_id_]').removeClass('trSelected');
            $(obj).attr('data-status', 'close').attr('title','展開所有子欄目').attr('src','/o2-design/public/static/admin/images/tv-expandable.gif');
          }
          setCookies('admin-treeClicked', status);
          return false;
        }

         var src = $(obj).attr('src');
         if(src == '/o2-design/public/static/admin/images/tv-expandable.gif')
         {
             // $("#treet1 tr").removeClass('tb_child');
             // $(".parent_id_"+id).addClass('tb_child');
             $(".parent_id_"+id).show();
             $(obj).attr('src','/o2-design/public/static/admin/images/tv-collapsable-last.gif');
             var status = 'close';
         }else{
             $(obj).attr('src','/o2-design/public/static/admin/images/tv-expandable.gif');     
             var status = 'open';      
             
             // 如果是點擊減号, 遍曆循環他下面的所有都關閉
             var tbl = document.getElementById("arctype_table");
             cur_tr = obj.parentNode.parentNode.parentNode;
             var fnd = false;
              for (i = 0; i < tbl.rows.length; i++)
              {
                  var row = tbl.rows[i];
                  
                  if (row == cur_tr)
                  {
                      fnd = true;         
                  }
                  else
                  {
                      if (fnd == true)
                      {
                         
                          var level = parseInt($(row).data('level'));
                          var cur_level = $(cur_tr).data('level');
                         
                          if (level > cur_level)
                          {
                              $(row).hide();        
                              $(row).find('img.has_children').attr('src','/o2-design/public/static/admin/images/tv-expandable.gif');
                          }
                          else
                          {
                              fnd = false;
                              break;
                          }
                      }
                  }
              }          
         }   
         setCookies('admin-treeClicked', status);    
    }
  
    function delfun(obj){
        var title = $(obj).attr('data-typename');
        layer.confirm('<font color="#ff0000">如有子欄目及文檔将一起清空</font>，确認删除到回收站？', {
            title: false,
            btn: ['确定','取消'] //按鈕
        }, function(){
            layer_loading('正在處理');
            // 确定
            $.ajax({
                type : 'post',
                url : $(obj).attr('data-url'),
                data : {del_id:$(obj).attr('data-id')},
                dataType : 'json',
                success : function(data){
                    layer.closeAll();
                    if(data.code == 1){
                        layer.msg(data.msg, {icon: 1});
                        window.location.reload();
                        // $('tr[data-id="'+$(obj).attr('data-id')+'"]').remove();
                    }else{
                        layer.alert(data.msg, {icon: 2, title:false});  //alert(data);
                    }
                }
            })
        }, function(index){
            layer.close(index);
        });
        return false;
    }  
  </script>
</div>

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