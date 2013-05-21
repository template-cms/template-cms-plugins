<?php

    /**
     *  Search plugin
     *  @package TemplateCMS
     *  @subpackage Plugins
     *  @author Romanenko Sergey / Awilum
     *  @copyright 2011 Romanenko Sergey / Awilum
     *  @version 1.0.0
     *
     */


    // Register plugin
    registerPlugin( getPluginId(__FILE__),
                    getPluginFilename(__FILE__),
                    'Search',
                    '1.0.0',          
                    'Search plugin',
                    'Awilum',           
                    'http://awilum.webdevart.ru/',
                    '',
                    'search');      


    // Add hooks
    addHook('search_title','searchTitle',array());
    addHook('search_content','searchContent',array());
    addHook('theme_header','sHeaders',array());

    // Get language file for this plugin
    getPluginLanguage('Search');

    // Add template hook
    /* use: <?php templateHook('search_block'); ?> */
    addHook('search_block','searchBlock');   



    /**
     * Search plugin headers
     */
    function sHeaders() {        
        $siteurl = getSiteUrl(false);
        echo '<script src="'.getSiteUrl(false).'admin/templates/js/jquery.js'.'"></script>';
        echo "<style>".compressCSS("
                    .webResult{ margin-bottom:50px;}
                    .webResult h3{
                        background-color:#5D6F7B;
                        font-size:18px;
                        font-weight:normal;
                        padding:8px 20px;

                        /* Applying CSS3 rounded corners */
                        -moz-border-radius:18px;
                        -webkit-border-radius:18px;
                        border-radius:18px;
                    }
                    .webResult h3 b{ color:#000; }
                    .webResult h3 a{ color:#eee;border:none; text-decoration:none;}
                    .webResult p{ line-height:1.5;padding:15px 20px;}
                    .webResult p b{ color:#000;}
                    .webResult > a{ margin-left:20px;}

                    /* The show more button */
                    #more{
                        cursor:pointer;
                    }
            ")."</style>";
        echo "<script>
                $(document).ready(function(){

                    var config = {
                        siteURL     : '".$siteurl."',   // Change this to your site
                        searchSite  : true,
                        type        : 'web',
                        append      : false,
                        perPage     : 8,            // A maximum of 8 is allowed by Google
                        page        : 0             // The start page
                    }

                    // The small arrow that marks the active search icon:
                    var arrow = $('<span>',{className:'arrow'}).appendTo('ul.icons');

                    $('ul.icons li').click(function(){
                        var el = $(this);

                        if(el.hasClass('active')){
                            // The icon is already active, exit
                            return false;
                        }

                        el.siblings().removeClass('active');
                        el.addClass('active');

                        // Move the arrow below this icon
                        arrow.stop().animate({
                            left        : el.position().left,
                            marginLeft  : (el.width()/2)-4
                        });

                        // Set the search type
                        config.type = el.attr('data-searchType');
                        $('#more').fadeOut();
                    });

                    // Adding the site domain as a label for the first radio button:
                    $('#siteNameLabel').append(' '+config.siteURL);

                    // Marking the Search tutorialzine.com radio as active:
                    $('#searchSite').click();   

                    // Marking the web search icon as active:
                    $('li.web').click();

                    // Focusing the input text box:
                    $('#s').focus();

                    $('#searchForm').submit(function(){
                        googleSearch();
                        return false;
                    });

                    $('#searchSite,#searchWeb').change(function(){
                        // Listening for a click on one of the radio buttons.
                        // config.searchSite is either true or false.

                        config.searchSite = this.id == 'searchSite';
                    });

                    

                function googleSearch(settings){

                    // If no parameters are supplied to the function,
                    // it takes its defaults from the config object above:

                    settings = $.extend({},config,settings);
                    settings.term = settings.term || $('#s').val();

                    if(settings.searchSite){
                        // Using the Google site:example.com to limit the search to a
                        // specific domain:
                        settings.term = 'site:'+settings.siteURL+' '+settings.term;
                    }

                    // URL of Google's AJAX search API
                    var apiURL = 'http://ajax.googleapis.com/ajax/services/search/'+settings.type+
                                    '?v=1.0&callback=?';
                    var resultsDiv = $('#resultsDiv');

                    $.getJSON(apiURL,{
                        q   : settings.term,
                        rsz : settings.perPage,
                        start   : settings.page*settings.perPage
                    },function(r){

                        var results = r.responseData.results;
                        $('#more').remove();

                        if(results.length){

                            // If results were returned, add them to a pageContainer div,
                            // after which append them to the #resultsDiv:

                            var pageContainer = $('<div>',{className:'pageContainer'});

                            for(var i=0;i<results.length;i++){
                                // Creating a new result object and firing its toString method:
                                pageContainer.append(new result(results[i]) + '');
                            }

                            if(!settings.append){
                                // This is executed when running a new search,
                                // instead of clicking on the More button:
                                resultsDiv.empty();
                            }

                            pageContainer.append('<div class=clear></div>')
                                         .hide().appendTo(resultsDiv)
                                         .fadeIn('slow');

                            var cursor = r.responseData.cursor;

                            // Checking if there are more pages with results,
                            // and deciding whether to show the More button:

                            if( +cursor.estimatedResultCount > (settings.page+1)*settings.perPage){
                                $('<div>',{id:'more'}).appendTo(resultsDiv).click(function(){
                                    googleSearch({append:true,page:settings.page+1});                                    
                                    $(this).fadeOut();                                                  
                                });
                                $('div[id=more]').html('".lang('search_more')."');                 
                            }
                        }
                        else {

                            // No results were found for this search.

                            resultsDiv.empty();
                            $('<p>',{
                                className   : 'notFound',
                                html        : 'No Results Were Found!'
                            }).hide().appendTo(resultsDiv).fadeIn();
                        }
                    });
                }

                function result(r){

                    // This is class definition. Object of this class are created for
                    // each result. The markup is generated by the .toString() method.

                    var arr = [];

                    // GsearchResultClass is passed by the google API
                    switch(r.GsearchResultClass){

                        case 'GwebSearch':
                            arr = [
                                '<div class=webResult>',
                                '<h3><a href=\"',r.url,'\">',r.title,'</a></h3>',
                                '<p>',r.content,'</p>',
                                '<a href=\"',r.url,'\">',r.visibleUrl,'</a>',
                                '</div>'
                            ];
                        break;                        
                    }

                    // The toString method.
                    this.toString = function(){
                        return arr.join('');
                    }

                }
            });
            </script>

        ";
    }


    /**
     * Search plugin title 
     */
    function searchTitle() {
        return lang('search');
    }


    /**
     * Search plugin content
     */
    function searchContent() {  
        if(isGet('s')) $_value = get('s'); else $_value = '';

        echo '
                <h2>'.lang('search').'</h2>
                <form id="searchForm" method="post">             
                        <input style="width:250px;" id="s" type="text" value="'.$_value.'" />
                        <input type="submit" value="'.lang('search_submit').'" id="submitButton" /> 
                </form><br /><br />
                <div id="resultsDiv"></div>                
        ';

        if(isGet('s')) {             
            echo '<script>$(document).ready(function() {  $("#searchForm").submit();  }); </script>';
        }
    }    


    /** 
     * Search block
     */
    function searchBlock() {
        echo '
        <div>
            <form method="get" action="'.getSiteUrl(false).'search">
                <input id="s" type="text" name="s" />
                <input type="submit" value="'.lang('search_submit').'" id="submitButton" /> 
            </form>
        <div>
        ';
    }



