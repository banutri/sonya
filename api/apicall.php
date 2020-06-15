<?php

    $keyword = urlencode($_POST['search_input']);

    $url_api_shopee = 'https://shopee.co.id/api/v2/search_items?by=relevancy&limit=20&order=desc&keyword='.$keyword;
    
    
    // $data_pencarian = minta($url_api_shopee);

    // $data_pencarian= json_decode($data_pencarian)->items;

    // echo json_encode($data_pencarian);

    // membersihkan data dari shopee supaya rapih



   get_data_shopee($url_api_shopee);



    function get_data_shopee($url)
    {
        $data = json_decode(get($url)); // memanggil api shopee
        $items = $data->items;

        $tampung_id=[]; // nanti untuk menampung shopid dan itemid

        // start mencari itemid dan shopid
        foreach($items as $v)
        {
            if($v->adsid==NULL)
            {
                array_push($tampung_id,array(
                    'itemid'=>$v->itemid,
                    'shopid'=>$v->shopid
                ));
            }
        }
        // end mencari itemid dan shopid

        $tampung_item=[];

        // start mencari detail item
        foreach($tampung_id as $v)
        {
            $url_get_detail = 'https://shopee.co.id/api/v2/item/get?itemid='.$v['itemid'].'&shopid='.$v['shopid'];
            $data_detail = json_decode(get($url_get_detail));
            array_push($tampung_item, $data_detail);


        }
        // end mencari detail item
        
        // return $tampung_id;

            
    
            echo json_encode($tampung_item);

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