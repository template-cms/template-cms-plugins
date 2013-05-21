<?php

    // Add hooks NAVIGATION
    addHook('admin_pages_second_navigation','adminSecondNavigation',array('pages',lang('hearts_submenu'),'hearts'));
    addHook('admin_header','heartsAdminHeaders');



    /**
     * Mailnewsletter Admin Headers
     */
    function heartsAdminHeaders() {

        // JS and hacked styles    
        echo '<style>               
                  #hearts-add {
                      margin-left: -140px!important;
                      width: 260px!important;
                  }
                  .hearts-edit {
                      margin-left: -140px!important;
                      width: 260px!important;
                  }
              </style>
              ';
        echo '<script>
              $(document).ready(function() {
                $(".reval-edit").click(function() {                  
                  addHeartEditClass($(this).attr("rel"));    
                });
              });
              
              function addHeartEditClass(id) {
                $("#hearts-edit-"+id).addClass("hearts-edit");
              }
              </script>';
    }



    /**
     * Hearts install
     */
  	function heartsInstall() {
  		$hearts_main_dir  = '../data/hearts/';

  		// Create directory and index and data database	
  		if(!is_dir($hearts_main_dir))  mkdir($hearts_main_dir, 0755);
  		createXMLdb($hearts_main_dir.'hearts_index');  		

  	}


    /**
     * Hearts admin function
     */
    function heartsAdmin() {        
        $hearts_main_dir = '../data/hearts/';

        // Get hearts index database
        $hearts_index_xml = getXMLdb($hearts_main_dir.'hearts_index.xml');

        // Select hearts index's 
        $hearts_index_records = selectXMLRecord($hearts_index_xml,'heart','all');     


        // Check for get actions
        if (isGet('action')) {
            // Switch actions
            switch (get('action')) {
                case "delete_heart": 
                    heartsDelete($hearts_main_dir,$hearts_index_xml,get('heart_id'));
                    redirect('index.php?id=pages&sub_id=hearts');
                break;
            }
        }

        // Edit heart
        if(isPost('edit_heart')) {
            heartsEditTitle($hearts_main_dir,$hearts_index_xml,post('title'),post('heart_id'));
            redirect('index.php?id=pages&sub_id=hearts');
        }

        // Create heart
        if(isPost('create_heart')) {
            heartsCreate($hearts_main_dir,$hearts_index_xml,post('title'));
            redirect('index.php?id=pages&sub_id=hearts');
        }


        include 'templates/backend/HeartsTemplate.php';
    }


    /**
     * Create heart
     */     
    function heartsCreate($hearts_main_dir,$hearts_index_xml,$title) {
        $uid = substr(getUniqueString(),0,5);
        insertXMLRecord($hearts_index_xml,'heart',array('uid'=>$uid,'title'=>$title,'counter'=>'0'));
        createXMLdb($hearts_main_dir.'hearts_data_'.$uid);        
    }


    /**
     * Delete heart
     */
    function heartsDelete($hearts_main_dir,$hearts_index_xml,$heart_id) {
        $heart = selectXMLRecord($hearts_index_xml,'heart[@id="'.$heart_id.'"]','all');        
        deleteFile($hearts_main_dir.'hearts_data_'.$heart[0]->uid.'.xml');
        deleteXMLRecord($hearts_index_xml,'heart',$heart_id);        
    }


    /**
     * Edit heart title
     */
    function heartsEditTitle($hearts_main_dir,$hearts_index_xml,$title,$id) {
        updateXMLRecordWhere($hearts_index_xml,'heart[@id="'.$id.'"]',array('title'=>$title));
    }