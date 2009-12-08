<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">  
  <title>cURL test - executable YQL table</title>
  <style type="text/css" media="screen">
  body{font-family:verdana,sans-serif;font-size:12px;}
    div.feed{float:left;width:20%;height:200px;overflow:auto;}
    p{clear:both;font-size:20px;margin:20px 0;color:#363;position:absolute;top:.5em;right:1em;}
    #nav {clear:both;margin:1em 0;list-style:none;padding:.5em;background:#ccc;}
    #nav li{display:inline;padding-right:1em;}
    a{color:#000;}
    h1{background:#ccc;padding:.5em;}*/
  </style>
</head>
<body>
<h1>Executable YQL table test</h1>
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
$query = "use 'http://isithackday.com/yqlspeed/rss.multi.list.xml' as m;select * from m where feeds=\"'".implode("','",$data)."'\" and html='true' and compact='true'";
$url.=urlencode($query).'&format=xml&diagnostics=false';
$content = get($url);
$content = preg_replace('/.*<results><div/','<results><div',$content);
$content = preg_replace('/div><\/results>.*/','div>',$content);
echo $content;
echo '<p>Time spent: <strong>' . (microtime(true)-$oldtime) .'</strong></p>';
function get($url){
  $ch = curl_init(); 
  curl_setopt($ch, CURLOPT_URL, $url); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  $output = curl_exec($ch); 
  curl_close($ch);
  return $output;
}
?>
<ul id="nav">
  <li><strong>Using simple cURL</strong></li>
  <li><a href="multicurl.php">Using multi cURL</a></li>
  <li><a href="yqlcurl.php">Using YQL</a></li>
  <li><strong>Using YQL executable</strong></li>
</ul>

</body>
</html>
