<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">  
  <title>cURL test - simple</title>
  <style type="text/css" media="screen">
  body{font-family:verdana,sans-serif;font-size:12px;}
  div{float:left;width:20%;height:200px;overflow:auto;}
  p{clear:both;font-size:20px;margin:20px 0;color:#363;position:absolute;top:.5em;right:1em;}
  #nav {clear:both;margin:1em 0;list-style:none;padding:.5em;background:#ccc;}
  #nav li{display:inline;padding-right:1em;}
  a{color:#000;}
  h1{background:#ccc;padding:.5em;}
  </style>
</head>
<body>
<h1>Simple cURL test</h1>
<?php
$oldtime = microtime(true);
$url = 'http://code.flickr.com/blog/feed/rss/';
$content[] = get($url);
$url = 'http://feeds.delicious.com/v2/rss/codepo8?count=15';
$content[] = get($url);
$url = 'http://www.stevesouders.com/blog/feed/rss';
$content[] = get($url);
$url = 'http://www.yqlblog.net/blog/feed/';
$content[] = get($url);
$url = 'http://www.quirksmode.org/blog/index.xml';
$content[] = get($url);
display($content);
echo '<p>Time spent: <strong>' . (microtime(true)-$oldtime) .'</strong></p>';
function get($url){
  $ch = curl_init(); 
  curl_setopt($ch, CURLOPT_URL, $url); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  $output = curl_exec($ch); 
  curl_close($ch);
  return $output;
}
function display($data){
  foreach($data as $d){
    $obj = simplexml_load_string($d);
    echo '<div><h2><a href="'.$obj->channel->link.'">'.
          $obj->channel->title.'</a></h2>';
    echo '<ul>';
    foreach($obj->channel->item as $i){
      echo '<li><a href="'.$i->link.'">'.$i->title.'</a></li>';
    }
    echo '</ul></div>';
  }
}
?>
<ul id="nav">
  <li><strong>Using simple cURL</strong></li>
  <li><a href="multicurl.php">Using multi cURL</a></li>
  <li><a href="yqlcurl.php">Using YQL</a></li>
  <li><a href="yqltable.php">Using YQL executable</a></li>
</ul>

</body>
</html>
