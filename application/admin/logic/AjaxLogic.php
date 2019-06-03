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
 * 邏輯定義
 * Class CatsLogic
 * @package admin\Logic
 */
class AjaxLogic extends Model
{
    private $request = null;

    /**
     * 解構函式
     */
    function  __construct() {
        $this->request = request();
    }

    /**
     * 進入登錄頁面需要非同步處理的業務
     */
    public function login_handle()
    {
        $this->saveBaseFile(); // 儲存後臺入口檔案路徑，比如：/login.php
        $this->clear_session_file(); // 清理過期的data/session檔案
    }

    /**
     * 進入歡迎頁面需要非同步處理的業務
     */
    public function welcome_handle()
    {
        $this->saveBaseFile(); // 儲存後臺入口檔案路徑，比如：/login.php
        $this->renameInstall(); // 重新命名安裝目錄，提高網站安全性
        $this->del_adminlog(); // 只保留最近三個月的操作日誌
        $this->syn_smtp_config(); // 同步外掛【郵箱發送】的配置資訊到內建表中
        tpversion(); // 統計裝載量，請勿刪除，謝謝支援！
    }
    
    /**
     * 只保留最近三個月的操作日誌
     */
    private function del_adminlog()
    {
        $mtime = strtotime("-3 month");
        Db::name('admin_log')->where([
            'log_time'  => ['lt', $mtime],
            ])->delete();
    }

    /**
     * 重新命名安裝目錄，提高網站安全性
     * 在 Admin@login 和 Index@index 操作下
     */
    private function renameInstall()
    {
        $install_path = ROOT_PATH.'install';
        if (is_dir($install_path) && file_exists($install_path)) {
            $install_time = DEFAULT_INSTALL_DATE;
            $constsant_path = APP_PATH.'admin/conf/constant.php';
            if (file_exists($constsant_path)) {
                require_once($constsant_path);
                defined('INSTALL_DATE') && $install_time = INSTALL_DATE;
            }
            $new_path = ROOT_PATH.'install_'.$install_time;
            @rename($install_path, $new_path);
        } else { // 修補v1.1.6版本刪除的安裝檔案 install.lock
            if(!empty($_SESSION['isset_install_lock']))
                return true;
            $_SESSION['isset_install_lock'] = 1;

            $install_time = DEFAULT_INSTALL_DATE;
            $constsant_path = APP_PATH.'admin/conf/constant.php';
            if (file_exists($constsant_path)) {
                require_once($constsant_path);
                defined('INSTALL_DATE') && $install_time = INSTALL_DATE;
            }
            $filename = ROOT_PATH.'install_'.$install_time.DS.'install.lock';
            if (!file_exists($filename)) {
                @file_put_contents($filename, '');
            }
        }
    }

    /**
     * 儲存後臺入口檔案路徑，比如：/login.php
     * 在 Admin@login 和 Index@index 操作下
     */
    private function saveBaseFile()
    {
        $baseFile = $this->request->baseFile();
        /*多語言*/
        if (is_language()) {
            $langRow = \think\Db::name('language')->field('mark')->order('id asc')->select();
            foreach ($langRow as $key => $val) {
                tpCache('web', ['web_adminbasefile'=>$baseFile], $val['mark']);
            }
        } else { // 單語言
            tpCache('web', ['web_adminbasefile'=>$baseFile]);
        }
        /*--end*/
    }

    /**
     * 自動糾正蜘蛛抓取檔案rotots.txt
     */
    public function update_robots()
    {
        $filename = 'robots.txt';
        if (file_exists($filename) && is_file($filename)) {
            // 系統設定的抓取規則
            $validList = [
                'disallow:/extend/',
                'disallow:/install/',
                'disallow:/template/',
                'disallow:/core/',
                'disallow:/vendor/',
            ];
            // 系統移除的抓取規則
            $removeList = [
                'disallow:/*.php*',
                'disallow:/*.js*',
                'disallow:/*.css*',
                'disallow:/data/',
                'disallow:/weapp/',
                'disallow:/public/',
                'disallow:/adm*',
                'sitemap:/sitemap.xml',
            ];
            $robots = @file_get_contents(ROOT_PATH . $filename);
            $arr = explode(PHP_EOL, $robots);
            foreach ($arr as $key => $val) {
                $is_unset = false;
                $val = trim($val);
                $str = str_replace(' ', '', strtolower($val));
                if ($str == 'disallow:/appli*') {
                    $arr[$key] = 'Disallow: /application';
                    continue;
                // } else if (stristr(strtolower($val), 'sitemap.xml') && !stristr($val, $this->request->domain().ROOT_DIR.'/sitemap.xml')) {
                //     $arr[$key] = preg_replace('#Sitemap:(.*)?(/)?sitemap.xml#i', 'Sitemap: '.$this->request->domain().ROOT_DIR.'/sitemap.xml', $val);
                //     continue;
                } else if (preg_match('#disallow:/install#i', $str)) {
                    $arr[$key] = 'Disallow: /install_*';
                    continue;
                } else if (in_array($str, $removeList)) {
                    $is_unset = true;
                }

                // 移除系統指定的抓取規則
                if (true === $is_unset) {
                    unset($arr[$key]);
                    continue;
                }

                // 系統之前設定的抓取規則，將移除尾部的斜桿
                if (in_array($str, $validList)) {
                    $val = trim($val, '/');
                }

                $arr[$key] = $val;
            }
            if (!empty($arr)) {
                $robotsStr = implode(PHP_EOL, $arr);
                is_writable($filename) && @file_put_contents($filename, $robotsStr);
            }
        }
    }

    /**
     * 清理過期的data/session檔案
     */
    private function clear_session_file()
    {
        $path = \think\Config::get('session.path');
        if (!empty($path) && file_exists($path)) {
            $files = glob($path.'/sess_*');
            foreach ($files as $key => $file) {
                $filemtime = filemtime($file);
                if (getTime() - intval($filemtime) > config('login_expire')) {
                    @unlink($file);
                }
            }
        }
    }

    /**
     * 同步外掛【郵箱發送】的配置資訊到內建表中 -- 相容1.3.0之前版本
     */
    private function syn_smtp_config()
    {
        $smtp_syn_weapp = tpCache('smtp.smtp_syn_weapp'); // 是否同步外掛【郵箱發送】的配置
        if (empty($smtp_syn_weapp)) {

            /*同步之前安裝郵箱外掛的配置資訊*/
            $data = \think\Db::name('weapp')->where('code','Smtpmail')->getField('data');
            if (!empty($data)) {
                $data = unserialize($data);
                if (is_array($data) && !empty($data)) {
                    foreach ($data as $key => $val) {
                        if (!in_array($key, ['smtp_server','smtp_port','smtp_user','smtp_pwd','smtp_from_eamil'])) {
                            unset($data[$key]);
                        }
                    }
                }
            }
            /*--end*/

            $data['smtp_syn_weapp'] = 1;

            /*多語言*/
            if (!is_language()) {
                tpCache('smtp',$data);
            } else {
                $smtp_tpl_db = \think\Db::name('smtp_tpl');
                $smtptplList = $smtp_tpl_db->field('tpl_id,lang')->getAllWithIndex('lang');
                $smtptplRow = $smtp_tpl_db->field('tpl_id,lang',true)
                    ->where('lang', get_main_lang())
                    ->order('tpl_id asc')
                    ->select();

                $langRow = \think\Db::name('language')->order('id asc')->select();
                foreach ($langRow as $key => $val) {
                    /*同步多語言郵件模板表數據*/
                    if (empty($smtptplList[$val['mark']]) && !empty($smtptplRow)) {
                        foreach ($smtptplRow as $key2 => $val2) {
                            $smtptplRow[$key2]['lang'] = $val['mark'];
                        }
                        model('SmtpTpl')->saveAll($smtptplRow);
                    }
                    /*--end*/
                    tpCache('smtp', $data, $val['mark']);
                }
            }
            /*--end*/
        }
    }

    /**
     * 升級前臺會員中心的模板檔案
     */
    public function update_template($type = '')
    {
        if (!empty($type)) {
            if ('users' == $type) {
                if (file_exists(ROOT_PATH.'template/pc/users') || file_exists(ROOT_PATH.'template/mobile/users')) {
                    /*升級之前，備份涉及的原始檔*/
                    $upgrade = getDirFile(DATA_PATH.'backup'.DS.'tpl');
                    if (!empty($upgrade) && is_array($upgrade)) {
                        delFile(DATA_PATH.'backup'.DS.'template_www');
                        foreach ($upgrade as $key => $val) {
                            $source_file = ROOT_PATH.$val;
                            if (file_exists($source_file)) {
                                $destination_file = DATA_PATH.'backup'.DS.'template_www'.DS.$val;
                                tp_mkdir(dirname($destination_file));
                                @copy($source_file, $destination_file);
                            }
                        }

                        // 遞迴複製資料夾
                        $this->recurse_copy(DATA_PATH.'backup'.DS.'tpl', rtrim(ROOT_PATH, DS));
                    }
                    /*--end*/
                }
            }
        }
    }

    /**
     * 自定義函式遞迴的複製帶有多級子目錄的目錄
     * 遞迴複製資料夾
     *
     * @param string $src 原目錄
     * @param string $dst 複製到的目錄
     * @return string
     */                        
    //參數說明：            
    //自定義函式遞迴的複製帶有多級子目錄的目錄
    private function recurse_copy($src, $dst)
    {
        $planPath_pc = 'template/pc/';
        $planPath_m = 'template/mobile/';
        $dir = opendir($src);

        /*pc和mobile目錄存在的情況下，才拷貝會員模板到相應的pc或mobile里*/
        $dst_tmp = str_replace('\\', '/', $dst);
        $dst_tmp = rtrim($dst_tmp, '/').'/';
        if (stristr($dst_tmp, $planPath_pc) && file_exists($planPath_pc)) {
            tp_mkdir($dst);
        } else if (stristr($dst_tmp, $planPath_m) && file_exists($planPath_m)) {
            tp_mkdir($dst);
        }
        /*--end*/

        while (false !== $file = readdir($dir)) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->recurse_copy($src . '/' . $file, $dst . '/' . $file);
                }
                else {
                    if (file_exists($src . DIRECTORY_SEPARATOR . $file)) {
                        /*pc和mobile目錄存在的情況下，才拷貝會員模板到相應的pc或mobile里*/
                        $rs = true;
                        $src_tmp = str_replace('\\', '/', $src . DIRECTORY_SEPARATOR . $file);
                        if (stristr($src_tmp, $planPath_pc) && !file_exists($planPath_pc)) {
                            continue;
                        } else if (stristr($src_tmp, $planPath_m) && !file_exists($planPath_m)) {
                            continue;
                        }
                        /*--end*/
                        $rs = @copy($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
                        if($rs) {
                            @unlink($src . DIRECTORY_SEPARATOR . $file);
                        }
                    }
                }
            }
        }
        closedir($dir);
    }
}
