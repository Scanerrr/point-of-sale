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
    $('.select-payment-type')
        .not('.disabled')
        .find('.card[data-payment_type]').on('click', e => {
        const $this = $(e.currentTarget)
        const type = $this.data('payment_type')
        $this.parent().find('.active').removeClass('active')
        $this.addClass('active')
        $.ajax({
            type: 'POST',
            url: '/cart/load-form',
            data: {type: type}
        })
            .done(data => {
                $('.payment-by-type').html(data)
                $('.add-payment').attr('disabled', false)
            })
            .fail(failHandler)
    })

    // assign payment to session
    $('.add-payment').on('click', e => {
        e.preventDefault()
        const price = $('input[name=payment_amount]').val()
        const method = $('[name=payment_method]').val()
        const type = $('.select-payment-type .card[data-payment_type].active').data('payment_type')
        const data = {
            price: price,
            method: method
        }
        if (type === 1) {
            data.card_number = $('input[name=payment_card_number]').val()
        }
        $.ajax({
            type: 'POST',
            url: '/cart/assign-payment',
            data: data
        })
            .done(({success, allowCheckout}) => {
                    if (!success) {
                        alert('wrong')
                        return;
                    }
                    pjaxReload('#payments-load')
                    $('.payment-by-type').html('')

                    if (allowCheckout) {
                        $('.complete-sale').attr('disabled', false)
                    }
                }
            )
            .fail(failHandler)
    })

    $('.complete-sale').on('click', e => {
        e.preventDefault()
        $.ajax({
            type: 'POST',
            url: '/cart/checkout',
            data: {customer: $('[name=customer]').val()}
        })
            .done(({url}) => {
                location.href = url
            })
            .fail(failHandler)
    })
})
