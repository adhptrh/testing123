document.querySelectorAll('.bGender').forEach(item => {
    item.addEventListener('click', () => {
        value = item.getAttribute('data-value');
        iGender.value = value;
    })
});