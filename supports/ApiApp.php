<?php
namespace common\supports;

class ApiApp extends \yii\web\Application
{
    public $lanuguage = 'en-US';
    public $domain = '';
    public $houseBaseUrl = '';
    public $configuationData = [];
    public $shareItems = [];
    public $areaMaps = [];

    public function bootstrap()
    {
        $this->houseInit();
        $this->initModules();
        parent::bootstrap();
        $this->initLanguage();
    }

    protected function headersResponse()
    {
        header('content-type:application:json;charset=utf8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Request-Method:OPTIONS,GET,POST,DELETE");
        header('Access-Control-Allow-Headers:x-requested-with,content-type'); 
    }

    protected function houseInit()
    {
        if ($houseBaseUrl = \WS::$app->request->cookies->getValue('house_base_url')) {
            $this->houseBaseUrl = $houseBaseUrl;
        } else {
            $this->houseBaseUrl = 'http://www'.domain();
        }
    }

    public function getSystemConfig($key, $defValue = null)
    {
        return \models\SiteSetting::get($key, $defValue);
    }

    public function share($name, $data = null)
    {
        if (is_null($data)) {
            return isset($this->shareItems[$name]) ? $this->shareItems[$name] : null;
        }
        $this->shareItems[$name] = $data;
    }

    protected function initLanguage()
    {
        $headers = \Yii::$app->request->headers;

        $language = $headers->get('language');
        if (!$language) {
            $language = \Yii::$app->request->get('language', 'en-US');
        }

        $this->language = in_array($language, ['en-US', 'zh-CN']) ? $language : 'en-US';
    }

    protected function initModules()
    {
        foreach($this->getModules() as $moduleId=>$moduleClass) 
        {
            $configFile = APP_ROOT.'/app/'.$moduleId.'/etc/config.php';
            if(file_exists($configFile)) {
                $config = include($configFile);
                new $moduleClass($moduleId, null, $config);
            }
        }
    }
}