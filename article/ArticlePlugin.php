<?php

    /**
     *  Article plugin
     *  @package TemplateCMS
     *  @subpackage Plugins
     *  @author Yudin Evgeniy / JEEN
     *  @copyright 2012 Yudin Evgeniy / JEEN
     *  @version 2.2.1
     *
     */

    // Register plugin
    registerPlugin(getPluginId(__FILE__),
                   getPluginFilename(__FILE__),
                   'Article',
                   '2.2.1',
                   'Article plugin. <a href="index.php?id=pages&sub_id=article">&rarr; admin</a> <a href="../article" target="_blank">&rarr; see</a>',
                   'JEEN',
                   'http://lovetcms.ru/',
                   'articleAdmin',
                   'article');

    // Get language file for this plugin
    getPluginLanguage('Article');

    // Include Article Admin
    getPluginAdmin('Article');
    
    // Frontend hooks
    addHook('article_content','articleContent');
    addHook('article_title','articleTitle');
    addHook('article_keywords','articleKeywords');
    addHook('article_description','articleDescription');
    addHook('article_template','articleTemplate',array());
    addHook('theme_header','articleThemeHeader');
    
    function articleThemeHeader() {
        echo '<style>
            ul.paginator {
                list-style:none;
                margin:0; padding:0;
            }
            
            ul.paginator li {
                margin:0;padding:0;
                float:left;
            }
            
            ul.paginator li a{
                display:block;
                padding:3px 5px;
                margin-right:3px;
                background:#ccc;
                border-radius:3px;
                color:#555;
            }
            
            ul.paginator li a:hover {
                background:#999;
            }
            
            ul.paginator li a.current {
                background:none;
            }
        </style>';
    }
    
    function articleTemplate($uri) {
        $template_def = getOption('article_template');
        if (empty($uri[1])) {
            if(empty($template_def)) return 'index'; else  return $template_def;
        } else {
            $article_xml = getArticleDB();
            $article = selectXMLRecord($article_xml,"//article[slug='".safeName($uri[1])."']",1);
            $template = (string)$article[0]->template;
            if (empty($template)) {
                if (empty($template_def)) return 'index'; else return $template_def;
            } else {
                return $template;
            }
        }
    }
    
    function articleContent($uri) {
        $article_xml = getArticleDB();
        
        if (empty($uri[1]) or $uri[1] == 'page') {
            $records = selectXMLRecord($article_xml, "//article[not(notshow='1')]",'all');
            
            $limit = getOption('article_limit');
            if (empty($limit)) $limit = 5;
            
            $article_count = count($records); // кол-во статей
            $pages_count = ceil($article_count/$limit); // кол-во страниц
            
            if(empty($uri[1])) $currentPage = 1;
            else $currentPage = intval($uri[2]);
            
            if (($currentPage < 1) or ($currentPage > $pages_count)) {
                articleError();
                statusHeader(404);
            } else {
                $start = $article_count - ($currentPage * $limit);
                
                if ($start < 0) {
                    $limit = $limit + $start;
                    $start = 0;
                }
                
                $records = array_slice($records, $start, $limit);
                $article = selectXMLfields($records, array('id','title','slug'),'date','DESC');
                include 'templates/frontend/ArticleTemplate.php';
                
                if ($pages_count > 1) {
                    htmlBr();
                    $pageurl = getSiteUrl(false).'article/page/';
                    $pageP1 = $currentPage + 1;
                    $pageP2 = $currentPage + 2;
                    $pageM1 = $currentPage - 1;
                    $pageM2 = $currentPage - 2;
                    
                    echo '<ul class="paginator">';
                    if ($currentPage != 1) {
                        echo '<li><a href="'.$pageurl.'1">&le;</a></li>';
                        echo '<li><a href="'.$pageurl.$pageM1.'">< '.lang('article_back').'</a></li>';
                    }
                    if($pageM2>0) echo '<li><a href="'.$pageurl.$pageM2.'">'.$pageM2.'</a></li>';
                    if($pageM1>0) echo '<li><a href="'.$pageurl.$pageM1.'">'.$pageM1.'</a></li>';
                    echo '<li><a href="'.$pageurl.$currentPage.'" class="current" title="">'.$currentPage.'</a></li>';
                    if($pageP1<=$pages_count) echo '<li><a href="'.$pageurl.$pageP1.'">'.$pageP1.'</a></li>';       
                    if($pageP2<=$pages_count) echo '<li><a href="'.$pageurl.$pageP2.'">'.$pageP2.'</a></li>';
                    if ($currentPage != $pages_count) {
                        echo '<li><a href="'.$pageurl.$pageP1.'">'.lang('article_next').' ></a></li>';
                        echo '<li><a href="'.$pageurl.$pages_count.'">&ge;</a>';
                    }
                    echo '</ul>';
                }
            }
        } else {
            $records = selectXMLRecord($article_xml,"//article[slug='".safeName($uri[1])."']",1);
            $article = selectXMLfields($records, array('id','title','slug','views'),'id');
            if(count($article) > 0) { 
                $article = $article[0];
                
                $article['message'] = getArticleMessage($article['id']);
            
                if (!isset($_SESSION['article'.$article['id']])) {
                    $_SESSION['article'.$article['id']] = true;
                
                    $article['views'] = intval($article['views'])+1;
                    updateXMLRecord($article_xml,'article',$article['id'],array('views'=>$article['views']));
                }
            
                include 'templates/frontend/ShowArticleTemplate.php';
            } else { 
                articleError();
                statusHeader(404);
            }
        }
    }
    
    function articleList($count=5) {
        $article_xml = getArticleDB();
        
        if ($count == 'all') {
            $records = selectXMLRecord($article_xml, "//article[not(notshow='1')]",'all');
        } else {
            $records = selectXMLRecord($article_xml, "//article[not(notshow='1')]",intval($count));
        }
        $article = selectXMLfields($records, array('id','title','slug'),'id','ASC');
        include 'templates/frontend/LastArticleTemplate.php';
    }
    
    function articleTitle($uri) {
        if (empty($uri[1])) {
            return lang('article_name');
        } else {
            $article_xml = getArticleDB();
        
            $article = selectXMLRecord($article_xml,"//article[slug='".safeName($uri[1])."']",1);
            return $article[0]->title;
        }
    }
    
    function articleDescription($uri) {
        if (!empty($uri[1])) {
            $article_xml = getArticleDB();
        
            $article = selectXMLRecord($article_xml,"//article[slug='".safeName($uri[1])."']",1);
            return $article[0]->description;
        }
    }
    
    function articleKeywords($uri) {
        if (!empty($uri[1])) {
            $article_xml = getArticleDB();
        
            $article = selectXMLRecord($article_xml,"//article[slug='".safeName($uri[1])."']",1);
            return $article[0]->keywords;
        }
    }
    
    function getArticleDB() {
        $article_dir  = TEMPLATE_CMS_DATA_PATH.'article/';
        return getXMLdb($article_dir.'article.xml');
    }
    
    function getArticleMessage($id_article) {
        $article_content_dir = TEMPLATE_CMS_DATA_PATH.'article/content/';
        $article_content_xml = getXMLdb($article_content_dir.$id_article.'.xml');
        $records_content = selectXMLRecord($article_content_xml,"//content[@id=1]",1);
        
        return $records_content[0]->message;
    }
    
    function articleMore($text,$slug) {
        $more = "<!--more-->";
        
        if(preg_match($more,$text)) {
            $text_decode = htmlspecialchars_decode($text);
            $array = explode($more,$text_decode);
            return $array[0].'<div><a href="'.getSiteUrl(false).'article/'.$slug.'" class="more_article">'.lang('article_more').'</a></div>';
        } else {
            return $text;
        }
    }
    
    function articleError() {
        $pages_xml = getXML(TEMPLATE_CMS_DATA_PATH.'pages/error404.xml');
        echo $pages_xml->content;
    }