<?php if(is_english()):?>
  
<?php else:?>
  欢迎加入Usleju.com, 你于<?php echo date('Y-m-d H:i', strtotime($user->created_at))?> 用户为: <?php echo $user->email?>注册成为会员, 请点击以下链接进行确认!<br/>
  <br/>
<?php endif?>

<a href="<?php echo $url?>" target="_balnk"><?php echo $url?></a>
<br/>
<br/>
Thank you, Usleju.com