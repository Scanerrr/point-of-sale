$('.show-cart a').on('click', (e) => {
    e.preventDefault()
    $('.shopping-cart').fadeToggle('fast')
})