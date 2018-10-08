$('.show-cart a').on('click', e => {
    e.preventDefault()
    $('.shopping-cart').fadeToggle('fast')
    e.target.closest('li').classList.toggle('active')
})

function failHandler(err) {
    console.error(err.responseText)
}

function pjaxReload(container) {
    $.pjax.reload({container: container, async: false})
}