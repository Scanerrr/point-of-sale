$('.show-cart a').on('click', e => {
    e.preventDefault()
    $('.shopping-cart').fadeToggle('fast')
    e.target.closest('li').classList.toggle('active')
})

$('.change-status-form').on('submit', e => {
    e.preventDefault()
    const $this = $(e.currentTarget)
    $.ajax({
        type: $this.attr('method'),
        url: $this.attr('action'),
        dataType: 'json'
    })
        .done(({error, status}) => {
            if (!error) {
                $('.change-status').text(status ? 'Open' : 'Closed')
            }
        })
        .fail(failHandler)
})

function failHandler(err) {
    console.error(err.responseText)
}

function pjaxReload(container) {
    $.pjax.reload({container: container, async: false})
}