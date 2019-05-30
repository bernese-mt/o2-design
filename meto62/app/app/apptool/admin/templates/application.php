<!--<?php
defined('IN_MET') or exit('No permission');
require $this->template('ui/head');
echo <<<EOT
-->
<form method="POST" class="ui-from" name="myform" action="{$_M['url']['own_form']}a=doaddapp" target="_self">
	<div class="v52fmbx">
            <h3 class="v52fmbx_hr">注意事项：</h3>
            <dl>
                <dd class="ftype_description">
                    1、应用文件需要放在网站 根目录/APP/APP下 【注意】两个APP;<BR />
                    2、根据应用安装文件的不同，会出现不同的安装页面，均属于正常;<BR />
                    3、安装执行后，若应用未安装成功，请检测安装文件是否有错误。<BR />
					4、只可以安装自行开发的应用，官方应用市场下载的应用无法二次安装。
                </dd>
            </dl>
            <dl>
                <dt>应用文件夹</dt>
                <dd class="ftype_input">
                    <div class="fbox">
                        <input type="text" name="appfile" value="{$appfile}" data-ajaxcheck-url="{$ajaxurl}&a=doappfile">
                    </div>
                    <span class="tips">请保证应用文件位置正确，和安装文件代码无错误。</span>
                </dd>
            </dl>
             {$url}
            <dl class="noborder">
                    <dt> </dt>
                    <dd>
                        <input type="submit" name="submit" value="安装" class="submit">
                    </dd>
            </dl>
            <dl>
                <dd class="ftype_description ftype_a" style="background:none;">
                    <a href="http://api.metinfo.wang" target="_blank">MetTooL 功能介绍</a>
                    <i style="font-style:normal; padding:0px 8px; color:#eee;">|</i>
                    <a href="http://api.metinfo.wang" target="_blank">MetTooL 更新说明</a>
                    <i style="font-style:normal; padding:0px 8px; color:#eee;">|</i>
                    <span style="color: #aaa; font-style: italic;">反馈QQ群：1452532</span>
                    <i style="font-style:normal; padding:0px 8px; color:#eee;">|</i>
                    <a target="_blank" href="http://shang.qq.com/wpa/qunwpa?idkey=e6bcac0a76480ffd47e41ff0fb82473659f40bde92c8116d3b7214b5343f388c"><img border="0" src="http://pub.idqqimg.com/wpa/images/group.png" alt="Metinfo( 米拓 )应用交流" title="Metinfo( 米拓 )应用交流"></a>

                </dd>
            </dl>
	</div>
</form>
<!--
EOT;
require $this->template('ui/foot');
?>