<?php

    /**
     *	Files manager plugin
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author El'Drako
     *	@copyright 2011 El'Drako
     *	@version 1.1.1
     *
     */


    // Register plugin
    registerPlugin( getPluginId(__FILE__),
                    getPluginFilename(__FILE__),
                    'FManager',
                    '1.1.1',
                    'File manger for TemplateCMS <a href="index.php?id=pages&sub_id=fmanager">&rarr; admin</a> <a href="../downloads" target="_blank">&rarr; see</a>',
                    'El\'Drako',
                    'http://',
                    'fmanagerAdmin',
					'downloads');

 
    // Get language file for this plugin
    getPluginLanguage('Fmanager');
	
	// Frontend hooks
    addHook('downloads_content','fmanagerContent',array());
    addHook('downloads_template','fmanagerTemplate',array());
    addHook('downloads_title','fmanagerTitle',array());
	
	// Add theme header
    addHook('theme_header','fmanagerThemeHeader');
	addHook('frontend_pre_render', 'fmanagerPreRender');

    // Include Admin
    getPluginAdmin('Fmanager');
	
	/**
     * Files manager headers
     */
    function fmanagerThemeHeader() {
        echo '<link rel="stylesheet" href="'.getSiteUrl(false).'/plugins/fmanager/templates/frontend/css/fmanager.css">';
    }
	
	/**
     * Set FManager template: indexTemplate.php
     */
    function fmanagerTemplate($data) {
        $template_xml = getXML(TEMPLATE_CMS_DATA_PATH.'other/fmanager_template.xml');
        if($template_xml == null) {
            return 'index';
        } else {
            return $template_xml->template;
        }
    }
	
	/**
     * Get FManager title
     */
    function fmanagerTitle($data) {
        echo lang('fmanager_title');
    }
	
	/**
     * Error page
     */
    function fmanagerErrorPage() {
        $pages_xml = getXML(TEMPLATE_CMS_DATA_PATH.'pages/error404.xml');
        echo $pages_xml->content;
    }
	
	/**
     * Get fmanager prerender contents
     */
    function fmanagerPreRender() {
	$uri = getUri();
	if ($uri[0] == 'downloads' && $uri[1]=='category' && $uri[3]=='file') {
		
		$error='';
		
		// Get Site url
		$site_url = getSiteUrl(false);
		
		$ext = '.xml';

		// Files manager folders
		$files_config = TEMPLATE_CMS_DATA_PATH.'files/db/';
		$files_path = TEMPLATE_CMS_DATA_PATH.'files/';
		
		 
		//Creating tree of all categories and files
		$xml_db = getXMLdb($files_config.'fmanager_db'.$ext);
		if($xml_db) {
			$fmanager_records = selectXMLRecord($xml_db,"category_entry",'all');
			$fmanager_records2 = selectXMLRecord($xml_db,"file_entry",'all');
			$category_entries = selectXMLfields($fmanager_records, array('description','category_name','category_img','category_parent','date','id'),'category_name','ASC');
			$files_entries = selectXMLfields($fmanager_records2, array('name','category','filename','published','description','counter','id'),'name','ASC');
        }
		
		//if plugin is disabled
		$s_id = 1;
		$settings = selectXMLRecord($xml_db,"//fmanager_option[@id='".$s_id."']",'all');
		$setting = selectXMLfields($settings, array('enabled'),'enabled','ASC'); 
		if (toText($setting[0]['enabled']) != 'checked') {
			// Display Files manager template
			include 'templates/frontend/FmanagerMainTemplate.php';
			exit;
		}
		
		if(isset($uri[1])) {
            if($uri[1] == 'category') {
                //addres link is valid? 
				if(!isset($uri[2]) || ($uri[2]) =='') {
					//if not - error 404
					fmanagerErrorPage();
                    statusHeader(404);
					exit;
				}
											
				//обработка линков на скачивание
				if(isset($uri[3]) && $uri[3] == 'file') {
					if(isset($uri[4]) && $uri[4] !='') {
						$f_id = (int)$uri[4];
						$records2 = selectXMLRecord($xml_db,"//file_entry[@id='".$f_id."']",'all');
						$file = selectXMLfields($records2, array('name','category','filename','published','tmpname','password','description','counter','id'),'name','ASC');
						
                        $p_id = 1;
                        $params = selectXMLRecord($xml_db,"//fmanager_option[@id='".$p_id."']",'all');
                        $param = selectXMLfields($params, array('enabled','description','directlinks','counter','referer'),'enabled','ASC'); 
						
						// checking referer flag: if it's false - not check
						if (toText($param[0]['referer']) != 'checked') {
                            //counter downloads
							$id = $file[0]['counter'];
							$id = (int)$id + 1;
							updateXMLRecord($xml_db,'file_entry',$f_id, array('counter'=>$id));
							
							// checking directlinks flag: if it's true - use redirect
							if (toText($param[0]['directlinks']) == 'checked' && toText($file[0]['password'])=='') {
								//return redirect
								ob_start();
								header('HTTP/1.1 301 Moved Permanently');
								header('Content-Type: application/octet-stream');
								header("Location: ".$site_url.$files_path.$file[0]['filename']);
								ob_get_clean();
								exit;
							} else {
								$full_referer = $_SERVER['HTTP_REFERER'];
								$referer = parse_url($full_referer);
								$referer = $referer['host'].'admin/index.php?id=pages&sub_id=fmanager';
								$site = parse_url($site_url);
								$site = $site['host'].'admin/index.php?id=pages&sub_id=fmanager';
								if (toText($file[0]['published']) == 'checked') {
									//checking directlinks flag: if it's false - use php for download files
									download_file($site_url.$files_path.$file[0]['filename'], 
											  $mimetype = 'application/octet-stream', true);
									exit;
								} else {
									if ($referer == $site) {
										//checking directlinks flag: if it's false - use php for download files
										download_file($site_url.$files_path.$file[0]['tmpname'], 
											  $mimetype = 'application/octet-stream', true);
										exit;
									}
								}
							}
                        } else {
							if (isset($_SERVER['HTTP_REFERER'])) {	
								$full_referer = $_SERVER['HTTP_REFERER'];
								$referer = parse_url($full_referer);
								$referer = $referer['host'];
								$site = parse_url($site_url);
								$site = $site['host'];
								
								if ($referer==$site) {
									//counter downloads
									$id = $file[0]['counter'];
									$id = (int)$id + 1;
									updateXMLRecord($xml_db,'file_entry',$f_id, array('counter'=>$id));
							
									// checking directlinks flag: if it's true - use redirect
									if (toText($param[0]['directlinks']) == 'checked' && toText($file[0]['password'])=='') {
										//return redirect
										ob_start();
										header('HTTP/1.1 301 Moved Permanently');
										header('Content-Type: application/octet-stream');
										header("Location: ".$site_url.$files_path.$file[0]['filename']);
										ob_get_clean();
										exit;
									} else {
										$full_referer = $_SERVER['HTTP_REFERER'];
										$referer = parse_url($full_referer);
										$referer = $referer['host'].$referer['path']; //'/admin/index.php?id=pages&sub_id=fmanager';
										$site = parse_url($site_url);
										$site = $site['host'].$site['path'].'admin/index.php';
										if (toText($file[0]['published']) == 'checked') {
											//checking directlinks flag: if it's false - use php for download files
                                            download_file($files_path.$file[0]['filename'], 
														$mimetype = 'application/octet-stream', true);

                                            exit;
										} else {
											if ($referer == $site) {
												//checking directlinks flag: if it's false - use php for download files
 
                                                download_file($files_path.$file[0]['tmpname'], 
													$mimetype = 'application/octet-stream', true);
                                                exit;
											} else {
												// Display template
												loadTemplate('themes/'.getSiteTheme(false).'/'.getTemplate().'Template.php');
												//fmanagerErrorPage();
												statusHeader(404);
												exit;
											}
										}
									}
								} else {
									if ($uri[2] == 0) { 
										redirect($site_url.'downloads');
									} else 
										redirect($site_url.'downloads/'.$uri[1].'/'.$uri[2]);
								}
							} else {
								if ($uri[2] == 0) { 
									redirect($site_url.'downloads');
								} else 
									redirect($site_url.'downloads/'.$uri[1].'/'.$uri[2]);
							}
						}
                    }  
				} else {
					//page error404
					//fmanagerErrorPage();
                    //statusHeader(404);
					//exit;
				}
            } else { //root of download list
				redirect($site_url.'downloads');
				//$catlist = lang('fmanager_home');
				//$category_number = 0; //only for root
				//$fileslist = listFMAllFiles($category_entries, $files_entries, 'root', $category_number, $files_path, $site_url, $xml_db);
			}
		} else {
			redirect($site_url.'downloads');
			//$catlist = lang('fmanager_home');
			//$fileslist = listFMAllFiles($category_entries, $files_entries, 'root', 0, $files_path, $site_url, $xml_db);
		}
		exit;
	}
	}
	
	/**
     * Get fmanager contents
     */
    function fmanagerContent($uri) {
	
		$error='';
		
		// Get Site url
		$site_url = getSiteUrl(false);
		
		$ext = '.xml';

		// Files manager folders
		$files_config = TEMPLATE_CMS_DATA_PATH.'files/db/';
		$files_path = TEMPLATE_CMS_DATA_PATH.'files/';
		
		 
		//Creating tree of all categories and files
		$xml_db = getXMLdb($files_config.'fmanager_db'.$ext);
		if($xml_db) {
			$fmanager_records = selectXMLRecord($xml_db,"category_entry",'all');
			$fmanager_records2 = selectXMLRecord($xml_db,"file_entry",'all');
			$category_entries = selectXMLfields($fmanager_records, array('published','description','category_name','category_img','category_parent','date','id'),'category_name','ASC');
			$files_entries = selectXMLfields($fmanager_records2, array('login','password','tmpname','name','category','filename','published','description','counter','id'),'name','ASC');
        }
		
		//if plugin is disabled
		$s_id = 1;
		$settings = selectXMLRecord($xml_db,"//fmanager_option[@id='".$s_id."']",'all');
		$setting = selectXMLfields($settings, array('enabled'),'enabled','ASC'); 
		if (toText($setting[0]['enabled']) != 'checked') {
			// Display Files manager template
			include 'templates/frontend/FmanagerMainTemplate.php';
			exit;
		}
		
		if(isset($uri[1])) {
            if($uri[1] == 'category') {
                //addres link is valid? 
				if(!isset($uri[2]) || ($uri[2]) =='') {
					//if not - error 404
					fmanagerErrorPage();
                    statusHeader(404);
					exit;
				}
				
				$id = (int)$uri[2];
				$records = selectXMLRecord($xml_db,"//category_entry[@id='".$id."']",'all');
				$category = selectXMLfields($records, array('category_name','category_parent'),'category_name','ASC');
				$current_category = toText($category[0]['category_name']);
				$parent_category = toText($category[0]['category_parent']);
				
				//creating navigation in $catlist
				if ($current_category =='') {
					$catlist = lang('fmanager_home');
				} else {
					$catlist = getNav ($xml_db, $current_category).'<a href="'.getSiteUrl(false).'downloads/category/'.toText($category[0]['id']).'">'.$current_category.'</a>';
				}
				if ($current_category=='') {
					$records = selectXMLRecord($xml_db,"//category_entry[category_parent='root']",'all');
				} else {
					$records = selectXMLRecord($xml_db,"//category_entry[category_parent='".$current_category."']",'all');
				}
				$category = selectXMLfields($records, array('published','description','category_name','category_img','category_parent','date','id'),'category_name','ASC');
				
				$records2 = selectXMLRecord($xml_db,"//file_entry[category='".$current_category."']",'all');
				$files = selectXMLfields($records2, array('tmpname','password','login','name','category','filename','published','description','counter','id'),'name','ASC');
				
				$fileslist = listFMAllFiles($category, $files, 
											$current_category,
											$id,
											$files_path,
											$site_url,
											$xml_db);
            } else { //root of download list
				$catlist = lang('fmanager_home');
				$category_number = 0; //only for root
				$fileslist = listFMAllFiles($category_entries, $files_entries, 'root', $category_number, $files_path, $site_url, $xml_db);
			}
		} else {
			$catlist = lang('fmanager_home');
			$fileslist = listFMAllFiles($category_entries, $files_entries, 'root', 0, $files_path, $site_url, $xml_db);
		}
		
		// Display Files manager template
        include 'templates/frontend/FmanagerMainTemplate.php';
	}
	
	//navigation bar
	function getParentNavItems ($xml_db, $current) {
		$navrecords = selectXMLRecord($xml_db,"//category_entry[category_name='".$current."']",'all');
		$navcategory = selectXMLfields($navrecords, array('category_name','category_parent'),'category_name','ASC');
		$parent = toText($navcategory[0]['category_parent']);
		return $parent;
	}
	
	function getParentNavItemsId ($xml_db, $current) {
		$navrecords = selectXMLRecord($xml_db,"//category_entry[category_name='".$current."']",'all');
		$navcategory = selectXMLfields($navrecords, array('category_name','category_parent'),'category_name','ASC');
		$parent = toText($navcategory[0]['id']);
		return $parent;
	}
	
	function getNav ($xml_db, $current) {
		$out ='';
		if ($current != 'root') {
			$tmp = getParentNavItems($xml_db, $current);
			do {
				if ($tmp != 'root') {
					$id = getParentNavItemsId($xml_db, $tmp);
					$out = '<a href="'.getSiteUrl(false).'downloads/category/'.$id.'">'.$tmp.'</a>'.lang('fmanager_navseparator').$out;
				} else {
					$out = '<a href="'.getSiteUrl(false).'downloads">'.lang('fmanager_home').'</a>'.lang('fmanager_navseparator').$out;
				    break;
                }
				$tmp = ''.getParentNavItems ($xml_db, $tmp);
			}
			while ( true );
		} else {
			$out = '<a href="'.getSiteUrl(false).'downloads">'.lang('fmanager_home').'</a>';
		}
		return $out;
	}
	
	/**
      * Get file and folder list for output
      * @param array $category_entries List of categories
	  * @param array $files_entries List of files
      * @param string $root Root category for creating list
	  * @param integer $category_number Id of category for current item on list
	  * @param string $files_path Path to files
	  * @param string $site_url Site url
	  * @param array $xml_db DB Array
      * @return string HTML list with folders and files
      */
    function listFMAllFiles($category_entries, $files_entries, $root, $category_number, $files_path, $site_url, $xml_db) {
		//$category_number = 0; //only for root
		$fileslist = listFMCategory ($category_entries, $root, $site_url, $xml_db);
		$fileslist = $fileslist.listFMCatFiles($root, $category_number, $files_entries, $files_path, $site_url, $xml_db);
		return $fileslist;
	}
	
	/**
      * Get list of files for selected category
      * @param string $category_entrie Category for creating list
	  * @param integer $category_number Id category in DB
	  * @param array $files_entries List of files
	  * @param string $files_path Path to files
	  * @param string $site_url Site url
	  * @param array $xml_db DB Array
      * @return string list of categories
      */
    function listFMCatFiles($category_entrie, $category_number, $files_entries, $files_path, $site_url, $xml_db) {
		$out = '';	
		$s_id = 1;
		$settings = selectXMLRecord($xml_db,"//fmanager_option[@id='".$s_id."']",'all');
		$setting = selectXMLfields($settings, array('enabled','description','directlinks','counter','referer'),'enabled','ASC'); 
		if (toText($setting[0]['description']) == 'checked') $s_description = true; else	$s_description = false;
		if (toText($setting[0]['counter']) == 'checked') $s_counter = true; else	$s_counter = false;
		
		if (!$files_entries=='') foreach ($files_entries as $entry) {
			if (toText($entry['published'])=='checked' && toText($entry['category'])==$category_entrie) {
				$out = $out.'<table class="filesmanager">';
				$f_pass = toText($entry['password']);
				if ($f_pass!='') {
					$icons = $site_url.'plugins/fmanager/templates/frontend/images/icons/lock.png';
					$ilock = '<img alt="lock" src="'.$icons.'">';
				} else {
					$ilock = '';
				}
				$icon_ext = fileExt($files_path.$entry['filename']);
				$icon = strtoupper($icon_ext).'.png';
				if (!FileExists('plugins/fmanager/templates/frontend/images/icons/'.$icon)) $icon = 'DEFAULT.png';
				$icons = $site_url.'/plugins/fmanager/templates/frontend/images/icons/'.$icon;
				$out = $out.'<tr class="filesmanager-tr"><td class="filesmanager-td-icons"><div><div class="icon"><img class="icons" src="'.$icons.'" alt="'.$icon_ext.'"></div><div class="clearer"></div><div class="locked">'.$ilock.'</div></div></td>'; //show file icons
				
				$f_count = toText($entry['counter']);
				if ($f_count == '') $f_count = 0;
				
				if ($s_description) {
					$s_desc = '<small>'.$entry['description'].'</small>';
				} else $s_desc = '';
				
				if ($s_counter) {
					$s_count = '<br><small>'.lang('fmanager_counter').$f_count.'</small><br>';
				} else $s_count = '<br>';
				
				$out = $out.'<td class="filesmanager-td">
							<strong>'.$entry['name'].'</strong><br><span class="filesize"> ('.convert(filesize($files_path.$entry['tmpname'])).', '.dateFormat(fileLastChange($files_path.$entry['tmpname'])).')</span>'.$s_count.$s_desc.'</td>';
				$out = $out."<td  class=\"filesmanager-td-btn\">";			
				$out = $out.'<span class="btn-edit"><a href="'.$site_url.'downloads/category/'.$category_number.'/file/'.$entry['id'].'" title="'.lang('fmanager_download').'" >'.lang('fmanager_download').'</a></span>';
				$out = $out."</td>"; 
				$out = $out."</tr></table>";
			}
		}
		return $out;
	}
	
	/**
      * Get list of categories for output
      * @param array $category_entries List of categories
      * @param string $root Root category for creating list
	  * @param string $site_url Site url
	  * @param array $xml_db DB Array
      * @return string list of categories
      */
    function listFMCategory ($category_entries, $category_entrie='root', $site_url, $xml_db) {
		$out = '';	
		$s_id = 1;
		$settings = selectXMLRecord($xml_db,"//fmanager_option[@id='".$s_id."']",'all');
		$setting = selectXMLfields($settings, array('elements'),'enabled','ASC'); 
		if (toText($setting[0]['elements']) == 'checked') {
			$s_elements = true;
		} else {
			$elements = '';
			$s_elements = false;
		}
		
		if (!$category_entries=='') foreach ($category_entries as $entry) {
			if (toText($entry['category_parent'])==$category_entrie) {
				if (toText($entry['published'])!='checked') continue;
				if ($s_elements) {
					$recs = selectXMLRecord($xml_db,"//file_entry[category='".toText($entry['category_name'])."']",'all');
					$recs2 = selectXMLRecord($xml_db,"//category_entry[category_parent='".toText($entry['category_name'])."']",'all');
					$elements = lang('fmanager_category_elements').count($recs2);
					$elements = $elements.', '.lang('fmanager_file_elements').count($recs);
					$elements = '<span class="filesize">'.$elements.'</span><br>';
				}
				$out = $out.'<table class="filesmanager">';
				$icon = 'FOLDER.png';
				$icons = $site_url.'/plugins/fmanager/templates/frontend/images/icons/'.$icon;
				if (toText($entry['category_img']!='')) {
					$icons = toText($entry['category_img']);
				}
				
				$out = $out.'<tr class="filesmanager-tr"><td class="filesmanager-td-icons"><img class="icons" src="'.$icons.'" alt="'.$icon.'"></td>'; //show folder icon
				$out = $out.'<td class="filesmanager-td">
							<strong><a href="'.$site_url.'downloads/category/'.$entry['id'].'">'.$entry['category_name'].'</a></strong><br>
							'.$elements.'<small>'.$entry['description'].'</small>
						</td>';
				$out = $out."</tr></table>";
			}
		}
		return $out;
	}
	
	function authenticate() {
		header('WWW-Authenticate: Basic realm="'.lang('fmanager_file_pass_on').'"');
		header('HTTP/1.0 401 Unauthorized');
		echo lang('fmanager_file_wrongpass');
		exit;
	}
	// $filepath � ���� � �����, ������� �� ����� ������
	// $mimetype � ��� ���������� ������ (����� �� ������)
	// $retry � ��������� �������
	function download_file($filepath, $mimetype = 'application/octet-stream', $retry=true) {
        
		
		$filename = basename($filepath);
		$dir = dirname($filepath);
		// Get Site url
		$site_url = getSiteUrl(false);
		
		$ext = '.xml';

		// Files manager folders
		$files_config = TEMPLATE_CMS_DATA_PATH.'files/db/';
		$files_path = TEMPLATE_CMS_DATA_PATH.'files/';
		
		 
		//Creating tree of all categories and files
		$xml_db = getXMLdb($files_config.'fmanager_db'.$ext);
		$records = selectXMLRecord($xml_db,"//file_entry[filename='".$filename."']",'all');
		$file = selectXMLfields($records, array('name','category','filename','published','tmpname','login','password','description','counter','id'),'name','ASC');
		if (toText($file[0]['password'])!='') {
			if (($_SERVER['PHP_AUTH_USER'])!=toText($file[0]['login'])) {
				authenticate();
			} 
			$user = $_SERVER['PHP_AUTH_USER'];
			$pass = $_SERVER['PHP_AUTH_PW'];
			$hash = md5(toText($file[0]['filename']).'_'.md5($pass));
			if ($hash!=toText($file[0]['password'])) {
				statusHeader(403);
				session_destroy();
				// Display template
				loadTemplate('themes/'.getSiteTheme(false).'/'.getTemplate().'Template.php');
				exit;
			}
			$filepath = $dir.'/'.toText($file[0]['tmpname']);
		}
		if(!FileExists($filepath) ) {
			//error 404
			fmanagerErrorPage();
            statusHeader(404);
			exit;
		}
		
		$fsize = filesize($filepath); // ����� ������ �����
		$ftime = date('D, d M Y H:i:s T', filemtime($filepath)); // ���������� ���� ��� �����������

		$fd = @fopen($filepath, 'rb'); // ��������� ���� �� ������ � �������� ������

		if (isset($_SERVER['HTTP_RANGE']) && $retry) { // �������������� �� �������?
			$range = $_SERVER['HTTP_RANGE']; // ����������, � ������ ����� ��������� ����
			$range = str_replace('bytes=', '', $range);
			list($range, $end) = explode('-', $range);

			if (!empty($range)) {
				fseek($fd, $range);
			}
		} else { // ������� �� ��������������
			$range = 0;
		}

		if ($range) {
			header($_SERVER['SERVER_PROTOCOL'].' 206 Partial Content'); // ������� ��������, ��� ��� ����� ������-�� ��������
		} else {
			header($_SERVER['SERVER_PROTOCOL'].' 200 OK'); // ����������� ����� ��������
		}

		// ������ ���������, ����������� ��� ���������� ������
		header('Content-Disposition: attachment; filename='.toText($file[0]['filename']));
		header('Last-Modified: '.$ftime);
		header('Accept-Ranges: bytes');
		header('Content-Length: '.($fsize - $range));
		if ($range) {
			header("Content-Range: bytes $range-".($fsize - 1).'/'.$fsize);
		}
		header('Content-Type: '.$mimetype);

		fpassthru($fd); // ������ ����� ����� � ������� (��������� �������)
		//echo fread($fd, $fsize);
        fclose($fd);
		exit;
	}