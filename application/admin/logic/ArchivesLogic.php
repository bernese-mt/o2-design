<?php
/**
 * 易優CMS
 * ============================================================================
 * 版權所有 2016-2028 海南贊贊網路科技有限公司，並保留所有權利。
 * 網站地址: http://www.eyoucms.com
 * ----------------------------------------------------------------------------
 * 如果商業用途務必到官方購買正版授權, 以免引起不必要的法律糾紛.
 * ============================================================================
 * Author: 小虎哥 <1105415366@qq.com>
 * Date: 2018-4-3
 */

namespace app\admin\logic;

use think\Model;
use think\Db;
/**
 * 文件邏輯定義
 * Class CatsLogic
 * @package admin\Logic
 */
load_trait('controller/Jump');
class ArchivesLogic extends Model
{
    use \traits\controller\Jump;
    
    private $admin_lang = 'cn';

    /**
     * 解構函式
     */
    function  __construct() {
        $this->admin_lang = get_admin_lang();
    }

    /**
     * 刪除文件
     */
    public function del($del_id = array())
    {
        if (empty($del_id)) {
            $del_id = input('del_id/a');
        }

        $id_arr = eyIntval($del_id);
        if(!empty($id_arr)){
            /*分離並組合相同模型下的文件ID*/
            $row = db('archives')
                ->alias('a')
                ->field('a.channel,a.aid,b.ctl_name')
                ->join('__CHANNELTYPE__ b', 'a.channel = b.id', 'LEFT')
                ->where([
                    'a.aid' => ['IN', $id_arr],
                    'a.lang'    => $this->admin_lang,
                ])
                ->select();
            $data = array();
            foreach ($row as $key => $val) {
                $data[$val['channel']]['aid'][] = $val['aid'];
                $data[$val['channel']]['ctl_name'] = $val['ctl_name'];
            }
            /*--end*/

            $info['is_del']     = '1'; // 偽刪除狀態
            $info['update_time']= getTime(); // 更新修改時間
            $info['del_method'] = '1'; // 恢復刪除方式為預設

            $err = 0;
            foreach ($data as $key => $val) {
                // $r = M('archives')->where('aid','IN',$val['aid'])->delete();
                $r = M('archives')->where('aid','IN',$val['aid'])->update($info);
                if ($r) {
                    // model($val['ctl_name'])->afterDel($val['aid']);
                    adminLog('刪除文件-id：'.implode(',', $val['aid']));
                } else {
                    $err++;
                }
            }

            if (0 == $err) {
                $this->success('刪除成功');
            } else if ($err < count($data)) {
                $this->success('刪除部分成功');
            } else {
                $this->error('刪除失敗');
            }
        }else{
            $this->error('參數有誤');
        }
    }

    /**
     * 獲取文件模板檔案列表
     */
    public function getTemplateList($nid = 'article')
    {   
        $planPath = 'template/pc';
        $dirRes   = opendir($planPath);
        $view_suffix = config('template.view_suffix');

        /*模板PC目錄檔案列表*/
        $templateArr = array();
        while($filename = readdir($dirRes))
        {
            if (in_array($filename, array('.','..'))) {
                continue;
            }
            array_push($templateArr, $filename);
        }
        /*--end*/

        /*多語言全部標識*/
        $markArr = Db::name('language_mark')->column('mark');
        /*--end*/

        $templateList = array();
        foreach ($templateArr as $k2 => $v2) {
            $v2 = iconv('GB2312', 'UTF-8', $v2);
            preg_match('/^(view)_'.$nid.'(_(.*))?(_'.$this->admin_lang.')?\.'.$view_suffix.'/i', $v2, $matches1);
            $langtpl = preg_replace('/\.'.$view_suffix.'$/i', "_{$this->admin_lang}.{$view_suffix}", $v2);
            if (file_exists(realpath($planPath.DS.$langtpl))) {
                continue;
            } else if (preg_match('/^(.*)_([a-zA-z]{2,2})\.'.$view_suffix.'$/i',$v2,$matches2)) {
                if (in_array($matches2[2], $markArr) && $matches2[2] != $this->admin_lang) {
                    continue;
                }
            }

            if (!empty($matches1)) {
                if ('view' == $matches1[1]) {
                    array_push($templateList, $v2);
                }
            }
        }

        return $templateList;
    }
}
