//plugin bootstrap minus and plus
//http://jsfiddle.net/laelitenetwork/puJ6G/
$('.btn-number').click(function(e){
    e.preventDefault();
    
    kodeItem = $(this).attr('data-field');
    type      = $(this).attr('data-type');
    var input = $("input[name='"+kodeItem+"']");
    var qty = parseInt(input.val());
    if (!isNaN(qty)) {
        if(type == 'minus') {
            if(qty > input.attr('min')) {
                input.val(qty - 1).change();
            } 
            if(parseInt(input.val()) == input.attr('min')) {
                $(this).attr('disabled', true);
            }
        } else if(type == 'plus') {
            if(qty < input.attr('max')) {
                input.val(qty + 1).change();
            }
            if(parseInt(input.val()) == input.attr('max')) {
                $(this).attr('disabled', true);
            }
        }
    } else {
        input.val(0);
    }
});

// $('.input-number').focusin(function(){
//     $(this).data('oldValue', $(this).val());
// });

$('.input-number').change(function() {
    
    minValue =  parseInt($(this).attr('min'));
    maxValue =  parseInt($(this).attr('max'));
    valueCurrent = parseInt($(this).val());
    
    name = $(this).attr('name');
    if(valueCurrent >= minValue) {
        $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the minimum value was reached');
        $(this).val($(this).data('oldValue'));
    }
    if(valueCurrent <= maxValue) {
        $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the maximum value was reached');
        $(this).val($(this).data('oldValue'));
    }
    
});


$(document).ready(function(){
    $('.add_cart').click(function(){
        console.log('hello');
        var product_id    = $(this).data("productid");
        var product_name  = $(this).data("productname");
        var product_price = $(this).data("productprice");
        var quantity      = $('#' + product_id).val();
        $.ajax({
            url : "<?php echo base_url('index.php/order/add_to_cart');?>",
            method : "POST",
            data : {
                product_id: product_id, 
                product_name: product_name, 
                product_price: product_price, 
                quantity: quantity
            },
            success: function(data){
                let datas = JSON.parse(data);
                console.log(datas);
                $('#detail_cart').html(datas.cart);
            }
        });
    });

     
    $('#detail_cart').load("<?php echo base_url('index.php/order/load_cart');?>");

     
    $(document).on('click','.romove_cart',function(){
        var row_id=$(this).attr("id"); 
        $.ajax({
            url : "<?php echo site_url('order/delete_cart');?>",
            method : "POST",
            data : {row_id : row_id},
            success :function(data){
                $('#detail_cart').html(data);
            }
        });
    });
});



