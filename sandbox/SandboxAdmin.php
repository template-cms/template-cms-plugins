<?php

    // Add hooks NAVIGATION
    addHook('admin_main_navigation','adminNavigation',array('sandbox',lang('sandbox_name')));
    addHook('admin_pages_second_navigation','adminSecondNavigation',array('pages',lang('sandbox_name'),'sandbox'));

    // Add some other hooks
    addHook('admin_headers','sandboxHeaders');


    /**
     * Add some headers to admin
     */
    function sandboxHeaders() {
        // some headers to admin...
    }

    /**
     * Sandbox admin
     */
    function sandboxAdmin() {

        // Check for get actions
        if (isGet('action')) {
            // Switch actions
            switch (get('action')) {
                case "show":

                    include 'templates/Template.php';
                    break;
                case "close":

                    include 'templates/Template.php';
                    break;
            }
            // Its mean that you can add your own actions for this plugin
            runHook('admin_sandbox_extra_actions');
        } else { // Load main template
            include 'templates/SandboxTemplate.php';
        }
    }