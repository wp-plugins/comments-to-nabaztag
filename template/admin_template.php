<div class="wrap">
  <h2><?php echo __("Step 1: Get your serialnumber and token", 'nabaztag')?></h2>
  <p><?php printf (__("Get your serialnumber and securitytoken from %s and ensure that you enable access to your Nabaztag via the API.", 'nabaztag'),
                  '<a href="http://my.nabaztag.com/vl/action/myTerrier.do?onglet=MesPreferences" target="_blank">my.nabaztag.com</a>');?>  
  <img src="http://web2.0du.de/pictures/nabaztag_enable.png" alt=""/></p>
   
  <h2><?php echo __("Step 2: Set the credentials", 'nabaztag')?></h2>
  <p><?php echo __("These two settings, are all the plugin needs. After submiting the form, you're visitors will speak through you're rabbit. Cool aye?", 'nabaztag')?></p>
  <form name="form1" method="post" action="<?php $location ?>">
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
<p class="submit"><input type="submit" class="button-primary" value="Submit" /></p>
</form>

   <h2><?php echo __("Step 3: Configure the plugin further, if you like!", 'nabaztag')?></h2>
   <form name="form2" action="<?php $location ?>">
   <p>
    <label for="voice"><?php echo __("Voice", 'nabaztag')?></label>
    <select name="voice" size="1">
    <?php foreach($voices as $voice): ?>
       <option><?php echo $voice?></option>
    <?php endforeach;?>
    </select>
  </p>
  </form>

  <h2><?php echo __("Step 4: Enjoy your Nabaztag and giv'me some feedback", 'nabaztag')?></h2>
  <p><?php echo __("I'd really like to get some feedback from you. Please drop me a line if you like the plugin or want new features.", 'nabaztag')?></p>
</div>