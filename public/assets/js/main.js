document.querySelectorAll('.dropdown').forEach(function (dropdown) {
    dropdown.addEventListener('mouseenter', function () {
        const menu = bootstrap.Dropdown.getOrCreateInstance(dropdown.querySelector('.dropdown-toggle'));
        menu.show();
    });
    dropdown.addEventListener('mouseleave', function () {
        const menu = bootstrap.Dropdown.getOrCreateInstance(dropdown.querySelector('.dropdown-toggle'));
        menu.hide();
    });
});

document.querySelectorAll('.selectable-size').forEach(button => {
    button.addEventListener('click', () => {
        document.querySelectorAll('.selectable-size').forEach(b => b.classList.remove('selected'));
        button.classList.add('selected');

        const talla = button.textContent;
        const inputTalla = document.getElementById('tallaSeleccionada');
        const inputFormTalla = document.getElementById('tallaSeleccionadaInput');

        if (inputTalla) {
            inputTalla.value = talla;
        }

        if (inputFormTalla) {
            inputFormTalla.value = talla;
        }
    });
});

document.querySelectorAll('.filtro-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
    });
});
