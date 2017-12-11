<?php
  $domain = \WS::$app->params['domain'];

  $styles = [
    'container' => function () {
      echo 'width:687px;margin-left:auto;margin-right:auto;font-size:12px;';
    },
    'header' => function () {
      echo 'height:60px;background-color:#99bd2a;margin-bottom:10px;padding:2px;box-sizing:border-box;';
    },
    'logo' => function () {
      echo 'width:46px;height:46px;margin-left:7px;margin-top:3px;background-image:url(http://www.usleju.cn//static/img/logo.png);background-size:100% 100%;';
    },
    'items-box' => function () {
      echo 'margin-left:-5px;margin-top:-20px;';
    },
    'item' => function () {
      echo 'display:inline-block;margin-left:5px;margin-top:20px;text-align:left;text-decoration:none;width:223px;overflow:hidden;';
    },
    'image' => function ($url) {
      echo "background-size: cover;background-position:center center;width:223px;height:174px;background-image:url({$url});position:relative;";
    },
    'item-info' => function () {
      echo "position:absolute;left:0;bottom:0;right:0;height:28px;line-height:28px;padding:0 5px;background:rgba(160,183,55,0.7);color:#fff";
    },
    'price' => function () {
      echo 'float:left;font-size:16px;';
    },
    'square' => function () {
      echo 'float:right';
    },
    'title' => function () {
      echo 'margin-top:5px;width:223px;white-space:nowrap;text-overflow:ellipsis;color:#333';
    }
  ];

?>
<div style="<?php ($styles['container'])()?>">
  <div style="<?php ($styles['header'])()?>">
    <div style="<?php ($styles['logo'])()?>"></div>
  </div>
  <div style="<?php ($styles['items-box'])()?>">
    <?php foreach($retsItems as $rets):?>
        <?php if($rets instanceof \common\estate\Rets):?>
        <a href="<?php echo 'http://ma'.$domain.'/'.$rets->getUrl()?>" target="_blank" style="<?php ($styles['item'])()?>">
            <?php
              $_render = $rets->render();
            ?>
            <div style="<?php ($styles['image'])($rets->getPhoto(0, 500, 500))?>">
              <div style="<?php ($styles['item-info'])()?>">
                <div style="<?php ($styles['price'])()?>"><?php echo $_render->get('list_price')['formatedValue']?></div>
                <div style="<?php ($styles['square'])()?>"><?php echo $_render->get('square_feet')['formatedValue']?></div>
              </div>
            </div>
            <div style="<?php ($styles['title'])()?>"><?php echo $_render->get('title')['value']?></div>
        </a>
        <?php else:?>
            <a href="<?php echo 'http://'.strtolower($rets->state).$domain.'/'.$rets->getUrl()?>" target="_blank" style="<?php ($styles['item'])()?>">
                <div style="<?php ($styles['image'])($rets->getPhoto(0)['url'])?>">
                    <div style="<?php ($styles['item-info'])()?>">
                        <div style="<?php ($styles['price'])()?>"><?php echo $rets->getFieldData('list_price')['formatedValue']?></div>
                        <div style="<?php ($styles['square'])()?>"><?php echo $rets->getFieldData('square_feet')['formatedValue']?></div>
                    </div>
                </div>
                <div style="<?php ($styles['title'])()?>"><?php echo $rets->getFieldData('title')['value']?></div>
            </a>
        <?php endif?>
    <?php endforeach?>
  </div>
</div>