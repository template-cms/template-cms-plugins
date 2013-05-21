<?php


    // Add hooks NAVIGATION
    addHook('admin_pages_second_navigation','adminSecondNavigation',array('pages',lang('guestbook_submenu'),'guestbook'));
	
    // Add hooks to setup contact form template
    addHook('admin_themes_extra_template_actions','guestbookFormComponent');
    addHook('admin_themes_extra_actions','guestbookFormComponentSave');


    /**
     * Guestbook admin
     */
    function guestbookAdmin() {

        // Get comments xml database
        $xml_db = getXMLdb('../'.TEMPLATE_CMS_DATA_PATH.'/other/guestbook.xml');


        $comments = array();

        // Select comments records from database
        if($xml_db) {
            $comments = selectXMLRecord($xml_db, "comment", 'all');
        }


        // Check for get actions
        if (isGet('action')) {
            // Switch actions
            switch (get('action')) {
                // Delete comment
                case "delete_comment":
                    deleteXMLRecord($xml_db,'comment',get('delete'));
                    redirect('index.php?id=pages&sub_id=guestbook');
                    break;
            }
            // Its mean that you can add your own actions for this plugin
            runHook('admin_guestbook_extra_actions');
        } else {

            // If the database does not exist, create it
            if(!file_exists('../'.TEMPLATE_CMS_DATA_PATH.'/other/guestbook.xml')) {
                createXMLdb('../'.TEMPLATE_CMS_DATA_PATH.'/other/guestbook');
            }

            // Include admin template
            include 'templates/backend/GuestbookAdminTemplate.php';
        }
    }

    /**
     * Guestbook form template save
     */
    function guestbookFormComponentSave() {
        if(isPost('guestbook_component_save')) {
            // Prepare content before saving
            $content = '<?xml version="1.0" encoding="UTF-8"?>';
            $content .= '<root>';
            $content .= '<template>'.post('guestbook_form_template').'</template>';
            $content .= '</root>';

            createFile('../'.TEMPLATE_CMS_DATA_PATH.'other/guestbook_template.xml',$content);
            redirect('index.php?id=themes');
        }
    }

    /**
     * Guestbook form template
     */
    function guestbookFormComponent() {
        $current_theme = getSiteTheme(false);
        $themes_templates = listFiles(TEMPLATE_CMS_THEMES_PATH.$current_theme, 'Template.php');
        $template_xml = getXML('../'.TEMPLATE_CMS_DATA_PATH.'other/guestbook_template.xml');

        foreach($themes_templates as $file) $templates[] = basename($file,'Template.php');

        htmlFormOpen('index.php?id=themes');
        htmlSelect($templates, array('style'=>'width:200px;','name'=>'guestbook_form_template'), lang('guestbook_title'), $template_xml->template);
        htmlNbsp();
        htmlFormClose(true, array('value'=>lang('guestbook_save'),'name'=>'guestbook_component_save'));
    }