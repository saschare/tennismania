$(function() {
    //$(document).reloadCart();
});

$(document).on('click', '.addArticleToCart', function(event) {
    event.preventDefault();
    
    var form = $(this).closest('form');

    $('#articleAddedToBasketModal').foundation('reveal', 'open', {
        url: '?renderOnly=Cart.Modal.Item.Added',
        type: 'POST',
        data: {
            action: 'addArticleToCart',
            idart: $(this).data('idart'),
            qty: $('select[name="qty"]', form).val(),
            timestamp: new Date().getTime()
        },
        success: function(data, textStatus, jqXHR ) {
          
        },
        error: function(jqXHR, textStatus, errorThrown) {

        },
        complete: function(jqXHR, textStatus) {
            
        }
    });
});

$.fn.reloadCart = function() {
    $.post('?renderOnly=Cart.Sidebar.Cart&ts=' + new Date().getTime(), function(data) {
        $('.widget.cart .content').slideUp('2000', function() {

            if (data.cnt > 0) {
                var content = $(this);

                $(content).html('<p>Es befinden sich ' + data.cnt + ' Artikel in Ihrem Warenkorb.</p>');
                $(content).append('<ul>');
                $.each(data.articles, function(idart, article) {
                    $(content).append('<li data-idart="' + idart + '">' + article.cnt + 'x ' + article.pagetitle + '</li>');
                });
                $(content).append('</ul>');
            }

            $(this).slideDown('2000');
        });
    }, 'json');
};