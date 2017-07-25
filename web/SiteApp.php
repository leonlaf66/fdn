<?php
namespace common\web;

class SiteApp extends \yii\web\Application
{
    public $lanuguage = 'en-US';
    public $houseBaseUrl = '';
    public $translationStatus = false;
    public $configuationData = [];
    public $shareItems = [];

    public function bootstrap()
    {
        ini_set('session.cookie_domain', domain());

        $this->initTranslation();
        $this->houseInit();
        $this->initModules();
        $this->initLanguage();

        parent::bootstrap();
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
        $cookies = \Yii::$app->request->cookies;

        if(isset($cookies['language'])) {
            $this->language = $cookies->getValue('language');
        } else {
            if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                $this->language = strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'zh-CN') !== false ? 'zh-CN' : 'en-US';
            }
        }
    }

    protected function initTranslation()
    {
        if(isset($_GET['translation-manager']) && $_GET['translation-manager']=='!2345@AbC') {
            $this->session->set('translation-manager', true);
        }
        if(\Yii::$app->session->get('translation-manager')) {
            $this->translationStatus = true;
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