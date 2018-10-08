$(() => {

    // payment type
    $('input[name=payment-type]').on('change', e => {
        const type = e.target.value

    })

    // create customer form
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
            })
            .fail(failHandler)

        return false
    })
})
