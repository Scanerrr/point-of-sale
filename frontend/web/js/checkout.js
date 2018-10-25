$(() => {
    // create customer
    $('.create_customer-form').on('beforeSubmit', e => {
        e.preventDefault()
        const $this = $(e.target)
        $.ajax({
            type: $this.attr('method'),
            url: $this.attr('action'),
            data: $this.serialize(),
            dataType: 'json'
        })
            .done(({success, customer}) => {
                if (success && customer !== null) {
                    pjaxReload('#customer-load')
                    $('#assigned-customer').val(customer.id).trigger('change')
                    $('#add-customer-modal').modal('hide')
                }
                else {
                    alert('Customer was not created! Check the fields and try again')
                }
            })
            .fail(failHandler)

        return false
    })

    // load form by payment type
    $('.card[data-payment_method]').on('click', e => {
        const $this = $(e.currentTarget)
        const type = $this.data('payment_method')
        $this.parent().find('.active').removeClass('active')
        $this.addClass('active')
        $.ajax({
            type: 'POST',
            url: '/cart/load-form',
            data: {type: type}
        })
            .done(data => {
                $('.payment-by-type').html(data)
                $('.payment-actions button').attr('disabled', false);
            })
            .fail(failHandler)
    })

    // assign payment to session
    $('.add-payment').on('click', e => {
        e.preventDefault()
        $.ajax({
            type: 'POST',
            url: '/cart/assign-payment',
            data: {
                type: type,
                price: price
            }
        })
            .done(data => {
                $('.payment-by-type').html(data)
                $('.payment-actions button').attr('disabled', false);
            })
            .fail(failHandler)
    })
})
