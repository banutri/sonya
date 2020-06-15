$(document).ready(function () {

    
    $('#search-button').on('click',function(){
        var search_input = $('#search-input').val();
        insert_ke_product_list(search_input);

    });

});

function insert_ke_product_list(search_input)
{
    var data_pencarian = get_data_pencarian(search_input);
    console.log(data_pencarian);
}


function get_data_pencarian(search_input)
{
    var hasil='';
    $.ajax({
        async:false,
        type: "post",
        url: "api/apicall.php",
        data: {search_input:search_input},
        dataType: "JSON",
        success: function (response) {
            hasil=response;
        }
    });
    return hasil;
}