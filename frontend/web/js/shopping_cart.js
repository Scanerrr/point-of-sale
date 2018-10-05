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
            .fail(failHandler)
    })

    // change quantity
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
            .fail(failHandler)
    })

    // payment type
    $('input[name=payment-type]').on('change', e => {
        $('#payment-modal').modal()
    })

    $('.set-payment-form').on('submit', e => {
        e.preventDefault();
        const $this = $(e.target)
        $.ajax({
            type: $this.attr('method'),
            url: $this.attr('action'),
            data: $this.serialize(),
            dataType: 'json'
        })
            .done(({success}) => {

            })
            .fail(failHandler)
    })

    // search customer form
    $('.search_customer-form').on('submit', e => {
        e.preventDefault()
        const $this = $(e.target)
        const query = $this.find('input[name=query]').val()
        if (query.length < 3) {
            alert('3 letter minimum for search')
            return false
        }
        $.ajax({
            type: $this.attr('method'),
            url: $this.attr('action'),
            data: {query: query},
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
                            `<td><a href="#" class="assign-customer" data-customer="${customer.id}">Assign</a></td>` +
                            '</tr>')
                    })
                    table.show()
                } else {
                    const addCustomerModal = $('#add-customer-modal')
                    addCustomerModal.find('.customer-firstname').val($this.find('input[name=query]').val())
                    addCustomerModal.modal()
                }
            })
            .fail(failHandler)
    })

    $('.found-customers').on('click', '.assign-customer', e => {
        const $this = $(e.target)
        $.ajax({
            url: '/customer/assign/?id=' + $this.data('customer'),
            dataType: 'json'
        })
            .done(({success, customer}) => {
                if (success && customer !== null) appendCustomerInfo(customer)
            })
            .fail(failHandler)
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
                if (success && customer !== null) appendCustomerInfo(customer)
            })
            .fail(failHandler)

        return false
    })
})

function appendCustomerInfo({firstname, lastname, email}) {
    $('#add-customer-modal').hide()
    $('#search-customer-modal').hide()
    const checkoutModal = $('#checkout-modal')
    const customerDiv = checkoutModal.find('.customer-info').empty()
    appendInfo(customerDiv, 'Customer:', `${firstname} ${lastname}`)
    appendInfo(customerDiv, 'Email receipt to:', `${email}`)
}

function appendInfo(div, title, text) {
    div.append('<div>' +
        '<span class="lighter-text"><strong>Customer:</strong></span>' +
        `<span class="main-color-text">${text}</span>` +
        '</div>')
}

function failHandler(err) {
    console.error(err.message)
}