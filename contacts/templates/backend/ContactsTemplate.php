<?php
    htmlBr(1);
    htmlAdminHeading(lang('contacts_form'));
    htmlFormOpen('index.php?id=system');
    htmlFormInput(array('value'=>$system_contact_xml->contact,'name'=>'contacts_contact'),lang('contacts_contact'));
    htmlBr(2);
    htmlFormClose(true,array('value'=>lang('contacts_save'),'name'=>'contacts_save'));
?>