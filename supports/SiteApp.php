<?php
namespace common\supports;

class SiteApp extends \yii\web\Application
{
    public $language = 'en-US';
    public $autoLanguage = false;
    public $domain = '';
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

    public function getEtcConfigs($configFile)
    {
        return include(__DIR__.'/../etc/'.$configFile.'.php');
    }

    protected function houseInit()
    {
        if ($houseBaseUrl = \WS::$app->request->cookies->getValue('house_base_url')) {
            $this->houseBaseUrl = $houseBaseUrl;
        } else {
            $this->houseBaseUrl = 'http://www'.domain();
        }
    }

    public function getSystemConfig($key)
    {
        return \models\SiteSetting::get($key, $this->area->id);
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
        $reqCookies = \Yii::$app->request->cookies;
        $resCookies = \Yii::$app->response->cookies;

        if (! $this->autoLanguage) {
            if (isset($reqCookies['language'])) {
                $this->language = $reqCookies['language']->value;
            } else {
                if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                    $this->language = strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'zh-CN') !== false ? 'zh-CN' : 'en-US';
                }
                $resCookies->add(new \yii\web\Cookie([
                    'name' => 'language',
                    'value' => $this->language,
                    'domain' => domain(),
                    'expire'=>0,
                ]));
            }
        }
    }

    protected function initTranslation()
    {
        if(\yii::$app->request->get('_lang_') === '!2345@abc') {
            $this->session->set('translation-manager', true);
        }

        if(\yii::$app->request->get('_lang_') === 'no') {
            $this->session->set('translation-manager', false);
        }

        $this->translationStatus = \Yii::$app->session->get('translation-manager', false);
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