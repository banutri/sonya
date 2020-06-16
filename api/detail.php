<?php


$itemid = $_POST['itemid'];
$shopid = $_POST['shopid'];

$url_toko = 'https://shopee.co.id/api/v2/item/get?itemid='.$itemid.'&shopid='.$shopid;

echo get($url_toko);


function get($url)
    {
        
        // persiapkan curl
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, $url);
        
        // set user agent    
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Linux; Android 4.4.2; Nexus 4 Build/KOT49H) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.114 Mobile Safari/537.36');

        // return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $output = curl_exec($ch); 

        // tutup curl 
        curl_close($ch);      

        // mengembalikan hasil curl
        return $output;
    }




?>