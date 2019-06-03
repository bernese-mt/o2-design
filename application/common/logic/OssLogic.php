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

use OSS\OssClient;
use OSS\Core\OssException;

require_once './vendor/aliyun-oss-php-sdk/autoload.php';

/**
 * Class OssLogic
 * 對像儲存邏輯類
 */
class OssLogic
{
    static private $initConfigFlag = false;
    static private $accessKeyId = '';
    static private $accessKeySecret = '';
    static private $endpoint = '';
    static private $bucket = '';
    
    /** @var \OSS\OssClient */
    static private $ossClient = null;
    static private $errorMsg = '';
    
    static private $waterPos = [
        1 => 'nw',     //標識左上角水印
        2 => 'north',  //標識上居中水印
        3 => 'ne',     //標識右上角水印
        4 => 'west',   //標識左居中水印
        5 => 'center', //標識居中水印
        6 => 'east',   //標識右居中水印
        7 => 'sw',     //標識左下角水印
        8 => 'south',  //標識下居中水印
        9 => 'se',     //標識右下角水印
    ];
    
    public function __construct()
    {
        self::initConfig();
    }
    
    /**
     * 獲取錯誤資訊，一旦其他介面返回false時，可呼叫此介面檢視具體錯誤資訊
     * @return type
     */
    public function getError()
    {
        return self::$errorMsg;
    }
    
    static private function initConfig()
    {
        if (self::$initConfigFlag) {
            return;
        }
        
        $c = [];
        $configItems = 'oss_key_id,oss_key_secret,oss_endpoint,oss_bucket';
        $config = M('config')->field('name,value')->where('name', 'IN', $configItems)->select();
        foreach ($config as $v) {
            $c[$v['name']] = $v['value'];
        }
        self::$accessKeyId     = $c['oss_key_id'] ?: '';
        self::$accessKeySecret = $c['oss_key_secret'] ?: '';
        self::$endpoint        = $c['oss_endpoint'] ?: '';
        self::$bucket          = $c['oss_bucket'] ?: '';
        self::$initConfigFlag  = true;
    }

    static private function getOssClient()
    {
        if (!self::$ossClient) {
            self::initConfig();
            try {
                self::$ossClient = new OssClient(self::$accessKeyId, self::$accessKeySecret, self::$endpoint, false);
            } catch (OssException $e) {
                self::$errorMsg = "建立oss對像失敗，".$e->getMessage();
                return null;
            }
        }
        return self::$ossClient;
    }
    
    public function getSiteUrl()
    {
        $http = config('is_https') ? 'https://' : 'http://';
        $site_url = $http .self::$bucket . "." . self::$endpoint;

        $ossConfig = tpCache('oss');
        $oss_domain = $ossConfig['oss_domain'];
        if ($oss_domain) {
            $site_url = $http . $oss_domain;
        }

        return $site_url;
    }

    public function uploadFile($filePath, $object = null)
    {  
        $ossClient = self::getOssClient();
        if (!$ossClient) {
            return false;
        }
        
        if (is_null($object)) {
            $object = $filePath;
        }
        
        try {
            $ossClient->uploadFile(self::$bucket, $object, $filePath);
        } catch (OssException $e) {
            self::$errorMsg = "oss上傳檔案失敗，".$e->getMessage();
            return false;
        }
        
        return $this->getSiteUrl().'/'.$object;
    }
    
    /**
     * 獲取產品圖片的url
     * @param type $originalImg
     * @param type $width
     * @param type $height
     * @param type $defaultImg
     * @return type
     */
    public function getProductThumbImageUrl($originalImg, $width, $height, $defaultImg = '')
    {
        if (!$this->isOssUrl($originalImg)) {
            return $defaultImg;
        }
        
        // 圖片縮放（等比縮放）
        $url = $originalImg."?x-oss-process=image/resize,m_pad,h_$height,w_$width";
        
        $water = tpCache('water');
        if ($water['is_mark']) {
            if ($width > $water['mark_width'] && $height > $water['mark_height']) {
                if ($water['mark_type'] == 'img') {
                    if ($this->isOssUrl($water['mark_img'])) {
                        $url = $this->withImageWaterUrl($url, $water['mark_img'], $water['mark_degree'], $water['mark_sel']);
                    }
                } else {
                    $url = $this->withTextWaterUrl($url, $water['mark_txt'], $water['mark_txt_size'], $water['mark_txt_color'], $water['mark_degree'], $water['mark_sel']);
                }
            }
        }
        return $url;
    }
    
    /**
     * 獲取產品相簿的url
     * @param type $originalImg
     * @param type $width
     * @param type $height
     * @param type $defaultImg
     * @return type
     */
    public function getProductAlbumThumbUrl($originalImg, $width, $height, $defaultImg = '')
    {
        if (!($originalImg && strpos($originalImg, 'http') === 0 && strpos($originalImg, 'aliyuncs.com'))) {
            return $defaultImg;
        }
        
        // 圖片縮放（等比縮放）
        $url = $originalImg."?x-oss-process=image/resize,m_pad,h_$height,w_$width";
        return $url;
    }
    
    /**
     * 鏈接加上文字水印參數（文字水印(方針黑體，黑色)）
     * @param string $url
     * @param type $text
     * @param type $size
     * @param type $posSel
     * @return string
     */
    private function withTextWaterUrl($url, $text, $size, $color, $transparency, $posSel)
    {
        $color = $color ?: '#000000';
        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
            $color = '#000000';
        }
        $color = ltrim($color, '#');
        $text_encode = urlsafe_b64encode($text);
        $url .= ",image/watermark,text_{$text_encode},type_ZmFuZ3poZW5naGVpdGk,color_{$color},size_{$size},t_{$transparency},g_" . self::$waterPos[$posSel];
        return $url;
    }
    
    /**
     * 鏈接加上圖片水印參數
     * @param string $url
     * @param type $image
     * @param type $transparency
     * @param type $posSel
     * @return string
     */
    private function withImageWaterUrl($url, $image, $transparency, $posSel)
    {
        $image = ltrim(parse_url($image, PHP_URL_PATH), '/');
        $image_encode = urlsafe_b64encode($image);
        $url .= ",image/watermark,image_{$image_encode},t_{$transparency},g_" . self::$waterPos[$posSel];
        return $url;
    }
    
    /**
     * 是否是oss的鏈接
     * @param type $url
     * @return boolean
     */
    public function isOssUrl($url)
    {
        if ($url && strpos($url, 'http') === 0 && strpos($url, 'aliyuncs.com')) {
            return true;
        }
        return false;
    }
}