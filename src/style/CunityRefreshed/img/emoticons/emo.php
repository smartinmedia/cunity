<?php

$files = scandir(".");

$smileys = [];
foreach($files AS $file){
    if($file !== "." && $file !== ".." && $file !== "emoticons.json" && $file !== pathinfo(__FILE__, PATHINFO_FILENAME).".php"){
        $vars = explode(".png",$file);
        $smileys[] = $vars[0];
    }        
}

echo '<pre>';
print_r(json_encode($smileys));