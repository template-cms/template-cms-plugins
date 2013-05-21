<?php


    // Add hooks
    addHook('admin_pages_second_navigation','adminSecondNavigation',array('pages',lang('fmanager_submenu'),'fmanager'));
    addHook('admin_header','fmanagerHeaders');
	
	// Add hooks to setup Files manager form template
    addHook('admin_themes_extra_template_actions','fmanagerFormComponent');
    addHook('admin_themes_extra_actions','fmanagerFormComponentSave');
	
    /**
     * Files manager headers
     */
    function fmanagerHeaders() {
        echo '<link rel="stylesheet" href="../plugins/fmanager/templates/backend/css/fmanager-admin.css">
<link rel="stylesheet" href="../plugins/fmanager/templates/backend/css/base/jquery-ui-1.8.13.custom.css">

<script type="text/javascript" src="../plugins/fmanager/templates/backend/js/jquery-ui-1.8.14.custom.min.js"></script>
<script type="text/javascript" src="../plugins/fmanager/templates/backend/js/jquery.cookie.js"></script>

<script type="text/javascript">
	$(function() {
		$("#tabs").tabs({
			cookie: {
			// store cookie for a day, without, it would be a session cookie
				expires: 7
			}
		});
		$("#veditor").markItUp();
		$("#veditor2").markItUp();
	});

</script>';
    }
    

    /**
     * Filesmanager admin function
     */
    function fmanagerAdmin() {
		
		$error ='';
		
        // Array of forbidden types
        $forbidden_types = array('php','htaccess','html','htm');        

        // Get Site url
        $site_url = getSiteUrl(false);
		
		$ext = '.xml';

        // Files manager config folder
        $files_config = '../'.TEMPLATE_CMS_DATA_PATH.'files/db/';
        $files_path = '../'.TEMPLATE_CMS_DATA_PATH.'files/';
		
        /**
         * Create folder for Files manager DB in data folder
         * and create simple database for it. 
         */
        if(is_dir($files_path)) {
            
            if(!is_dir($files_config)) {
                mkdir($files_config, 0755);
                createXMLdb($files_config.'fmanager_db');
				$xml_db = getXMLdb($files_config.'fmanager_db'.$ext);
                insertXMLRecord($xml_db, 'fmanager_option', array('enabled'=>'checked',
																  'description'=>'checked',
																  'directlinks'=>'checked',
                                                                  'referer'=>'checked',
																  'elements'=>'checked',
																  'counter'=>'checked'));
            } else {
                if(file_exists($files_config.'fmanager_db'.$ext)) {
                    $xml_db = getXMLdb($files_config.'fmanager_db'.$ext);
                } else {
                    createXMLdb($files_config.'fmanager_db');
                    $xml_db = getXMLdb($files_config.'fmanager_db'.$ext);
					insertXMLRecord($xml_db, 'fmanager_option', array('enabled'=>'checked',
																		'description'=>'checked',
																		'directlinks'=>'checked',
                                                                        'referer'=>'checked',
																		'elements'=>'checked',
																		'counter'=>'checked'));
                }
            }
        } else {
            mkdir($files_path, 0755);
            mkdir($files_config, 0755);
            createXMLdb($files_config.'fmanager_db');
            $htaccess = "Options -Indexes \n
Allow from all \n

RemoveHandler .phtml .php .php3 .php4 .php5 .php6 .phps .cgi .exe .pl .asp .aspx .shtml .shtm .fcgi .fpl .jsp .htm .html .wml \n
AddType application/x-httpd-php-source .phtml .php .php3 .php4 .php5 .php6 .phps .cgi .exe .pl .asp .aspx .shtml .shtm .fcgi .fpl .jsp .htm .html .wml";
            file_put_contents($files_path.'.htaccess', $htaccess);
			$htaccess = "Options -Indexes \n
Deny from all";
			file_put_contents($files_config.'.htaccess', $htaccess);
            $xml_db = getXMLdb($files_config.'fmanager_db'.$ext);
            insertXMLRecord($xml_db, 'fmanager_option', array('enabled'=>'checked',
																  'description'=>'checked',
																  'directlinks'=>'checked',
                                                                  'referer'=>'checked',
																  'elements'=>'checked',
																  'counter'=>'checked'));
        }
		
		/**
         * Get records from Files manager DB
         */
        if($xml_db) {
			$id = 1;
			$records = selectXMLRecord($xml_db,"//fmanager_option[@id='".$id."']",'all');
			$fm_conf_entries = selectXMLfields($records, array('enabled','description','directlinks','counter','elements','referer'),'enabled','ASC'); 
        }
		  
        // Init vars
        $files_list = array();
        $files_list = listFMFiles($xml_db, $files_path);
	
		
        // Delete file
        if(get('sub_id') == 'fmanager') {
            if(get('delete_file')) {
				if(get('unknown')) {
					deleteFile($files_path.get('delete_file'));
					redirect($site_url.'admin/index.php?id=pages&sub_id=fmanager#tabs-2');
				} else {
					if(isset($xml_db)) {
						if($xml_db !== false) {
							$file = (int)get('delete_file');
							$records = selectXMLRecord($xml_db,"//file_entry[@id='".$file."']",'all');
							$f_list = selectXMLfields($records, array('name','filename','category','description','id'),'id','ASC');
							deleteFile($files_path.toText($f_list[0]['filename']));
							deleteXMLRecord($xml_db,'file_entry',get('delete_file'));
							redirect($site_url.'admin/index.php?id=pages&sub_id=fmanager#tabs-2');
						}
					}
				}
            }
        }

        // Upload file
        if(isPost('upload_file')) {
			if(isset($xml_db)) {
				if($xml_db !== false) {
					if ($_FILES['file']) {
						if (!FileExists($files_path.translitIt($_FILES['file']['name']))) {
							if(!in_array(fileExt($_FILES['file']['name']),$forbidden_types)) {
								move_uploaded_file($_FILES['file']['tmp_name'],$files_path.translitIt($_FILES['file']['name']));
								if (FileExists($files_path.translitIt($_FILES['file']['name']))) {
									$f_name = post('fname');
									$f_cat = post('fcategory');
									$f_desc = post('editor');
									$f_show = post('f_show');
									if($f_name == '') {
										$f_name = 'Безымянный файл';
									}
									if($f_cat == '') {
										$f_cat = 'root';
									}
									insertXMLRecord($xml_db, 'file_entry', array('name'=>$f_name,
																				'filename'=>translitIt($_FILES['file']['name']),
																				'category'=>$f_cat,
																				'published'=>$f_show,
																				'description'=>$f_desc));
																		 
									redirect($site_url.'admin/index.php?id=pages&sub_id=fmanager#tabs-2');    
								} else {
									//show error - big file or php restrictions!
									$error = '<div><p class="error"><b>'.lang('fmanager_error_phprestrictions').'</b></p></div>';
								}
							}
						} else {
							// show error - file exists!
							$error = '<div><p class="error"><b>'.lang('fmanager_error_fileexists').'</b></p></div>';
							//$error = htmlMsgWindow('message-error', lang('fmanager_error_fileexists'));
						}
					}
				}
            }
        }
		
		// Save category
        if(isPost('save_category')) {
            if(isset($xml_db)) {
				if($xml_db !== false) {
					$category_name = post('category_name');
                    $category_parent = post('category_parent');
					$category_img = post('category_img');
					
                    if($category_parent == '') {
                        $category_parent = 'root';
                    }
                    insertXMLRecord($xml_db, 'category_entry', array('category_name'=>$category_name,
																		 'category_img'=>$category_img,
                                                                         'category_parent'=>$category_parent,
                                                                         'description'=>post('editor2'),
																		 'published'=>post('category_show'),
                                                                         'date'=>time()));
				}
			}
        }
		
		// Delete category
        if(get('sub_id') == 'fmanager') {
            if(get('delete_category')) {
				if(isset($xml_db)) {
					if($xml_db !== false) {
						deleteXMLRecord($xml_db,'category_entry',get('delete_category'));
						redirect('index.php?id=pages&sub_id=fmanager');
					}
				}
			}
        }
		
		// Set/unset password
        if(get('sub_id') == 'fmanager') {
            if(get('block_file')) {
				$id = (int)get('block_file');
				$records = selectXMLRecord($xml_db,"//file_entry[@id='".$id."']",'all');
				$f_edit = selectXMLfields($records, array('name','description','filename','category','published','id','login','password'),'name','ASC');
				$f_filename = $f_edit[0]['name'].' ('.$f_edit[0]['filename'].')';
				$f_id = $id;
				if ($f_edit[0]['password']!='') {
					$f_block = 'checked="checked"';
				} else {
					$f_block = '';
				}
				$f_loginname = $f_edit[0]['login'];
				include 'templates/backend/FMFileBlockTemplate.php';	
				exit;
			}
        }
		
		// Save Set/unset password
        if(isPost('block_file')) {
            if(isset($xml_db)) {
				if($xml_db !== false) {
					$f_loginname = post('f_loginname');
					$f_pass1 = post('f_pass1');
                    $f_pass2 = post('f_pass2');
					$f_block = post('f_block');
					if ($f_block == 'checked') {
						$f_block = true;
					} else {
						$f_block = false;
					}
					$id = (int)post('id_filename');
					if ($f_block && ($f_pass1 != $f_pass2 || $f_pass1 =='' || $f_pass2=='' || $f_loginname=='')) {
						$error = lang('fmanager_fileblock_errorpass');
						if ($f_loginname =='') $error = $error.'<br>'.lang('fmanager_fileblock_errornologin');
						if ($f_pass1 =='') $error = $error.'<br>'.lang('fmanager_fileblock_errornopass1');
						if ($f_pass2 =='') $error = $error.'<br>'.lang('fmanager_fileblock_errornopass2');
						$f_filename = post('f_filename');
						$f_id = post('id_filename');
						$f_block = post('f_block');
						if ($f_block == 'checked') {
							$f_block = 'checked="checked"';
						} else {
							$f_block = '';
						}
										
						include 'templates/backend/FMFileBlockTemplate.php';	
						exit;
					}
					
					$records = selectXMLRecord($xml_db,"//file_entry[@id='".$id."']",'all');
					$file_entries = selectXMLfields($records, array('filename','published','id','tmpname','login','password'),'id','ASC');
					if ($f_block) {
						BlockFiles ($xml_db, $file_entries, $files_path, $f_loginname, $f_pass1); 
					} else {
						BlockFiles ($xml_db, $file_entries, $files_path, $f_loginname, '');
					}
					//updateXMLRecord($xml_db,'file_entry',$id, array('published'=>'checked'));   
					redirect('index.php?id=pages&sub_id=fmanager#tabs-2');
				}
			}
		}
		
		//Creating tree of all categories
		if($xml_db) {
			$fmanager_records = selectXMLRecord($xml_db,"category_entry",'all');
            $category_entries = selectXMLfields($fmanager_records, array('published','description','category_name','category_parent','date','id'),'category_name','ASC');
        }
		//Переменная для массива всех категорий по алфавиту с учётом иерархии
		$cat_list = array();
		$cat_list[] = 'root';
		foreach (arrayFMCategories ($category_entries) as $item) {
			$cat_list[] = $item;
		}
		
		//Создаём таблицу категорий для вкладки категорий
		$categorylist = listFMCategories ($category_entries);
		
		// Edit category
        if(get('sub_id') == 'fmanager') {
            if(get('edit_category')) {
				$id = (int)get('edit_category');
				$records = selectXMLRecord($xml_db,"//category_entry[@id='".$id."']",'all');
				$category_edit = selectXMLfields($records, array('description','category_name','category_parent','category_img','published','id'),'category_name','ASC');
				include 'templates/backend/FMCatEditTemplate.php';	
				exit;
			}
        }
		
		// Save edited category
        if(isPost('edit_category')) {
            if(isset($xml_db)) {
				if($xml_db !== false) {
					$category_name = post('category_name');
                    $category_parent = post('category_parent');
					$category_pub = post('category_show');
					$category_img = post('category_img');
					if ($category_pub != 'checked') {
						$c_hide = true;
					} else {
						$c_hide = false;
					}
					$id = (int)post('entry_id');
					
					//changing all childs (categories and files) in edited category
					$old_cat = selectXMLRecord($xml_db,"//category_entry[@id='".$id."']",'all');
					$c_old = selectXMLfields($old_cat, array('category_name','category_img','category_parent', 'published','id'),'category_name','ASC');
					
					$c_records = selectXMLRecord($xml_db,"//category_entry[category_parent='".toText($c_old[0]['category_name'])."']",'all');
					$category_edit = selectXMLfields($c_records, array('category_name','category_parent', 'published','id'),'category_name','ASC');
					$f_records = selectXMLRecord($xml_db,"//file_entry[category='".toText($c_old[0]['category_name'])."']",'all');
					$files_edit = selectXMLfields($f_records, array('category','id'),'id','ASC');
					if (count($files_edit) > 0) {
						foreach ($files_edit as $file) {
							if (toText($file['category']) == toText($c_old[0]['category_name'])) {
								updateXMLRecord($xml_db,'file_entry',$file['id'],array('category'=>$category_name));   
							}
						}
					}
					if (count($category_edit) > 0) {
						foreach ($category_edit as $cat) {
							if (toText($cat['category_parent']) == toText($c_old[0]['category_name'])) {
								updateXMLRecord($xml_db,'category_entry',$cat['id'],array('category_parent'=>$category_name));   
							}
						}
					}
					
                    if($category_parent == '') {
                        $category_parent = 'root';
                    }
					
					//unpublish all subcategories with files
					HideCategory($xml_db, $category_name, $c_hide);
					
					//save changes in db
					updateXMLRecord($xml_db,'category_entry',$id,array('category_name'=>$category_name,
																		 'category_img'=>$category_img,
                                                                         'category_parent'=>$category_parent,
                                                                         'description'=>post('editor2'),
                                                                         'date'=>time()));  
					redirect('index.php?id=pages&sub_id=fmanager#tabs-3');
				}
			}
        }
		
		// Show a form to adding info of file
        if(get('sub_id') == 'fmanager') {
            if(get('add_file')) {	
				$f_filename = get('add_file');
				include 'templates/backend/FMFileAddTemplate.php';	
				exit;
			}
        }
		
		// Adding a file
        if(isPost('add_file')) {
			if(isset($xml_db)) {
				if($xml_db !== false) {					
					$f_name = post('f_name');
					$f_cat = post('f_category');
					$f_desc = post('editor2');
					$f_show = post('f_show');
					$f_filename = post('entry_filename');
					if($f_name == '') {
						$f_name = 'Безымянный файл';
					}
					if($f_cat == '') {
						$f_cat = 'root';
					}
					insertXMLRecord($xml_db, 'file_entry', array('name'=>$f_name,
																 'tmpname'=>$f_name,
																 'login'=>'',
																 'password'=>'',
																 'filename'=>$f_filename,
																 'category'=>$f_cat,
																 'published'=>$f_show,
																 'description'=>$f_desc));
																		 
					redirect($site_url.'admin/index.php?id=pages&sub_id=fmanager#tabs-2');                    
				}
            }
        }
		
		// Publish a file
        if(get('sub_id') == 'fmanager') {
            if(get('pub_file')) {
				if(isset($xml_db)) {
					if($xml_db !== false) {
						$id = (int)get('pub_file');
						$records = selectXMLRecord($xml_db,"//file_entry[@id='".$id."']",'all');
						$file_entries = selectXMLfields($records, array('filename','published','id','tmpname','password'),'id','ASC');
						PubFiles($xml_db, $file_entries, $files_path, 'checked'); 
						redirect('index.php?id=pages&sub_id=fmanager#tabs-2');
					}
				}
			}
        }
		
		// Unpublish a file
        if(get('sub_id') == 'fmanager') {
            if(get('unpub_file')) {
				if(isset($xml_db)) {
					if($xml_db !== false) {
						$id = (int)get('unpub_file');
						$records = selectXMLRecord($xml_db,"//file_entry[@id='".$id."']",'all');
						$file_entries = selectXMLfields($records, array('filename','published','id','tmpname','password'),'id','ASC');   
						PubFiles($xml_db, $file_entries, $files_path, '');
						redirect('index.php?id=pages&sub_id=fmanager#tabs-2');
					}
				}
			}
        }
		
		// Edit info of file
        if(get('sub_id') == 'fmanager') {
            if(get('edit_file')) {
				if(isset($xml_db)) {
					if($xml_db !== false) {
						$id = (int)get('edit_file');
						$records = selectXMLRecord($xml_db,"//file_entry[@id='".$id."']",'all');
						$f_edit = selectXMLfields($records, array('description','name','category','filename','published','id'),'id','ASC');
						include 'templates/backend/FMFileEditTemplate.php';	
						exit;
					}
				}
			}
        }
		
		// Show categories
        if(get('sub_id') == 'fmanager') {
            if(get('cshow')) {
				if(isset($xml_db)) {
					if($xml_db !== false) {
						if (get('cshow')=='all') {
							//$records = selectXMLRecord($xml_db,"category_entry",'all');
							HideCategory($xml_db, 'root', false);
						} else {
							$id = (int)get('cshow');
							$records = selectXMLRecord($xml_db,"//category_entry[@id='".$id."']",'all');
							$c_show = selectXMLfields($records, array('category_name','id'),'id','ASC');
							HideCategory($xml_db, $c_show[0]['category_name'], false);
						}
						redirect('index.php?id=pages&sub_id=fmanager#tabs-3');
						exit;
					}
				}
			}
        }
		
		// Hide categories
        if(get('sub_id') == 'fmanager') {
            if(get('chide')) {
				if(isset($xml_db)) {
					if($xml_db !== false) {
						if (get('chide')=='all') {
							//$records = selectXMLRecord($xml_db,"category_entry",'all');
							HideCategory($xml_db, 'root', true);
						} else {
							$id = (int)get('chide');
							$records = selectXMLRecord($xml_db,"//category_entry[@id='".$id."']",'all');
							$c_show = selectXMLfields($records, array('category_name','id'),'id','ASC');
							HideCategory($xml_db, $c_show[0]['category_name'], true);
						}
						redirect('index.php?id=pages&sub_id=fmanager#tabs-3');
						exit;
					}
				}
			}
        }
		
		// Save edited fileinfo
        if(isPost('edit_file')) {
            if(isset($xml_db)) {
				if($xml_db !== false) {
					$f_name = post('f_name');
					$f_cat = post('f_category');
					$f_show = post('f_show');
					if($f_name == '') {
						$f_name = 'Безымянный файл';
					}
					if($f_cat == '') {
						$f_cat = 'root';
					}
					
					$id = (int)post('entry_id');
					updateXMLRecord($xml_db,'file_entry',$id, array('name'=>$f_name,
                                                                    'category'=>$f_cat,
																	'filename'=>post('entry_filename'),
																	'published'=>$f_show,
                                                                    'description'=>post('editor2')));   
					redirect('index.php?id=pages&sub_id=fmanager#tabs-2');
				}
			}
        }
		
		// Save plugin settings
        if(isPost('save_settings')) {
            if(isset($xml_db)) {
				if($xml_db !== false) {
					$id = 1;
					$fm_on = post('fmanager_enabled');
					if ($fm_on == '') $fm_on = 'no';
					$fm_desc = post('fmanager_description');
					if ($fm_desc == '') $fm_desc = 'no';
					$fm_directlinks = post('fmanager_directlinks');
					if ($fm_directlinks == '') $fm_directlinks = 'no';
					$fm_counter = post('fmanager_counter');
					if ($fm_counter == '') $fm_counter = 'no';
					$fm_referer = post('fmanager_referer');
					if ($fm_referer == '') $fm_referer = 'no';
					$fm_elements = post('fmanager_elements');
					if ($fm_elements == '') $fm_elements = 'no';
					updateXMLRecord($xml_db,'fmanager_option',$id,array('enabled'=>$fm_on,
                                                                        'description'=>$fm_desc,
																		'directlinks'=>$fm_directlinks,
																		'referer'=>$fm_referer,
																		'elements'=>$fm_elements,
																		'counter'=>$fm_counter));   
					//modify .htaccess - set "deny from all" if $fm_directlinks = 'no' 													
					if ($fm_directlinks != 'checked') {
						$htaccess = "Options -Indexes \n
Deny from all\n

RemoveHandler .phtml .php .php3 .php4 .php5 .php6 .phps .cgi .exe .pl .asp .aspx .shtml .shtm .fcgi .fpl .jsp .htm .html .wml \n
AddType application/x-httpd-php-source .phtml .php .php3 .php4 .php5 .php6 .phps .cgi .exe .pl .asp .aspx .shtml .shtm .fcgi .fpl .jsp .htm .html .wml";
						file_put_contents($files_path.'.htaccess', $htaccess);
					} else {
						$htaccess = "Options -Indexes \n
Allow from all \n

RemoveHandler .phtml .php .php3 .php4 .php5 .php6 .phps .cgi .exe .pl .asp .aspx .shtml .shtm .fcgi .fpl .jsp .htm .html .wml \n
AddType application/x-httpd-php-source .phtml .php .php3 .php4 .php5 .php6 .phps .cgi .exe .pl .asp .aspx .shtml .shtm .fcgi .fpl .jsp .htm .html .wml";
						file_put_contents($files_path.'.htaccess', $htaccess);
					}
					redirect('index.php?id=pages&sub_id=fmanager');
				}
			}
        }
		
        // Display Files manager template
        include 'templates/backend/FmanagerTemplate.php';
    }
	
	/**
      * Hide or show category with subcategories and all files
	  * @param array $xml_db XML DB
	  * @param string $category_name Name of category
      * @param boolean $hiding True - hide category (optional)
      * @return none
     **/
	function HideCategory ($xml_db, $category_name, $hiding=true) {
		//unpublish all subcategories with files
		
			if($xml_db) {
				$fmanager_records = selectXMLRecord($xml_db,"category_entry",'all');
				$category_entries = selectXMLfields($fmanager_records, array('published','description','category_name','category_parent','date','id'),'category_name','ASC');
			}
			//Переменная для массива всех категорий по алфавиту с учётом иерархии
			$subcats = arrayFMCategories($category_entries, $category_name);
			$subcats[] = $category_name;

			foreach ($subcats as $entry) {
                if ($entry=='root') {
                    $cat_records = selectXMLRecord($xml_db,"//category_entry[category_parent='".$entry."']",'all');
                 } else {
				    $cat_records = selectXMLRecord($xml_db,"//category_entry[category_name='".$entry."']",'all');
				}
                $c_list = selectXMLfields($cat_records, array('category_name','published','id'),'id','ASC'); 
				if ($hiding) {
					updateXMLRecord($xml_db,'category_entry',$c_list[0]['id'],array('published'=>''));
				} else {
					updateXMLRecord($xml_db,'category_entry',$c_list[0]['id'],array('published'=>'checked'));
				}
				$records = selectXMLRecord($xml_db,"//file_entry[category='".$entry."']",'all');	
				$f_list = selectXMLfields($records, array('name','filename','category','published','id'),'id','ASC');	
				//скрываем все файлы для скрытой категории
				if (count($f_list) > 0) {
					foreach ($f_list as $f_entry) {
						//save changes in db
						if ($hiding) {
							updateXMLRecord($xml_db,'file_entry',$f_entry['id'],array('published'=>''));
						} else {
							updateXMLRecord($xml_db,'file_entry',$f_entry['id'],array('published'=>'checked'));
						}
					}
				}
			}
		
		
		
	}
	
	/**
      * Pub/unpub files (renaming)
	  * @param array $xml_db XML DB
      * @param array $file_entries List of files
	  * @param string $files_path Path to files
	  * @param string $published Flag for show|hide
      * @return none
      */
	function PubFiles ($xml_db, $file_entries, $files_path, $published='') {
		$r_id = rand();
		foreach ($file_entries as $entry) {
			$oldfilename = $entry['filename'];
			$newfilename = md5($r_id.'_'.$oldfilename).'_'.$oldfilename;
			if ($published=='checked' && $entry['password']=='') {
				$oldfilename = $entry['tmpname'];
				$newfilename = $entry['filename'];
			}
			$id = toText($entry['id']);
			if (rename($files_path.$oldfilename, $files_path.$newfilename)) {
				updateXMLRecord($xml_db,'file_entry',$id, array('published'=>$published, 'tmpname'=>$newfilename));
			}
			if ($entry['password']!='') {
				updateXMLRecord($xml_db,'file_entry',$id, array('published'=>$published));
			}
		}
	}
	
	/**
      * Blocking files (renaming)
	  * @param array $xml_db XML DB
      * @param array $file_entries List of files
	  * @param string $files_path Path to files
	  * @param string $login Login for download
      * @param string $password Password for download (optional)
      * @return none
      */
	function BlockFiles ($xml_db, $file_entries, $files_path, $login, $password='') {
		$r_id = rand();    
		foreach ($file_entries as $entry) {
		$id = toText($entry['id']);
			if ($password=='') { //снимаем пароль
				$oldfilename = $entry['filename'];
				if ($entry['filename']!=$entry['tmpname']) $oldfilename = $entry['tmpname'];
				if ($entry['published']!='checked') { //если файл скрыт, то просто снимаем пароль
					$hash='';
					updateXMLRecord($xml_db,'file_entry',$id, array('password'=>$hash));
				} else { //если файл с паролем виден на сайте + переименование
					$oldfilename = $entry['tmpname'];
					$newfilename = $entry['filename'];
					$hash='';
					if (rename($files_path.$oldfilename, $files_path.$newfilename)) {
						updateXMLRecord($xml_db,'file_entry',$id, array('password'=>$hash, 'tmpname'=>$newfilename));
					}
				}
			} else { //ставим пароль
				if ($entry['published']!='checked') { //если файл скрыт, то просто ставим пароль
					$oldfilename = $entry['filename'];
					$hash = md5($oldfilename.'_'.md5($password));
					updateXMLRecord($xml_db,'file_entry',$id, array('login'=>$login,'password'=>$hash));
				} else { //иначе ещё и переименовываем
                    $oldfilename = $entry['filename']; 
					$hash = md5($oldfilename.'_'.md5($password));
					$newfilename = md5($r_id.'_'.$oldfilename).'_'.$oldfilename;
					if (rename($files_path.$oldfilename, $files_path.$newfilename)) {
						updateXMLRecord($xml_db,'file_entry',$id, array('login'=>$login, 'password'=>$hash, 'tmpname'=>$newfilename));
					}
					if ($entry['password']!='') {
						updateXMLRecord($xml_db,'file_entry',$id, array('login'=>$login, 'password'=>$hash));
					}
				}
			}
		}
	}
		
	/**
      * Get list of categories as array
      * @param array $category_entries List of categories
      * @param string $root Root category for creating list
	  * @param integer $level Level for current item of list
      * @return array list of categories
      */
    function arrayFMCategories($category_entries, $root='root', $level=1) {
		$out = array();		
		$catroot = array();
		if (count($category_entries) != 0) {
			//получаем все корневые котегории
            foreach ($category_entries as $rootentry) {
				if ($rootentry['category_parent'] == $root) {
					$catroot[] = $rootentry;
				} 
			}
			foreach ($catroot as $rootentry) {
				$out[] = $rootentry['category_name'];
				if ($rootentry['category_parent'] == $root) {
					$rt = $rootentry['category_name'];
                    settype($rt, "string");
                    foreach ($category_entries as $subentry) {
                        $st = $subentry['category_parent']; 
                        settype($st, "string"); 
                        if ($rt === $st) {
							$level = $level+1;
							if ($level > 20 ) exit;
							foreach (arrayFMCategories($category_entries, $subentry['category_name'], $level) as $item) {
								$out[] = $item;
							}
							$level = 1;
                        }   
                    }
				} 
			}
		}
		return $out;
	}
	
	/**
      * Get list of categories for output
      * @param array $category_entries List of categories
      * @param string $root Root category for creating list
	  * @param integer $level Level for current item of list
      * @return string list of categories
      */
    function listFMCategories($category_entries, $root='root', $level=1) {
		$out = '';
		$catroot = array();
		if (count($category_entries) != 0) {
			//получаем все корневые котегории
            foreach ($category_entries as $rootentry) {
				if ($rootentry['category_parent'] == $root) {
					$catroot[] = $rootentry;
				} 
			}	
			$w = 0;
			for ($x=0; $x<$level; $x++) $w = $w + 20;
			foreach ($catroot as $rootentry) {
				$c_pub = '';
				if ($rootentry['published']!='checked') {
					$c_pub = 'style="background:#eee;"';
					$c_btnhide = '<span class="btn-edit"><a href="index.php?id=pages&sub_id=fmanager&cshow='.$rootentry['id'].'" title="'.lang('fmanager_category_show').'" >'.lang('fmanager_category_show').'</a></span>';
				} else {
					$c_pub = '';
					$c_btnhide = '<span class="btn-edit"><a href="index.php?id=pages&sub_id=fmanager&chide='.$rootentry['id'].'" title="'.lang('fmanager_category_hide').'" >'.lang('fmanager_category_hide').'</a></span>';
				}
				$out = $out.'<table border="0" cellspacing="3" cellpadding="3" width="100%" >';
				$out = $out.'<tr class="filesmanager-tr" '.$c_pub.'><td width="'.$w.'px"></td>';
				$out = $out.'<td valign="top" class="filesmanager-td" width="600px">
								<p><strong>'.$rootentry['category_name'].'</strong><span class="filesize"> ('.dateFormat($rootentry['date']).')</span></p><span class="filesize">Предок: '.$rootentry['category_parent'].'</span><br>
								<small>'.$rootentry['description'].'</small>
							</td>';
				$out = $out."<td  class=\"filesmanager-td\" style=\"text-align:right;width:200px;\">".$c_btnhide;		
				$out = $out.'<span class="btn-edit"><a href="index.php?id=pages&sub_id=fmanager&edit_category='.$rootentry['id'].'" title="'.lang('fmanager_edit').'" >'.lang('fmanager_edit').'</a></span>';
				$out = $out.'<span class="btn-delete"><a href="index.php?id=pages&sub_id=fmanager&delete_category='.$rootentry['id'].'" title="'.lang('fmanager_delete').'" onclick="return confirmDelete(\''.lang('fmanager_delete').'\')">'.lang('fmanager_delete').'</a></span>';
				$out = $out."</td>";
				if ($rootentry['category_parent'] == $root) {
					$rt = toText($rootentry['category_name']);
                    foreach ($category_entries as $subentry) {
                        $st = toText($subentry['category_parent']); 
                        if ($rt == $st) {
							if ($level > 20 ) exit;
                            $out = $out.listFMCategories($category_entries, $subentry['category_name'], ++$level);
							$level--;
                        }   
                    }
				} 
                $out = $out."</tr></table>";
			}
		}
		return $out;
	}

     /**
      * Get list of files in directory (1 level)
	  * @param array $xml_db XML DB
      * @param string $dir Directory to scan
      * @param mixed $type Files types
      * @return boolean
      */
     function listFMFiles($xml_db, $dir, $type=null) {
        $files = array();
		$hidden = array();
		$hidden_files = array();
        if(is_dir($dir)) {
            $dir = opendir ($dir);
			//get hidden filelist
			$records = selectXMLRecord($xml_db,"//file_entry",'all');
			$f_list = selectXMLfields($records, array('name','filename','category','published','description','id','counter','tmpname','password'),'id','ASC');
			foreach ($f_list as $item) {
				if ((toText($item['tmpname']) != toText($item['filename'])) && toText($item['tmpname']) != '') {
					$hidden[] = $item['tmpname'];
					$hidden_files[] = $item['filename'];
				}
			}
			
			//creating list of all files
            while (false !== ($file = readdir($dir))) {                
                if(is_array($type)) {    
                    $file_ext = substr(strrchr($file, '.'), 1);                                     
                    if(in_array($file_ext, $type)) {
                        if (strpos($file, $file_ext, 1)) { 
                            $files[] = $file;
                        }                          
                    }
                } else {
                    if(($file !=".") && ($file !="..") && ($file !="db") && !in_array($file, $hidden)) {
                        if(isset($type)) {
                            if (strpos($file, $type, 1)) {
                                $files[] = $file;
                            }
                        } else {
                            $files[] = $file;
                        }
                    } 
                }
            }
			foreach ($hidden_files as $item) {
				$files[] = $item;
			}
            closedir($dir);
            return $files;
        } else {
            return false;
        }
     }

	/**
	* Fmanager form template save
	*/
	function fmanagerFormComponentSave() {
		if(isPost('fmanager_component_save')) {
			// Prepare content before saving
			$content = '<?xml version="1.0" encoding="UTF-8"?>';
			$content .= '<root>';
			$content .= '<template>'.post('fmanager_form_template').'</template>';
			$content .= '</root>';
			
			createFile('../'.TEMPLATE_CMS_DATA_PATH.'other/fmanager_template.xml',$content);
			redirect('index.php?id=themes');
		}
	}
	
	/**
     * Fmanager form template
     */
    function fmanagerFormComponent() {
        $current_theme = getSiteTheme(false);
        $themes_templates = listFiles(TEMPLATE_CMS_THEMES_PATH.$current_theme, 'Template.php');
        $template_xml = getXML('../'.TEMPLATE_CMS_DATA_PATH.'other/fmanager_template.xml');

        foreach($themes_templates as $file) $templates[] = basename($file,'Template.php');

        htmlFormOpen('index.php?id=themes');
        htmlSelect($templates, array('style'=>'width:200px;','name'=>'fmanager_form_template'), lang('fmanager_plugname'), $template_xml->template);
        htmlNbsp();
        htmlFormClose(true, array('value'=>lang('fmanager_save_options'),'name'=>'fmanager_component_save'));
    }