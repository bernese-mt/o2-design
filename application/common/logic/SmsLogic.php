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

/**
 * Description of SmsLogic
 *
 * 簡訊類
 */
class SmsLogic 
{
    private $config;
    
    public function __construct() 
    {
        $this->config = tpCache('sms') ?: [];
    }

    /**
     * 發送簡訊邏輯
     * @param unknown $scene
     */
    public function sendSms($scene, $sender, $params, $unique_id=0)
    {
        $smsTemp = M('sms_template')->where("send_scene", $scene)->find();    //使用者註冊.
        $code = !empty($params['code']) ? $params['code'] : false;
        $content = !empty($params['content']) ? $params['content'] : false;
        if(empty($unique_id)){
            $session_id = session_id();
        }else{
            $session_id = $unique_id;
        }
        $product = $this->config['sms_product'];

        $smsParams = array(
            1 => "{\"code\":\"$code\",\"product\":\"$product\"}", //1. 使用者註冊
            2 => "{\"code\":\"$code\"}", //2. 使用者找回密碼
            3 => "{\"code\":\"$code\"}", //3. 
            4 => "{\"content\":\"$content\"}", //4. 
        );

        $smsParam = $smsParams[$scene];

        //提取發送簡訊內容
        $scenes = config('SEND_SCENE');
        $msg = $scenes[$scene][1];
        $params_arr = json_decode($smsParam);
        foreach ($params_arr as $k => $v) {
            $msg = str_replace('${' . $k . '}', $v, $msg);
        }

        //發送記錄儲存數據庫
        $log_id = M('sms_log')->insertGetId(array('mobile' => $sender, 'code' => $code, 'add_time' => time(), 'session_id' => $session_id, 'status' => 0, 'scene' => $scene, 'msg' => $msg));
        if ($sender != '' && check_mobile($sender)) {//如果是正常的手機號碼才發送
            try {
                $resp = $this->realSendSms($sender, $smsTemp['sms_sign'], $smsParam, $smsTemp['sms_tpl_code']);
            } catch (\Exception $e) {
                $resp = ['status' => -1, 'msg' => $e->getMessage()];
            }
            if ($resp['status'] == 1) {
                M('sms_log')->where(array('id' => $log_id))->save(array('status' => 1)); //修改發送狀態為成功
            }else{
                M('sms_log')->where(array('id' => $log_id))->update(array('error_msg'=>$resp['msg'])); //發送失敗, 將發送失敗資訊儲存數據庫
            }
            return $resp;
        }else{
           return $result = ['status' => -1, 'msg' => '接收手機號不正確['.$sender.']'];
        }
        
    }

    private function realSendSms($mobile, $smsSign, $smsParam, $templateCode)
    {
        if (config('sms_debug') == true) {
            return array('status' => 1, 'msg' => '專用於越過簡訊發送');
        }
        
        $type = (int)$this->config['sms_platform'] ?: 1;
        switch($type) {
            case 1:
                $result = $this->sendSmsByAliyun($mobile, $smsSign, $smsParam, $templateCode);
                break;
            case 2:
                $result = $this->sendSmsByAlidayu($mobile, $smsSign, $smsParam, $templateCode);
                break;
            default:
                $result = ['status' => -1, 'msg' => '不支援的簡訊平臺'];
        }
        
        return $result;
    }
    
    /**
     * 發送簡訊（阿里大於）
     * @param $mobile  手機號碼
     * @param $code    驗證碼
     * @return bool    簡訊發送成功返回true失敗返回false
     */
    private function sendSmsByAlidayu($mobile, $smsSign, $smsParam, $templateCode)
    {
        //時區設定：亞洲/上海
        date_default_timezone_set('Asia/Shanghai');
        //這個是你下面實例化的類
        vendor('Alidayu.TopClient');
        //這個是topClient 裡面需要實例化一個類所以我們也要載入 不然會報錯
        vendor('Alidayu.ResultSet');
        //這個是成功后返回的資訊檔案
        vendor('Alidayu.RequestCheckUtil');
        //這個是錯誤資訊返回的一個php檔案
        vendor('Alidayu.TopLogger');
        //這個也是你下面示例的類
        vendor('Alidayu.AlibabaAliqinFcSmsNumSendRequest');

        $c = new \TopClient;
        //App Key的值 這個在開發者控制檯的應用管理點選你新增過的應用就有了
        $c->appkey = $this->config['sms_appkey'];
        //App Secret的值也是在哪裡一起的 你點選檢視就有了
        $c->secretKey = $this->config['sms_secretKey'];
        //這個是使用者名稱記錄那個使用者操作
        $req = new \AlibabaAliqinFcSmsNumSendRequest;
        //代理人編號 可選
        $req->setExtend("123456");
        //簡訊型別 此處預設 不用修改
        $req->setSmsType("normal");
        //簡訊簽名 必須
        $req->setSmsFreeSignName($smsSign);
        //簡訊模板 必須
        $req->setSmsParam($smsParam);
        //簡訊接收號碼 支援單個或多個手機號碼，傳入號碼為11位手機號碼，不能加0或+86。群發短信需傳入多個號碼，以英文逗號分隔，
        $req->setRecNum("$mobile");
        //簡訊模板ID，傳入的模板必須是在簡訊平臺「管理中心-簡訊模板管理」中的可用模板。
        $req->setSmsTemplateCode($templateCode); // templateCode

        $c->format = 'json';

        //發送簡訊
        $resp = $c->execute($req);
        //簡訊發送成功返回True，失敗返回false
        if ($resp && $resp->result) {
            return array('status' => 1, 'msg' => $resp->sub_msg);
        } else {
            return array('status' => -1, 'msg' => $resp->msg . ' ,sub_msg :' . $resp->sub_msg . ' subcode:' . $resp->sub_code);
        }
    }

    /**
     * 發送簡訊（阿里云簡訊）
     * @param $mobile  手機號碼
     * @param $code    驗證碼
     * @return bool    簡訊發送成功返回true失敗返回false
     */
    private function sendSmsByAliyun($mobile, $smsSign, $smsParam, $templateCode)
    {
        include_once './vendor/aliyun-php-sdk-core/Config.php';
        include_once './vendor/Dysmsapi/Request/V20170525/SendSmsRequest.php';
        
        $accessKeyId = $this->config['sms_appkey'];
        $accessKeySecret = $this->config['sms_secretKey'];
        
        //簡訊API產品名
        $product = "Dysmsapi";
        //簡訊API產品域名
        $domain = "dysmsapi.aliyuncs.com";
        //暫時不支援多Region
        $region = "cn-hangzhou";

        //初始化訪問的acsCleint
        $profile = \DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
        \DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
        $acsClient= new \DefaultAcsClient($profile);

        $request = new \Dysmsapi\Request\V20170525\SendSmsRequest;
        //必填-簡訊接收號碼
        $request->setPhoneNumbers($mobile);
        //必填-簡訊簽名
        $request->setSignName($smsSign);
        //必填-簡訊模板Code
        $request->setTemplateCode($templateCode);
        //選填-假如模板中存在變數需要替換則為必填(JSON格式)
        $request->setTemplateParam($smsParam);
        //選填-發送簡訊流水號
        //$request->setOutId("1234");

        //發起訪問請求
        $resp = $acsClient->getAcsResponse($request);
        
        //簡訊發送成功返回True，失敗返回false
        if ($resp && $resp->Code == 'OK') {
            return array('status' => 1, 'msg' => $resp->Code);
        } else {
            return array('status' => -1, 'msg' => $resp->Message . '. Code: ' . $resp->Code);
        }
    }
}
