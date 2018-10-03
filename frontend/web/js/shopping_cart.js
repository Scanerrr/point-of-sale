$(document).ready(() => {
    $('.search_customer-form').on('submit', e => {
        e.preventDefault()
        const $this = $(e.target)
        $.ajax({
            type: $this.attr('method'),
            url: $this.attr('action'),
            data: $this.serialize(),
            dataType: 'json'
        })
            .done(customers => {
                const table = $('.found-customers tbody')
                table.empty()
                customers.map(customer => {
                    table.append('<tr>' +
                        `<td>${customer.firstname}</td>` +
                        `<td>${customer.lastname}</td>` +
                        `<td>${customer.email}</td>` +
                        `<td>${customer.phone}</td>` +
                        `<td>${customer.created_at}</td>` +
                        '</tr>')
                })
            })
            .fail(err => console.error(err))
    })
})