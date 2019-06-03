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

namespace app\common\logic;

use think\Db;

/**
 * Description of SmsLogic
 *
 * 郵件類
 */
class EmailLogic 
{
    private $config;
    private $home_lang;
    
    public function __construct($smtp_config = []) 
    {
        $this->config = !empty($smtp_config) ? $smtp_config : tpCache('smtp');
        $this->home_lang = get_home_lang();
    }

    /**
     * 置換郵件模版內容
     * @param intval $scene 應用場景
     */
    private function replaceContent($scene = '', $params = '')
    {
        if (0 == intval($scene)) {
            $msg = $params;
        } else {
            $params_arr = [];
            $emailTemp = Db::name('smtp_tpl')->where([
                    'send_scene'=> $scene,
                    'lang'      => $this->home_lang,
                ])->find();
            if (!empty($emailTemp)) {
                $msg = $emailTemp['tpl_content'];
                preg_match_all('/\${([^\}]+)}/i', $msg, $matchs);
                if (!empty($matchs[1])) {
                    foreach ($matchs[1] as $key => $val) {
                        if (is_array($params)) {
                            $params_arr[$val] = $params[$val];
                        } else {
                            $params_arr[$val] = $params;
                        }
                    }
                }

                //置換郵件模版內容
                foreach ($params_arr as $k => $v) {
                    $msg = str_replace('${' . $k . '}', $v, $msg);
                }
            } else {
                return '';
            }
        }

        return $msg;
    }

    /**
     * 郵件發送
     * @param $to    接收人
     * @param string $subject   郵件標題
     * @param string|array $data   郵件內容(html模板渲染后的內容)
     * @param string $scene   使用場景
     * @throws Exception
     */
    public function send_email($to='', $subject='', $data='', $scene=1, $library = 'phpmailer'){
        if (0 < intval($scene)) {
            $smtp_tpl_row = Db::name('smtp_tpl')->where([
                    'send_scene'=> $scene,
                    'lang'      => $this->home_lang,
                ])->find();
            if (empty($smtp_tpl_row)) {
                return ['code'=>0,'msg'=>'找不到相關郵件模板！'];
            } else if (empty($smtp_tpl_row['is_open'])) {
                return ['code'=>0,'msg'=>'該功能待開放，請先啟用郵件模板('.$smtp_tpl_row['tpl_name'].')'];
            } else {
                empty($subject) && $subject = $smtp_tpl_row['tpl_title'];
            }
        }

        switch ($library) {
            case 'phpmailer':
                return $this->send_phpmailer($to, $subject, $data, $scene);
                break;

            case 'swiftmailer':
                return $this->send_swiftmailer($to, $subject, $data, $scene);
                break;
            
            default:
                return $this->send_phpmailer($to, $subject, $data, $scene);
                break;
        }
    }

    /**
     * 郵件發送 - swiftmailer第三方庫
     * @param $to    接收人
     * @param string $subject   郵件標題
     * @param string|array $data   郵件內容(html模板渲染后的內容)
     * @param string $scene   使用場景
     * @throws Exception
     */
    private function send_swiftmailer($to='', $subject='', $data='', $scene=1){
        vendor('swiftmailer.lib.swift_required');
        // require_once 'vendor/swiftmailer/lib/swift_required.php';
        try {
            //判斷openssl是否開啟
            $openssl_funcs = get_extension_funcs('openssl');
            if(!$openssl_funcs){
                return array('code'=>0 , 'msg'=>'請先開啟php的openssl擴充套件');
            }

            //判斷openssl是否開啟
            // $sockets_funcs = get_extension_funcs('sockets');
            // if(!$sockets_funcs){
            //     return array('code'=>0 , 'msg'=>'請先開啟php的sockets擴充套件');
            // }
        
            empty($to) && $to = $this->config['smtp_from_eamil'];
            $to = explode(',', $to);

            //smtp伺服器
            $host = $this->config['smtp_server'];
            //埠 - likely to be 25, 465 or 587
            $port = intval($this->config['smtp_port']);
            //使用者名稱
            $user = $this->config['smtp_user'];
            //密碼
            $pwd = $this->config['smtp_pwd']; 
            //發送者
            $from = $this->config['smtp_user'];
            //發送者名稱
            $from_name = $user;//tpCache('web.web_name');
            // 使用安全協議
            $encryption_type = null;
            switch ($port) {
                case 465:
                    $encryption_type = 'ssl';
                    break;
                
                case 587:
                    $encryption_type = 'tls';
                    break;

                default:
                    # code...
                    break;
            }
            //HTML內容轉換
            $body = $this->replaceContent($scene, $data);

            // Create the Transport
            $transport = (new \Swift_SmtpTransport($host, $port, $encryption_type))
                ->setUsername($user)
                ->setPassword($pwd);

            // Create the Mailer using your created Transport
            $mailer = new \Swift_Mailer($transport);

            // Create a message
            $message = (new \Swift_Message($subject))
                ->setFrom([$from=>$from_name])
                // ->setTo([$to, '第二個接收者郵箱' => '別名'])
                ->setTo($to)
                ->setContentType("text/html")
                ->setBody($body);

            // Send the message
            $result = $mailer->send($message);
            if (!$result) {
                return array('code'=>0 , 'msg'=>'發送失敗');
            } else {
                return array('code'=>1 , 'msg'=>'發送成功');
            }
        } catch (\Exception $e) {
            return array('code'=>0 , 'msg'=>'發送失敗: '.$e->errorMessage());
        }
    }

    /**
     * 郵件發送 - 第三方庫phpmailer
     * @param $to    接收人
     * @param string $subject   郵件標題
     * @param string|array $data   郵件內容(html模板渲染后的內容)
     * @param string $scene   使用場景
     * @throws Exception
     */
    private function send_phpmailer($to='', $subject='', $data='', $scene=1){
        vendor('phpmailer.PHPMailerAutoload');
        try {
            //判斷openssl是否開啟
            $openssl_funcs = get_extension_funcs('openssl');
            if(!$openssl_funcs){
                return array('code'=>0 , 'msg'=>'請先開啟php的openssl擴充套件');
            }

            //判斷openssl是否開啟
            // $sockets_funcs = get_extension_funcs('sockets');
            // if(!$sockets_funcs){
            //     return array('code'=>0 , 'msg'=>'請先開啟php的sockets擴充套件');
            // }

            $mail = new \PHPMailer;
            $mail->CharSet  = 'UTF-8'; //設定郵件編碼，預設ISO-8859-1，如果發中文此項必須設定，否則亂碼
            $mail->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            //除錯輸出格式
            //$mail->Debugoutput = 'html';
            //接收者郵件
            empty($to) && $to = $this->config['smtp_from_eamil'];
            $to = explode(',', $to);
            //smtp伺服器
            $mail->Host = $this->config['smtp_server'];
            //埠 - likely to be 25, 465 or 587
            $mail->Port = intval($this->config['smtp_port']);
            // 使用安全協議
            switch ($mail->Port) {
                case 465:
                    $mail->SMTPSecure = 'ssl';
                    break;
                
                case 587:
                    $mail->SMTPSecure = 'tls';
                    break;

                default:
                    # code...
                    break;
            }
            //Whether to use SMTP authentication
            $mail->SMTPAuth = true;
            //使用者名稱
            $mail->Username = $this->config['smtp_user'];
            //密碼
            $mail->Password = $this->config['smtp_pwd'];
            //Set who the message is to be sent from
            $mail->setFrom($this->config['smtp_user']);
            //回覆地址
            //$mail->addReplyTo('replyto@example.com', 'First Last');
            //接收郵件方
            if(is_array($to)){
                foreach ($to as $v){
                    $mail->addAddress($v);
                }
            }else{
                $mail->addAddress($to);
            }

            $mail->isHTML(true);// send as HTML
            //標題
            $mail->Subject = $subject;
            //HTML內容轉換
            $content = $this->replaceContent($scene, $data);
            $mail->msgHTML($content);
            //Replace the plain text body with one created manually
            //$mail->AltBody = 'This is a plain-text message body';
            //新增附件
            //$mail->addAttachment('images/phpmailer_mini.png');
            //send the message, check for errors
            $result = $mail->send();
            if (!$result) {
                return array('code'=>0 , 'msg'=>'發送失敗:'.$mail->ErrorInfo);
            } else {
                return array('code'=>1 , 'msg'=>'發送成功');
            }
        } catch (\Exception $e) {
            return array('code'=>0 , 'msg'=>'發送失敗: '.$e->errorMessage());
        }
    }
}
