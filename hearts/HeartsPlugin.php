<?php

    /**
     *	Hearts plugin
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
                   'Hearts',
                   '1.0.0',            	
                   'Hearts plugin',
                   'Awilum',           	
                   'http://awilum.webdevart.ru/',
                   'heartsAdmin');


    
    // Get language file for this plugin
    getPluginLanguage('Hearts');
    
    // Include Microblog Admin
    getPluginAdmin('Hearts');


    // Add some hooks
    addHook('theme_header','heartsThemeHeaders');
    addHook('frontend_pre_render','heartsAjax');

    

    /**
     * Hearts theme Headers
     */
    function heartsThemeHeaders() {
        echo '<script>function heartClick(uid){$.ajax({type:"post",data:"uid="+uid,url: "'.getOption('siteurl').'",success: function(res){data = res.split(","); $(".counter_"+uid).html(data[0]); if(data[1] == "1"){ $(".image_"+uid).attr("src","'.getOption('siteurl').'plugins/hearts/img/heart_off.png"); } else { $(".image_"+uid).attr("src","'.getOption('siteurl').'plugins/hearts/img/heart_on.png"); } } }); }</script>';   
    }


    /**
     * Hearts ajax
     */
    function heartsAjax() {
        $hearts_main_dir  = 'data/hearts/';

        if(isPost('uid')) {
            $hearts_data_xml  = getXMLdb($hearts_main_dir.'hearts_data_'.post('uid').'.xml');
            $hearts_index_xml = getXMLdb($hearts_main_dir.'hearts_index.xml');
            $mark_ip = selectXMLRecord($hearts_data_xml,'heart[ip="'.$_SERVER['REMOTE_ADDR'].'"]','all');
        

            if(empty($mark_ip)) { 
                insertXMLRecord($hearts_data_xml,'heart',array('status'=>'1','ip'=>$_SERVER['REMOTE_ADDR']));                          
                updateXMLRecordWhere($hearts_data_xml,'heart[ip="'.$_SERVER['REMOTE_ADDR'].'"]',array('status'=>'1'));                 
          
                $heart = selectXMLRecord($hearts_index_xml,'heart[uid="'.post('uid').'"]','all');
          
                $counter = (int)$heart[0]->counter + 1;

                updateXMLRecordWhere($hearts_index_xml,'heart[uid="'.post('uid').'"]',array('counter'=>$counter));

                $status = 0; // some magic happens here ...

                // Echo data 
                echo $counter.','.$status;
                // and die
                die();

             } else {          
 
                $heart = selectXMLRecord($hearts_index_xml,'heart[uid="'.post('uid').'"]','all');
          
                if($mark_ip[0]->status == '0') {
                    $counter = (int)$heart[0]->counter + 1;  
                    updateXMLRecordWhere($hearts_index_xml,'heart[uid="'.post('uid').'"]',array('counter'=>$counter));
                    updateXMLRecordWhere($hearts_data_xml,'heart[ip="'.$_SERVER['REMOTE_ADDR'].'"]',array('status'=>'1'));
                    $status = 0;
                } else {
                    $counter = (int)$heart[0]->counter - 1;
                    updateXMLRecordWhere($hearts_index_xml,'heart[uid="'.post('uid').'"]',array('counter'=>$counter));
                    updateXMLRecordWhere($hearts_data_xml,'heart[ip="'.$_SERVER['REMOTE_ADDR'].'"]',array('status'=>'0'));
                    $status = 1;
                }                 

                // Echo data 
                echo $counter.','.$status;
                // and die
                die();         
            }                    
        }      
            
    }


    /**
     * Draw heart function 
     */     
    function heart($uid) {
            
        $hearts_main_dir = 'data/hearts/';

        // Check is Heart exsits
        if(file_exists($hearts_main_dir.'hearts_data_'.$uid.'.xml')) {                
                    
            // Get hearts index and data database
            $hearts_index_xml = getXMLdb($hearts_main_dir.'hearts_index.xml');
            $hearts_data_xml = getXMLdb($hearts_main_dir.'hearts_data_'.$uid.'.xml');
            
            // Select record with ip
            $mark_ip = selectXMLRecord($hearts_data_xml,'heart[ip="'.$_SERVER['REMOTE_ADDR'].'"]','all');      
            

            // Check if record with this ip exists then show off heart
            if(empty($mark_ip)) {
                $heart_img = 'heart_off.png';
            } else {
                // if status = 1 then add ability to turn off heart
                if($mark_ip[0]->status == '1') {
                    $heart_img = 'heart_on.png';            
                } else {
                    $heart_img = 'heart_off.png';            
                }          
            }

            // Select current heart
            $heart = selectXMLRecord($hearts_index_xml,'heart[uid="'.$uid.'"]','all');

            // Draw heart
            include 'templates/frontend/HeartTemplate.php';

        } else {
            echo 'Ooops... heart not found';
        }
    }   