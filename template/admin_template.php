<div class="wrap">
  <h2><?php echo __("Step 1: Get your serialnumber and token", 'nabaztag')?></h2>
  <p><?php printf (__("Get your serialnumber and securitytoken from %s and ensure that you enable access to your Nabaztag via the API.", 'nabaztag'),
                  '<a href="http://my.nabaztag.com/vl/action/myTerrier.do?onglet=MesPreferences" target="_blank">my.nabaztag.com</a>');?>  
  <img src="http://web2.0du.de/pictures/nabaztag_enable.png" alt=""/></p>
   
  <h2><?php echo __("Step 2: Set the credentials", 'nabaztag')?></h2>
  <p><?php echo __("These two settings, are all the plugin needs. After submiting the form, you're visitors will speak through you're rabbit. Cool aye?", 'nabaztag')?></p>

  <form name="form1" method="post" action="<?php $location ?>">
  
  <?php if($flash_1):?>
    <p style='padding:0 8px;color:#9f6000;background-color:#feefb3;border:1px solid #9f6000;'>
    <strong><?php echo __("Error!", "nabaztag")?></strong>
    <?php echo __("You're credentials are wrong. Please check the fields and submitt the form again.", "nabaztag")?> 
  </p>
  <?php endif;?>
  
  <?php if($flash_2):?>
    <p style='padding:0 8px;color:#9f6000;background-color:#6ffc63;border:1px solid #9f6000;'>
    <strong><?php echo __("Success", "nabaztag")?></strong> - 
    <?php printf(__("%s will read out you're comments!", "nabaztag"), get_option("nab_name")) ?> 
  </p>
  <?php endif;?>
  
    <table class="form-table">
      <tr valign="top">
        <th scope="row"><label for="nab_id"><?php echo __("Serialnumber", 'nabaztag')?></label></th>
        <td><input name="nab_id" value="<?php echo get_option("nab_id");?>" type="text" class="regular-text" /></td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="nab_token"><?php echo __("Token", 'nabaztag')?></label></th>
        <td><input name="nab_token" value="<?php echo get_option("nab_token");?>" type="text" class="regular-text" />
      </td>
    </tr>
   </table>
   <p class="submit"><input type="submit" class="button-primary" value="<?php echo __("Submit", 'nabaztag')?>" /></p>
</form>
<?php if(get_option("nab_valid") == 1):?>
<h2><?php echo __("Step 3: Pick your favourite voice!", 'nabaztag')?></h2>
<p><?php echo __("In the selectbox below you find a list of all the available voices four your languages.", "nabaztag")?><br />
   <?php echo __("Pick a voice, adjust the notification test and press preview. If you like the sound save your settings with submit", "nabaztag")?></p>
<form name="form2" method="post" action="<?php $location ?>">
<table class="form-table">
  <tr valign="top">
    <th scope="row"><label for="nab_message"><?php echo __("Notification Text", 'nabaztag')?></label></th>
    <td><input name="nab_message" value="<?php echo get_option("nab_message");?>" type="text" class="regular-text" /></td>
  </tr>
  <tr valign="top">
    <th scope="row"><label for="voice"><?php echo __("Voice", 'nabaztag')?></label></th>
    <td><select name="voice" size="1">
    <?php $selected_voice = get_option("nab_voice");?>?>
    <?php $voices = explode(',',get_option("nab_voices_cache")); ?>
    <?php foreach($voices as $voice): ?>
       <option<?php echo($voice === $selected_voice)?' selected="selected"' : ''?>><?php echo $voice?></option>
    <?php endforeach;?>
    </select></td>
  </tr>
</table>
<p class="submit"><input type="submit" class="button-primary" value="<?php echo __("Submit", 'nabaztag')?>" />
<input name="nb_preview" type="submit" class="button-primary" value="<?php echo __("Preview", 'nabaztag')?>" /></p>
</form>

  <h2><?php echo __("Step 4: Enjoy your Nabaztag and giv'me some feedback", 'nabaztag')?></h2>
  <p><?php echo __("I'd really like to get some feedback from you. Please drop me a line if you like the plugin or want new features.", 'nabaztag')?></p>
</div>
<?php endif;?>