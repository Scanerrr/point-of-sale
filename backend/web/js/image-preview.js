function readURL(input) {
    const file = input.files

    const img = document.querySelector('.image-preview img')

    if (!file || !file[0]) return img.src = '';

    img.src = URL.createObjectURL(file[0])
}
$('.avatar-input').on('change', e => readURL(e.target))