$(document).ready( function() {
    $(".b1c-submit").live('click', function() {
        var params = [];
        $(".b1c-form .b1c-caption").each( function() {
            var name = $(this).text();
            var value = $(this).next().val();
            
            params.push( {'name': name, 'value': value} );
        });
        
        var productId = $(".shop-product button.btn-u").attr('data-id');
        params.push( {'name': 'productId', 'value': productId} );
        
        var productName = $(".shop-product .b1c-name").text();
        params.push( {'name': 'productName', 'value': productName} );
        
        var productPrice = parseInt($(".shop-product .shop-product-prices:eq(0)").text().replace(/\D/g, ''));
        params.push( {'name': 'productPrice', 'value': productPrice} );
        
        var parts = [];

        for ( var i = 0; i < params.length; ++i )
          parts.push(encodeURIComponent(params[i].name) + '=' + 
                     encodeURIComponent(params[i].value));

        var urlParams = parts.join('&');
        
        $.ajax({
            type: "GET",
            url: "/retailcrm/oneclick.php?" + urlParams,
            success: function(res) {}
        })
    });
});