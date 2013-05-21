<?php

    /**
     *	Captcha plugin
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 Romanenko Sergey / Awilum
     *	@version 1.0.0
     *
     */

    // Register plugin
    registerPlugin(getPluginId(__FILE__),
                   getPluginFilename(__FILE__),
                   'Captcha',
                   '1.0.0',            	
                   'Captcha plugin',
                   'Awilum',           	
                   'http://awilum.webdevart.ru/',
                   '');


    $cryptinstall = getOption('siteurl').'plugins/captcha/crypt/images/';    
    include 'crypt/cryptographp.fct.php';


    /*
      Check captcha

      if((getOption('captcha_installed') !== null) and (getOption('captcha_installed') == 'true')) {
          if (!chk_crypt($_POST['code'])) $errors['captcha_robot'] = lang('captcha_robot');
      }
     
      Draw captcha
      
      <?php if((getOption('captcha_installed') !== null) and (getOption('captcha_installed') == 'true')) { ?>
      <table>  
        <tr><td><?php echo lang('captcha_crypt'); ?>:<input type="text" name="code"></td><td><?php dsp_crypt(0,1); ?></td></tr>  
      </table>
      <?php } ?>

     */

    getPluginLanguage('Captcha'); 


    function captchaInstall() {
        addOption('captcha_installed','true');
    }


    function captchaUninstall() {
        deleteOption('captcha_installed');
    }    