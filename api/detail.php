<?php


$itemid = $_POST['itemid'];
$shopid = $_POST['shopid'];

// $itemid = 5716522705;
// $shopid = 20481265;



echo json_encode(detail_item($itemid,$shopid));

// detail_item();

function detail_item($itemid,$shopid)
{
    
    $url_toko = 'https://shopee.co.id/api/v2/shop/get?shopid='.$shopid;
    $url_detail = 'https://shopee.co.id/api/v2/item/get?itemid='.$itemid.'&shopid='.$shopid;

    $data_detail = json_decode(get($url_detail));
    $data_detail = $data_detail->item;


    $data_toko = json_decode(get($url_toko));
    $data_toko = $data_toko->data->account;

    $data_join = array(
        'itemid'=>$data_detail->itemid,
        'shopid'=>$data_detail->shopid,
        'shopicon'=>$data_toko->portrait,
        'shopstar'=>$data_toko->total_avg_star,
        'username'=>$data_toko->username,
        'description'=>$data_detail->description,
        'name'=>ucwords(strtolower($data_detail->name)),
        'price'=>remove_last_number($data_detail->price),
        'image'=>$data_detail->images[0]
    );

    return $data_join;
}


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

    function remove_last_number($number)
    {
        $string = strval($number);
        

        return (int)substr($string,0,-5);

    }



?>