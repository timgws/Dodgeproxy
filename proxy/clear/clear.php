<?php

echo "<pre>";
echo "BE GONE, OLD DOWNLOADED CACHE\n";
$l = rrmdir('cache');
echo "$l old cache file(s) removed";


function rrmdir($dir) {
    $l = 0;
    foreach(glob($dir . '/*') as $file) {
        if(is_dir($file))
            @rrmdir($file);
        else
            @unlink($file);
        $l++;
    }
    @rmdir($dir);

    return $l;
}

