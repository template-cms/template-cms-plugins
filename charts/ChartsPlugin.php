<?php

    /**
     *  Charts plguin
     *
     *  @package TemplateCMS
     *  @subpackage Plugins
     *  @author Romanenko Sergey / Awilum
     *  @copyright 2011 - 2012 Romanenko Sergey / Awilum
     *  @version 1.0.0
     *
     */


    // Register plugin
    registerPlugin( getPluginId(__FILE__),
                    getPluginFilename(__FILE__),
                    'Charts',
                    '1.0.0',
                    'Charts plugin for TemplateCMS :)',
                    'Awilum',
                    'http://template-cms.ru/');

 

    // Add hooks
    addHook('theme_header', 'chartsHeaders');
    
    // Add shortcode
    addShortcode('chart', 'chartsShortcode');


    /**
     * Charts Headers
     */
    function chartsHeaders() {
        echo ('<script type="text/javascript" src="https://www.google.com/jsapi"></script>');
    }


    /**
     * Charts Shortcode
     *
     *  <code>
     *      {chart data="Mushrooms,3|Zucchini,5|Pepperoni,1"}
     *
     *      {chart width="640" height="480" data="Mushrooms,3|Zucchini,5|Pepperoni,1"}
     *
     *      {chart width="640" height="480" title="How Much Pizza I Ate Last Night" data="Mushrooms,3|Zucchini,5|Pepperoni,1"}
     *
     *      {chart width="640" height="480" title="How Much Pizza I Ate Last Night" type="bar" data="Mushrooms,3|Zucchini,5|Pepperoni,1"}
     *  </code>
     *
     */
    function chartsShortcode($attributes) {
        
        // Extract
        extract($attributes);

        // UID
        $uid = getUniqueString();        

        // Data
        if (isset($data)) {
            $data_string = '';            
            $_data = explode('|', $data);
            foreach($_data as $d) {
                $part = explode(',', $d);                
                $data_string .= '["'.$part[0].'", '.$part[1].'],';
            }
            
            $data = $data_string;
        } else {
            $data = '["test", 1],';
        }

        // Title
        if (isset($title)) $title = $title; else $title = 'Charts';

        // Type
        if (isset($type) && ($type == 'pie' || $type == 'bar')) $type = $type; else $type = 'pie';

        // Width
        if (isset($width)) $width = $width; else $width = 400;

        // Height
        if (isset($height)) $height = $height; else $height = 300;

        // Chart
        return ('
                <script type="text/javascript">

                  // Load the Visualization API and the piechart package.
                  google.load("visualization", "1.0", {"packages":["corechart"]});

                  // Set a callback to run when the Google Visualization API is loaded.
                  google.setOnLoadCallback(drawChart_'.$uid.');

                  // Callback that creates and populates a data table,
                  // instantiates the pie chart, passes in the data and
                  // draws it.
                  function drawChart_'.$uid.'() {

                    // Create the data table.
                    var data = new google.visualization.DataTable();
                    data.addColumn("string", "Topping");
                    data.addColumn("number", "Slices");
                    data.addRows([
                      '.$data.'                      
                    ]);

                    // Set chart options
                    var options = {"title":"'.$title.'",
                                   "width":'.(int)$width.',
                                   "height":'.(int)$height.'};

                    // Instantiate and draw our chart, passing in some options.                    
                    var chart = new google.visualization.'.ucfirst($type).'Chart(document.getElementById("chart_div_'.$uid.'"));
                    chart.draw(data, options);
                  }
                </script>
                <div id="chart_div_'.$uid.'"></div>
        ');
    
    }