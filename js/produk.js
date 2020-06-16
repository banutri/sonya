$(document).ready(function () {

   

    // pencarian
    $('#search-button').on('click',function(){
        var search_input = $('#search-input').val();
        search_input = $.trim(search_input);
        if(search_input==='')
        {
            // do nothing
            console.log('kosong hmm...');
        }
        else
        {
            loading_search();
            $.ajax({
                type: "post",
                url: "api/apicall.php",
                data: {search_input:search_input},
                dataType: "JSON",
                success: function (response) {
                    insert_ke_product_list(response);
                }
            });
        }
        

    });
    // pencarian


    // lihat detail barang
    $('#product-list').on('click','.btn-lihat-detail',function(){
        
        var itemid = $(this).data('item');
        var shopid = $(this).data('shop');

        var data_detail = get_data_detail(itemid,shopid);
        // console.log(data_detail);

            
            $('#img-product').attr('src','https://cf.shopee.co.id/file/'+data_detail['image']);
            $('#name-product').html(data_detail['name']);
            $('#price-product').html('Rp.'+angka_koma(data_detail['price']));
            $('#desc-product').html(data_detail['description']);
            $('#img-shop').attr('src','https://cf.shopee.co.id/file/'+data_detail['shopicon']+'_tn');
            $('#username-shop').html(data_detail['username']);
            $('#shop-star').html('');

            $('#shop-star').append(generate_star(data_detail['shopstar']));

            $('#shop-link').attr('href',generate_link(data_detail['name'],data_detail['shopid']));

       
       $('#modal-detail').modal('show');

        // loading_detail_barang(); // animasi

        
    
       

        
    });
    // lihat detail barang



    

});

function insert_ke_product_list(data)
{
    
    $('#product-list').html('');
    $.each(data, function (i, v) { 
         $('#product-list').append(`
         <div class="col-sm-4">
         <div class="card mb-4 shadow-sm pb-4">
                
                <div class="thubmnail my-2" style="background-image: url('https://cf.shopee.co.id/file/`+v.image+`');">

                </div>
                <div class="card-body">
                  <p class="card-text ">`+cut_the_string(v.name)+`</p>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                      <button type="button" class="btn btn-sm btn-outline-secondary btn-lihat-detail" data-item="`+v.itemid+`" data-shop="`+v.shopid+`">Detail</button>
                    </div>
                    <p class="text-muted">Rp.`+angka_koma(v.price)+`</p>
                  </div>
                </div>
              </div>
              </div>
         `);
    });
}


function get_data_pencarian(search_input)
{
    var hasil='';
    
    return hasil;
}

function cut_the_string(str)
{
    str = str.replace(/ +(?= )/g,'');
    var maxlength = 75;
    var length = str.length;
    if(length>75)
    {
        var selisih = length-maxlength;
        return str.slice(0,-selisih)+'...';
    }
    else
    {
        return str;
    }
}


/// start function ubah angka koma
function angka_koma(angka) {
	return angka.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}
///end function ubah angka koma


function loading_search()
{
    // animasi loading
    $(document).ajaxStart(function(){
        $("#search-button").css("display", "none");
        $("#search-input").prop('disabled',true);
        $("#search-button-loader").css("display", "block");
        
    });
    
    $(document).ajaxComplete(function(){
        $("#search-button-loader").css("display", "none");
        $("#search-button").css("display", "block");
        $("#search-input").prop('disabled',false);
    });
}


function loading_detail_barang()
{
    // animasi loading
    $(document).ajaxStart(function(){
        $("#search-button").css("display", "none");
        $("#search-input").prop('disabled',true);
        $("#search-button-loader").css("display", "block");
        
    });
    
    $(document).ajaxComplete(function(){
        $("#search-button-loader").css("display", "none");
        $("#search-button").css("display", "block");
        $("#search-input").prop('disabled',false);
    });
}


function get_data_detail(itemid,shopid)
{
    var jsonku='';

    $.ajax({
        async:false,
        type: "post",
        url: "api/detail.php",
        data: {itemid:itemid,shopid:shopid},
        dataType: "JSON",
        success: function (response) {
            jsonku = response;
        }
    });
    return jsonku;
}

function generate_link(name,shopid)
{
    name = encodeURI(name);
    var url = 'https://shopee.co.id/search?keyword='+name+'&shop='+shopid;
    return url;
    
}

function generate_star(star)
{
    star_icon ='';
    star = Math.floor(star);
    var i=0;
    for(i=0; i<star;)
    {
        star_icon = star_icon.concat(' ','⭐');
        i++;
    }
    return star_icon;
}