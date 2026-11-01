const form = document.getElementById('form');
const input = document.getElementsByTagName('input');
const errorElement = document.getElementById('container')


form.addEventListener('submit', (e) => {
    let messages = []
    if (input.value === '' || input.value === null) {
        messages.push('Mohon lengkapi semua data')
    }

    if (messages.length > 0) {
    e.preventDefault()
    errorElement.innerText = messages.join(', ')
    }
})
