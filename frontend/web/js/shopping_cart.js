$(document).ready(() => {
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
                $('#checkout-modal .total').html(total)
            })
            .fail(err => console.error(err.message))
    })

    $('.update-quantity').on('change', e => {
        e.preventDefault()
        const $this = $(e.target)
        $.ajax({
            type: 'post',
            url: '/cart/update/?id=' + $this.data('id'),
            data: {quantity: $this.val()},
            dataType: 'json',
        })
            .done(({success, total}) => {
                $this.closest('.items').find('.prodTotal p').text(total)
            })
            .fail(err => console.error(err.message))
    })

    $('.search_customer-form').on('submit', e => {
        e.preventDefault()
        const $this = $(e.target)
        $.ajax({
            type: $this.attr('method'),
            url: $this.attr('action'),
            data: $this.serialize(),
            dataType: 'json'
        })
            .done(({success, customers}) => {
                if (success && customers.length > 0) {
                    const table = $('.found-customers')
                    const tbody = table.find('tbody')
                    tbody.empty()
                    customers.map(customer => {
                        tbody.append('<tr>' +
                            `<td>${customer.firstname}</td>` +
                            `<td>${customer.lastname}</td>` +
                            `<td>${customer.email}</td>` +
                            `<td>${customer.phone}</td>` +
                            `<td>${customer.created_at}</td>` +
                            '</tr>')
                    })
                    table.show()
                } else {
                    const addCustomerModal = $('#add-customer-modal')
                    addCustomerModal.find('.customer-firstname').val($this.find('input[name=query]').val())
                    addCustomerModal.modal()
                }
            })
            .fail(err => console.error(err.message))
    })

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
                if (success && customer !== 0) {
                    $('#add-customer-modal').hide()
                    $('#search-customer-modal').hide()
                    const checkoutModal = $('#checkout-modal')
                    const customerDiv = checkoutModal.find('.customer-info')
                    customerDiv.append('<div>' +
                        '<span class="lighter-text"><strong>Customer:</strong></span>' +
                        `<span class="main-color-text">${customer.firstname} ${customer.lastname}</span>` +
                        '</div>')
                    customerDiv.append('<div>' +
                        '<span class="lighter-text"><strong>Email receipt to:</strong></span>' +
                        `<span class="main-color-text">${customer.email}</span>` +
                        '</div>')

                }
            })
            .fail(err => console.error(err.message))

        return false
    })
})