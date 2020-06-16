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

});

function insert_ke_product_list(data)
{
    
    $('#product-list').html('');
    $.each(data, function (i, v) { 
         $('#product-list').append(`
         <div class="col-sm-4">
         <div class="card mb-4 shadow-sm">
                
                <div class="thubmnail my-2" style="background-image: url('https://cf.shopee.co.id/file/`+v.image+`');">

                </div>
                <div class="card-body">
                  <p class="card-text text-justify">`+v.name.replace(/ +(?= )/g,'')+`</p>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                      <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
                      <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                    </div>
                    <small class="text-muted">9 mins</small>
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