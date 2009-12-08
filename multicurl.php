<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">  
  <title>cURL test - multicurl</title>
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
<h1>Multi cURL test</h1>
<?php
$oldtime = microtime(true);

$data = array(
  'http://code.flickr.com/blog/feed/rss/',
  'http://feeds.delicious.com/v2/rss/codepo8?count=15',
  'http://www.stevesouders.com/blog/feed/rss',
  'http://www.yqlblog.net/blog/feed/',
  'http://www.quirksmode.org/blog/index.xml'
);
$r = multiRequest($data);
display($r);

function multiRequest($data, $options = array()) {
  // array of curl handles
  $curly = array();
  // data to be returned
  $result = array();
  // multi handle
  $mh = curl_multi_init();
  // loop through $data and create curl handles
  // then add them to the multi-handle
  foreach ($data as $id => $d) {
    $curly[$id] = curl_init();
    $url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
    curl_setopt($curly[$id], CURLOPT_URL,            $url);
    curl_setopt($curly[$id], CURLOPT_HEADER,         0);
    curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
    // post?
    if (is_array($d)) {
      if (!empty($d['post'])) {
        curl_setopt($curly[$id], CURLOPT_POST,       1);
        curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $d['post']);
      }
    }
    // extra options?
    if (!empty($options)) {
      curl_setopt_array($curly[$id], $options);
    }
    curl_multi_add_handle($mh, $curly[$id]);
  }
  // execute the handles
  $running = null;
  do {
    curl_multi_exec($mh, $running);
  } while($running > 0);
  // get content and remove handles
  foreach($curly as $id => $c) {
    $result[$id] = curl_multi_getcontent($c);
    curl_multi_remove_handle($mh, $c);
  }
  // all done
  curl_multi_close($mh);
  return $result;
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

echo '<p>Time spent: <strong>' . (microtime(true)-$oldtime) .'</strong></p>';


?>
<ul id="nav">
  <li><a href="simplecurl.php">Using simple cURL</a></li>
  <li><strong>Using multi cURL</strong></li>
  <li><a href="yqlcurl.php">Using YQL</a></li>
  <li><a href="yqltable.php">Using YQL executable</a></li>
</ul>
</body>
</html>
