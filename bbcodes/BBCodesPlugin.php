<?php

    /**
     *	BBCodes plugin
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 Romanenko Sergey / Awilum
     *	@version 1.0.1
     *
     */

	
	$tags = '
			<style>
				#tags-box {
					display: none;
					color:#9A9A9A;
				}
			</style>
			<script language="javascript" type="text/javascript">
				function showBBCodes() {
					$("#tags-box").show();
					return false;
				}			
			</script>
			<span id="tags-box">
			<br />
			[b][/b] [i][/i] [u][/u] [s][/s] [img][/img] [email][/email] [url=""][/url] [size=""][/size] <br />
			[color=""][/color] [code=""][/code] [quote][/quote] [center][/center] [left][/left] [right][/right] <br />
			[justify][/justify] [list=][/list] [list][/list] [*] [br]
			</span>';
	
    
    // Register plugin
    registerPlugin( getPluginId(__FILE__),
                    getPluginFilename(__FILE__),
                    'BBCodes',
                    '1.0.1',
                    'BBcodes plugin <a href="#" onclick="return showBBCodes();" id="tags">&rarr; tags</a>'.$tags,
                    'Awilum',
                    'http://awilum.webdevart.ru/',
                    '');


    // Add filters
    addFilter('comments', 'bbcodes');
	addFilter('content', 'bbcodes');
    
    /**
     * BBCodes
     * @param string $str content
     * @return string
     */
    function bbcodes($str) {
        
		// BBCode to find...
		$in = array('/\[b\](.*?)\[\/b\]/ms',       
					'/\[i\](.*?)\[\/i\]/ms',
					'/\[u\](.*?)\[\/u\]/ms',
					'/\[s\](.*?)\[\/s\]/ms',
					'/\[img\](.*?)\[\/img\]/ms',
					'/\[email\](.*?)\[\/email\]/ms',
					'/\[url\="?(.*?)"?\](.*?)\[\/url\]/ms',
					'/\[size\="?(.*?)"?\](.*?)\[\/size\]/ms',
					'/\[color\="?(.*?)"?\](.*?)\[\/color\]/ms',
					'/\[code\="?(.*?)"?\](.*?)\[\/code\]/ms',
					'/\[quote](.*?)\[\/quote\]/ms',
					'/\[center](.*?)\[\/center\]/ms',
					'/\[left](.*?)\[\/left\]/ms',
					'/\[right](.*?)\[\/right\]/ms',
					'/\[justify](.*?)\[\/justify\]/ms',
					'/\[br]/ms',
					'/\[list\=(.*?)\](.*?)\[\/list\]/ms',
					'/\[list\](.*?)\[\/list\]/ms',
					'/\[\*\]\s?(.*?)\n/ms');
		
		
		// And replace them by...
		$out = array('<strong>\1</strong>',
					 '<em>\1</em>',
					 '<u>\1</u>',
					 '<strike>\1</strike>',
					 '<img src="\1" alt="\1" />',
					 '<a href="mailto:\1">\1</a>',
					 '<a href="\1">\2</a>',
					 '<span style="font-size:\1%">\2</span>',
					 '<span style="color:\1">\2</span>',
					 '<pre><code class="\1">\2</code></pre>',
					 '<blockquote>\1</blockquote>',
					 '<p align="center">\1</p>',
					 '<p align="left">\1</p>',
					 '<p align="right">\1</p>',
					 '<p align="justify">\1</p>',
					 '<br />',
					 '<ol start="\1">\2</ol>',
					 '<ul>\1</ul>',
					 '<li>\1</li>');
										 
        return preg_replace($in, $out, $str);
    }
    