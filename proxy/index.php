<?
$host = 'http://the.website.i.want.to.proxy/';

$file = $_SERVER['REQUEST_URI'];
$key = hexString(md5($file));

$skipCache = false;
$saveCache = true;
$isPost = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $isPost = true;
    $skipCache = true;
    $saveCache = false;
}

if ($skipCache == false) {
if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && file_exists($key)) {
    $fs = stat($key);
    debug( $_SERVER['HTTP_IF_NONE_MATCH'] . "&&" . $key );
    $tehEtag = $_SERVER['HTTP_IF_NONE_MATCH'];
    $etag = sprintf('"%x-%x-%s"', $fs['ino'], $fs['size'],base_convert(str_pad($fs['mtime'],16,"0"),10,16));

    debug ( $tehEtag . '==' . $etag );
    if ($tehEtag == $etag) {
        header('HTTP/1.1 304 Not Modified');
        exit;
    }
}

if (file_exists($key)) {
    if (file_exists($key . '.type')) {
        $fs = stat($key);
        $mime = trim(file_get_contents($key . '.type'));
        header("Pragma:");
        header("Etag: ".sprintf('"%x-%x-%s"', $fs['ino'], $fs['size'],base_convert(str_pad($fs['mtime'],16,"0"),10,16)));
        header('Cache-control: max-age='.(60*60*24) . ',public');
        header('Expires: '. str_replace( '+0000', 'GMT', gmdate(DATE_RFC1123,time()+60*60*24))  );
        header("Content-Type: $mime");
    }

    echo file_get_contents($key);
    exit;
}
}

// Try downloading the URL...
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $host . $file);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)");
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
if ($isPost && isset($_POST))
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($_POST));
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
$data = curl_exec($ch);
$info = curl_getinfo($ch);

// Save cache...
if ($info['http_code'] == 200 && strlen($data) > 20) {
    if ($saveCache) {
        file_put_contents($key, $data);
        file_put_contents($key . '.type', $info['content_type']);
    }

    header("Content-Type: " . $info['content_type']);
    echo $data;
} else {
    header("HTTP/1.1 404 Not Found");
    echo "Not Found.";
}

function hexString($md5, $hashLevels=3) {
    $hexString = substr($md5, 0, $hashLevels );
    $folder = "";
    while (strlen($hexString) > 0) {
        $folder =  "$hexString/$folder";
        $hexString = substr($hexString, 0, -1);
    }
    if (!file_exists('cache/' . $folder))
        mkdir('cache/' . $folder, 0777, true);

    return 'cache/' . $folder . $md5;
}

function debug($blah) {
    if (isset($_GET['debug']))
        echo "==> $blah\n";
}
