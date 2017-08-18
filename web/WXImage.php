<?php
// http://mmbiz.qpic.cn/mmbiz_jpg/jwhY1YicXqftWiaZiclttsNRia8kwdMysMuJDvZCAAq4HkkkHV4Y7CdiaH6HYAK5PYcE9ADg8UiaRXiaiaEcjm0U1UEpPQ/640?wx_fmt=jpeg&tp=webp&wxfrom=5&wx_lazy=1
namespace common\web;

class WXImage extends \yii\base\Component
{
    public $baseDir = '';
    public $baseUrl = '';

    public function process($content, $replace = false)
    {
        if (preg_match_all('/<img.*?src="(.*?)".*?>/is', $content, $matchs)) {
            foreach ($matchs[1] as $wxImageUrl) {
                if (strpos($wxImageUrl, 'mmbiz.qpic.cn') !== false) {
                    $newWxImageUrl = $this->getLocalImage($wxImageUrl, $replace);
                    $content = str_replace($wxImageUrl, $newWxImageUrl, $content);
                }
            }
        }

        return $content;
    }

    public function getLocalImage($url, $replace = false)
    {
        $hashId = md5($url);
        $localFileDir = sprintf('%s/%s/%s',
                substr($hashId, 0, 1),
                substr($hashId, 1, 1),
                substr($hashId, 2, 2)
                );

        if (! is_dir($this->baseDir.'/'.$localFileDir)) {
            mkdir($this->baseDir.'/'.$localFileDir, 0777, true);
        }

        $localFileName = $localFileDir.'/'.substr($hashId, 4).'.jpg';

        /*已经有本地缓存*/
        if (! $replace && file_exists($this->baseDir.'/'.$localFileName)) {
            return $this->baseUrl.'/'.$localFileName;
        }

        /*写图片到本地缓存*/
        $ch = curl_init();
        $timeout = 30;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $blob = curl_exec($ch);
        curl_close($ch);
        /*
        ob_start();
        readfile($url);
        $blob = ob_get_contents();
        ob_end_clean();*/

        $fp = fopen($this->baseDir.'/'.$localFileName, 'a');  
        $imgLen = strlen($blob);
        $_inx = 1024;
        $_time = ceil($imgLen / $_inx);  
        for($i = 0; $i < $_time; $i ++){  
            fwrite($fp,substr($blob, $i * $_inx, $_inx));
        }  
        fclose($fp);

        return $this->baseUrl.'/'.$localFileName;
    }
}