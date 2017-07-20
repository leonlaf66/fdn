<table border="1" style="border-collapse:collapse">
<?php foreach($retsItems as $rets):?>
    <tr>
        <!--
        <td>
            <a href="<?php echo \WS::$app->params['frontendBaseUrl']?><?php echo $rets->getUrl()?>" target="_blank">
                <img src="<?php echo $rets->getPhotoUrl(60, 50)?>" alt="" style="width:60px;height:50px;"/>
            </a>
        </td>
        -->
        <td>
            <a href="<?php echo \WS::$app->params['frontendBaseUrl']?><?php echo $rets->getUrl()?>" target="_blank">
                <?php echo $rets->location?>
            </a>
        </td>
        <td><?php echo $rets->getData('list_price')?></td>
    </tr>
<?php endforeach?>
</table>