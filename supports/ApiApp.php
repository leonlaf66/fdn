<?php
namespace common\supports;

class ApiApp extends \yii\web\Application
{
    public $lanuguage = 'en-US';
    public $domain = '';
    public $houseBaseUrl = '';
    public $configuationData = [];
    public $shareItems = [];

    public function bootstrap()
    {
        $this->houseInit();
        $this->initModules();
        parent::bootstrap();

        $this->initLanguage();
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
        return \common\core\Configure::get($key, $defValue);
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

        if($language = $headers->get('language')) {
            if (in_array($language, ['en-US', 'zh-CN'])) {
                $this->language = $language;
            }
        }
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