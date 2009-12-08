<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">  
  <title>Using YQL, JSON-P-X and rss.multi.list in PHP</title>
  <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
  <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/base/base.css" type="text/css">
  <style type="text/css" media="screen">
   #doc{font-family:calibri,helvetica,arial,sans-serif;} .feed{width:32%;float:left;background:#ccc;-moz-border-radius:5px;margin-right:1%;}
    .feeds{overflow:auto;}
    .feeds h2 a{text-decoration:none;color:#000;padding:.2em;}
    .feeds a{color:#369;text-decoration:none;}
  </style>
</head>
<body>
<div id="doc" class="yui-t7">
  <div id="hd" role="banner"><h1>Using YQL, JSON-P-X and rss.multi.list in PHP</h1></div>
  <div id="bd" role="main">
    <?php
    $oldtime=microtime(true);
    $url= "http://query.yahooapis.com/v1/public/yql?q=use%20%22http%3A%2F%2Fgithub.com%2Fyql%2Fyql-tables%2Fraw%2Fmaster%2Fdata%2Frss.multi.list.xml%22%20as%20mrss%3B%20select%20*%20from%20mrss%20where%20feeds%3D%22'http%3A%2F%2Fsearch.twitter.com%2Fsearch.rss%3Fq%3Dperformance%26rpp%3D5'%2C'http%3A%2F%2Fsearch.twitter.com%2Fsearch.rss%3Fq%3Dwebservice%26rpp%3D5'%2C'http%3A%2F%2Ffeeds.delicious.com%2Fv2%2Frss%2Ftag%2Fperformance%3Fcount%3D5'%22%20and%20html%3D%22true%22%20and%20compact%3D%22true%22&format=xml&diagnostics=false&callback=seedfeeds";
    $content = get($url);
    $content = preg_replace("/.*\[\"|\"\].*/","",$content);
    echo stripslashes($content);
    function get($url){
      $ch = curl_init(); 
      curl_setopt($ch, CURLOPT_URL, $url); 
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
      $output = curl_exec($ch); 
      curl_close($ch);
      return $output;
    }
    echo '<p>Time spent: <strong>' . (microtime(true)-$oldtime) .'</strong></p>';
    
    ?>  </div>
  <div id="ft" role="contentinfo"><p>Written by <a href="http://icant.co.uk">Christian Heilmann</a></p></div>
</div>
</body>
</html>
