$(() => {
    // create customer
    $(document).on('beforeSubmit', '.create_customer-form', createCustomerCallback)

    // load form by payment type
    $(document).on('click', '.select-payment-type:not(.disabled) .card[data-payment_type]', selectPaymentCallback)

    // assign payment to session
    $(document).on('click', '.add-payment', addPaymentCallback)

    // complete sale
    $(document).on('click', '.complete-sale', completeSaleCallback)
})

function createCustomerCallback(e) {
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
}

function selectPaymentCallback(e) {
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
}

function addPaymentCallback(e) {
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
                pjaxReload('#payments-load0')
                pjaxReload('#payments-load1')
                pjaxReload('#payments-load')
                $('.payment-by-type').html('')

                if (allowCheckout) {
                    $('.complete-sale').attr('disabled', false)
                }
            }
        )
        .fail(failHandler)
}

function completeSaleCallback(e) {
    e.preventDefault()
    $.ajax({
        type: 'POST',
        url: '/cart/checkout',
        data: {customer: $('[name=customer]').val()}
    })
        .done(({error, success}) => {
            if (error) alert(error)
            if (success) alert(success)
        })
        .fail(failHandler)
}