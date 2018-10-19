$('.show-cart a').on('click', e => {
    e.preventDefault()
    $('.shopping-cart').fadeToggle('fast')
    e.target.closest('li').classList.toggle('active')
})

$('.change-status-form').on('submit', e => {
    e.preventDefault()
    const $this = $(e.target)
    $.ajax({
        type: $this.attr('method'),
        url: $this.attr('action'),
        dataType: 'json'
    })
        .done(({error}) => {
            if (error.length > 0) {
                console.error(error)
            } else {
                pjaxReload('.list-location-menu')
            }
        })
        .fail(failHandler)
})

$('.clock-form').on('submit', e => {
    e.preventDefault()
    const $this = $(e.target)
    $.ajax({
        type: $this.attr('method'),
        url: $this.attr('action'),
        dataType: 'json'
    })
        .done(({error, workedTime}) => {
            if (error.length > 0) {
                console.error(error)
            } else {
                workedTime && alert('You worked for: ' + workedTime)
                pjaxReload('.list-location-menu')
            }
        })
        .fail(failHandler)
})

$('.discount-select').on('change', e => {
    e.preventDefault()
    const $this = $(e.target)
    const discountInput = $('input[name=discount]')
    switch ($this.val()) {
        case 'percent':
            discountInput.attr('max', 100)
            break;
        case 'currency':
            discountInput.attr('max', $this.data('markup-price'))
            break;
        default:
            break;
    }
    discountInput.val(0)

})

function failHandler(err) {
    console.error(err.responseText)
}

function pjaxReload(container) {
    $.pjax.reload({container: container, async: false})
}