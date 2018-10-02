$('.show-cart a').on('click', e => {
    e.preventDefault()
    $('.shopping-cart').fadeToggle('fast')
    e.target.closest('li').classList.toggle('active')
})