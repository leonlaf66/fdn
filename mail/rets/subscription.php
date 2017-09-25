<?php
  $baseUrl = \WS::$app->params['frontendBaseUrl'];

  $styles = [
    'container' => function () {
      echo 'max-width:720px;margin-left:auto;margin-right:auto;font-size:12px;';
    },
    'header' => function () {
      echo 'height:100px;background-color:#99bd2a;margin-bottom:16px;';
    },
    'items-box' => function () {
      echo 'margin-left:-5px;margin-top:-20px;background-color:#ececec';
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
    
  </div>
  <div style="<?php ($styles['items-box'])()?>">
    <?php foreach($retsItems as $rets):?>
        <a href="<?php echo $baseUrl.'/'.$rets->getUrl()?>" target="_blank" style="<?php ($styles['item'])()?>">
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
    <?php endforeach?>
  </div>
</div>