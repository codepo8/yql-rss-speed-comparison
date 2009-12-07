<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">  
  <title>cURL test - YQL</title>
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
<h1>YQL cURL test</h1>
<?php
$oldtime = microtime(true);

$data = array(
  'http://code.flickr.com/blog/feed/rss/',
  'http://feeds.delicious.com/v2/rss/codepo8?count=15',
  'http://www.stevesouders.com/blog/feed/rss',
  'http://www.yqlblog.net/blog/feed/',
  'http://www.quirksmode.org/blog/index.xml'
);
$url ='http://query.yahooapis.com/v1/public/yql?q=';
$query = "select channel.title,channel.link,channel.item.title,channel.item.link from xml where url in('".implode("','",$data)."')";
$url.=urlencode($query).'&format=xml';
$content = get($url);
display($content);

echo '<p id="time">Time spent: <strong>' . (microtime(true)-$oldtime) .'</strong></p>';

function get($url){
  $ch = curl_init(); 
  curl_setopt($ch, CURLOPT_URL, $url); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  $output = curl_exec($ch); 
  curl_close($ch);
  return $output;
}
function display($data){
  $data = simplexml_load_string($data);
  $sets = $data->results->rss;
  $all = sizeof($sets);
  for($i=0;$i<$all;$i++){
    $r = $sets[$i];
    $title = $r->channel->title.'';
    if($title != $oldtitle){
      echo '<div><h2><a href="'.($r->channel->link.'').'">'.
           ($r->channel->title.'').'</a></h2><ul>';
    }
      echo '<li><a href="'.($r->channel->item->link.'').'">'.
           ($r->channel->item->title.'').'</a></li>';
    if($title != $sets[$i+1]->channel->title.''){
      echo '</ul></div>';
    }
    $oldtitle = $r->channel->title.'';
  };
}
?>
<ul id="nav">
  <li><a href="simplecurl.php">Using simple cURL</a></li>
  <li><a href="multicurl.php">Using multi cURL</a></li>
  <li><strong>Using YQL</strong></li>
</ul>
</body>
</html>
