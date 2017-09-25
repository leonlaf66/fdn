<?php
namespace common\cms;

class Mailer extends \yii\swiftmailer\Mailer
{
    public function render($view, $params = [], $layout = false)
    {
        $output = parent::render($view, $params, $layout);
        if (isset(\yii::$app->params['mailDebug']) && \yii::$app->params['mailDebug']) {
          ob_clean();
          echo '<!DOCTYPE html><html><head><title>Email Test</title></head>';
          echo '<body>'.$output.'</body>'.'</html>';
          exit;
        }
        return $output;
    }
}