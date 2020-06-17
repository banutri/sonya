<?php


    $keyword = $_POST['search_input']; // mendapatkan keyword

    // start pengaturan kriteria pecarian produk
    //
    $orderby = 'relevancy';
    $limit = 20;

    // pengaturan harga minimum dan maximum berdasarkan keyword 'murah' atau 'mahal'
    // range ['mahal'= 100k - 200k] , ['murah'= 0-100k], ['mahal' dan 'murah' atau tidak keduanya= 0-200k]
    if(cek_keyword_harga($keyword)==0) // kondisi kata tidak keduanya
    {
        $price_min = 0;
        $price_max = 200000;
        
    }
    elseif(cek_keyword_harga($keyword)==1) // kondisi kata harga murah
    {
        $price_min = 0;
        $price_max = 100000;
    }
    elseif(cek_keyword_harga($keyword)==2) // kondisi kata harga mahal
    {
        $price_min = 100000;
        $price_max = 200000;
    }

    // membersihkan kata kunci dari kata 'mahal' dan 'murah'
    // supaya hasil pencarian barang, pada nama barang tidak mengandung kata 'murah' dan 'mahal'
    $keyword = str_replace(['murah','mahal'],"",$keyword); 

    // 

    // end pengaturan kriteria pencarian produk

    // alamat API shopee beserta parameter nya
    $keyword = urlencode($keyword); // melakukan konversi kata pencarian ke bentuk URL
    $url_pencarian_shopee = 'https://shopee.co.id/api/v2/search_items?by='.$orderby.'&limit=20&price_min='.$price_min.'&price_max='.$price_max.'&keyword='.$keyword;
    //





    // Start Eksekusi program pencarian barang
    echo json_encode(get_data_shopee($url_pencarian_shopee));
    // 







    // start mencari barang
    function get_data_shopee($url)
    {
        $data = json_decode(get($url)); // memanggil api shopee
        $items = $data->items; // mengakses key item yang berisi barang

        $tampung_id=[]; // nanti untuk menampung shopid dan itemid

        $tampung_item=[]; // nanti untuk menampung item hasil searcing

        // start mencari itemid dan shopid
        foreach($items as $v)
        {
            if($v->ads_keyword==NULL) // membersihkan hasil pencarian dari iklan
            {
                array_push($tampung_id, array('itemid'=>$v->itemid,'shopid'=>$v->shopid));
                
            }
        }
        // end mencari itemid dan shopid

        

        // start menelusuri detail hasil pencarian
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
                'historical_sold'=>$data_detail->historical_sold,
                'image'=>$data_detail->images[0]
                
            )); // memasukan hasil pencarian ke variable $tampung_item


        }
        // end menelusuri detail hasil pencarian
        
        // mengembalikan $tampung_item;
        return $tampung_item;
    }
    // end mencari barang




    // start curl
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
    // end curl

    // cek keyword jika ada kata 'mahal' atau 'murah'
    function cek_keyword_harga($kata)
    {
        $keyword_mahal = preg_match('/mahal/i',$kata);
        $keyword_murah = preg_match('/murah/i',$kata);

        if($keyword_mahal==1 && $keyword_murah==1 || $keyword_mahal==0 && $keyword_murah==0)
        {
            return 0;
        }
        elseif($keyword_murah==1)
        {
            return 1;
        }
        elseif($keyword_mahal==1)
        {
            return 2;
        }
    }


    // ini untuk menghilangkan 5 angka dibelakang harga barang. 
    // entah kenapa API shopee memberi 5 angka 0 di belakang harga barang nya :(
    function remove_last_number($number)
    {
        $string = strval($number);
        

        return (int)substr($string,0,-5);

    }



 

?>