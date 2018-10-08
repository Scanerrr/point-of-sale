$(() => {

    // change quantity
    $('.update-quantity').on('change', e => {
        const $this = $(e.target)
        $.ajax({
            type: 'post',
            url: '/cart/update/?id=' + $this.data('id'),
            data: {quantity: $this.val()},
            dataType: 'json',
        })
            .done(({success, total}) => {
                $this.closest('.items').find('.prodTotal p').text(total)
                pjaxReload('#cart-idx-total')
            })
            .fail(failHandler)
    })

    // Remove Items From Cart
    $('a.remove').click(e => {
        e.preventDefault()
        const $this = $(e.target)
        $this.parent().parent().parent().hide(400);
        $.ajax({
            type: $this.data('type'),
            url: $this.data('href'),
            dataType: 'json',
        })
            .done(({total}) => {
                pjaxReload('#cart-idx-total')
            })
            .fail(failHandler)
    })
})