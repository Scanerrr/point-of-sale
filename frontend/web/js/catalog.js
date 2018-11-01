$(() => {
    $('.discount-select').on('change', e => {
        e.preventDefault()
        const $this = $(e.target)
        const form = $this.closest('form')
        const discountInput = form.find('.discount-input')
        const productPrice = $this.data('markup-price')
        switch ($this.val()) {
            case 'percent':
                discountInput.attr('max', 100)
                break;
            case 'currency':
                discountInput.attr('max', productPrice)
                break;
            default:
                break;
        }
        if (!discountInput.val()) return;
        discountInput.trigger('change')
    })

    $('.discount-input').on('change', e => {
        e.preventDefault()
        const $this = $(e.target)
        const form = $this.closest('form')
        const discountTypeInput = form.find('.discount-select')
        const priceInput = form.find('.price-input')
        const totalPriceSpan = form.find('h5 .total-price')
        const productPrice = discountTypeInput.data('markup-price')

        const newPrice = getDiscountPrice(productPrice, $this.val(), discountTypeInput.val())

        priceInput.val(newPrice)
        totalPriceSpan.text('$' + newPrice)
    })

    $('.price-input').on('change', e => {
        e.preventDefault()
        const $this = $(e.target)
        const form = $this.closest('form')
        const discountTypeInput = form.find('.discount-select')
        const discountInput = form.find('.discount-input')
        const totalPriceSpan = form.find('h5 .total-price')
        const productPrice = discountTypeInput.data('markup-price')
        const newPrice = $this.val()

        if (newPrice <= productPrice) {
            discountTypeInput.val('currency')
            discountInput.val((productPrice - newPrice).toFixed(2))
        }

        totalPriceSpan.text('$' + newPrice);
    })

    $('.quantity-input').on('click', e => {
        e.preventDefault()
        const $this = $(e.target)
        const hiddenInput = $this.closest('.product').find('.hidden-quantity-input')
        console.log(hiddenInput)
        hiddenInput.val($this.val())
    })

    function getDiscountPrice(price, discount, discountType) {
        if (!discount) return price;

        switch (discountType) {
            case 'percent':
                price -= (price * discount) / 100
                break;
            case 'currency':
                price -= discount
                break;
            default:
                break;
        }
        return price.toFixed(2)
    }
})