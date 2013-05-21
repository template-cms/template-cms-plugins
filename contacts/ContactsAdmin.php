<?php


    // Admin hooks
    addHook('admin_system_extra_template_actions','contactFormInput');
    addHook('admin_system_extra_actions','contactFormInputSave');

    // Add hooks to setup contact form template
    addHook('admin_themes_extra_template_actions','contactFormComponent');
    addHook('admin_themes_extra_actions','contactFormComponentSave');


    /**
     * Contact form save action.
     * This action will add to hook: admin_system_extra_actions
     */
    function contactFormInputSave() {
        if(isPost('contacts_save')) {
            // Prepare content before saving
            $content = '<?xml version="1.0" encoding="UTF-8"?>';
            $content .= '<item>';
            $content .= '<contact>'.post('contacts_contact').'</contact>';
            $content .= '</item>';

            createFile('../'.TEMPLATE_CMS_DATA_PATH.'system/contact.xml',$content);
            redirect('index.php?id=system');
        }
    }

    /**
     * Contact form admin template.
     * This template will add to hook: admin_system_extra_template_actions
     */
    function contactFormInput() {

        // For this plugin requires a xml file.
        // If not, then show the link to create a xml file.
        if(file_exists('../'.TEMPLATE_CMS_DATA_PATH.'system/contact.xml')) {
            $system_contact_xml = getXML('../'.TEMPLATE_CMS_DATA_PATH.'system/contact.xml');
        } else {

            // Prepare content before saving
            $content = '<?xml version="1.0" encoding="UTF-8"?>';
            $content .= '<item>';
            $content .= '<contact></contact>';
            $content .= '</item>';

            createFile('../'.TEMPLATE_CMS_DATA_PATH.'system/contact.xml',$content);
        }

        include 'templates/backend/ContactsTemplate.php';
    }

    /**
     * Contacts form template save
     */
    function contactFormComponentSave() {
        if(isPost('contacts_component_save')) {
            // Prepare content before saving
            $content = '<?xml version="1.0" encoding="UTF-8"?>';
            $content .= '<root>';
            $content .= '<template>'.post('contacts_form_template').'</template>';
            $content .= '</root>';

            createFile('../'.TEMPLATE_CMS_DATA_PATH.'other/contact_template.xml',$content);
            redirect('index.php?id=themes');
        }
    }

    /**
     * Contacts form template
     */
    function contactFormComponent() {
        $current_theme = getSiteTheme(false);        
        $themes_templates = listFiles(TEMPLATE_CMS_THEMES_PATH.$current_theme, 'Template.php');        
        $template_xml = getXML('../'.TEMPLATE_CMS_DATA_PATH.'other/contact_template.xml');

        foreach($themes_templates as $file) $templates[] = basename($file,'Template.php');

        if(isset($template_xml->template)) {
            $template = $template_xml->template;
        } else {
            $template = 'index';
        }

        htmlFormOpen('index.php?id=themes');
        htmlSelect($templates, array('style'=>'width:200px;','name'=>'contacts_form_template'), lang('contacts_form'), $template);
        htmlNbsp();
        htmlFormClose(true, array('value'=>lang('contacts_save'),'name'=>'contacts_component_save'));
    }