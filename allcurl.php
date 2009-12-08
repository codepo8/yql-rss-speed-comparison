<?php
echo 'datain('; 
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
echo '{"single":"' . (microtime(true)-$oldtime) .'",';
$oldtime = microtime(true);
$data = array(
  'http://code.flickr.com/blog/feed/rss/',
  'http://feeds.delicious.com/v2/rss/codepo8?count=15',
  'http://www.stevesouders.com/blog/feed/rss',
  'http://www.yqlblog.net/blog/feed/',
  'http://www.quirksmode.org/blog/index.xml'
);
$r = multiRequest($data);
echo '"multi":"' . (microtime(true)-$oldtime) .'",';

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
echo '"yql":"' . (microtime(true)-$oldtime) .'"}';

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
function get($url){
  $ch = curl_init(); 
  curl_setopt($ch, CURLOPT_URL, $url); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
  $output = curl_exec($ch); 
  curl_close($ch);
  return $output;
}
echo')';

?>