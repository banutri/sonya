<?php



    $keyword = urlencode($_POST['search_input']);

    $orderby = 'relevancy';
    $limit = 20;
    $price_min = 50000;
    $price_max = 100000;

    $url_api_shopee = 'https://shopee.co.id/api/v2/search_items?by='.$orderby.'&limit=20&price_min='.$price_min.'&price_max='.$price_max.'&keyword='.$keyword;
    
    



   get_data_shopee($url_api_shopee);



    function get_data_shopee($url)
    {
        $data = json_decode(get($url)); // memanggil api shopee
        $items = $data->items;

        $tampung_id=[]; // nanti untuk menampung shopid dan itemid

        $tampung_item=[];

        // start mencari itemid dan shopid
        foreach($items as $v)
        {
            if($v->ads_keyword==NULL)
            {
                // $url_get_detail = 'https://shopee.co.id/api/v2/item/get?itemid='.$v->itemid.'&shopid='.$v->shopid;
                // $data_detail = json_decode(get($url_get_detail));
                // $data_detail = $data_detail->item;

                // array_push($tampung_item, array(
                //     'itemid'=>$data_detail->itemid,
                //     'shopid'=>$data_detail->shopid,
                //     'name'=>$data_detail->name,
                //     'price'=>remove_last_number($data_detail->price),
                //     'image'=>$data_detail->images[0]
                    
                // ));

                array_push($tampung_id, array('itemid'=>$v->itemid,'shopid'=>$v->shopid));
                
            }
        }
        // end mencari itemid dan shopid

        

        // start mencari detail item
        foreach($tampung_id as $vitem)
        {
            $url_get_detail = 'https://shopee.co.id/api/v2/item/get?itemid='.$vitem['itemid'].'&shopid='.$vitem['shopid'];
            $url_get_toko = 'https://shopee.co.id/api/v2/shop/get?shopid='.$vitem['shopid'];

            $data_detail = json_decode(get($url_get_detail));
            $data_detail = $data_detail->item;

            $data_toko = json_decode(get($url_get_toko));
            $data_toko = $data_toko->data->account;
            
            array_push($tampung_item, array(
                'itemid'=>$data_detail->itemid,
                'shopid'=>$data_detail->shopid,
                'shopicon'=>$data_toko->portrait,
                'shopstar'=>$data_toko->total_avg_star,
                'username'=>$data_toko->username,
                'name'=>ucwords(strtolower($data_detail->name)),
                'price'=>remove_last_number($data_detail->price),
                'image'=>$data_detail->images[0]
                
            ));


        }
        // end mencari detail item
        
        // return $tampung_id;

            
    
            echo json_encode($tampung_item);
            // var_dump ($tampung_item);

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