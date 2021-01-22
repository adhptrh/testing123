const button_add = document.getElementsByClassName("add")[0];
const create_form = document.getElementsByClassName("create")[0];
const list = document.getElementsByClassName("list")[0];
let soal, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, button_cancel, form;
let dsimpan = '';

button_add.addEventListener("click", () => {
    load_add_form();
})

function close_form(e) {
    (e || window.event).preventDefault();
    create_form.classList.toggle('d-none');
    list.classList.toggle('d-none');
}

/* Menge-load editor */
function setEditor() {
    soal = new Quill('#soal', {
        modules: {
            toolbar: [
                [{ header: [1, 2, false] }],
                ['bold', 'italic', 'underline'],
                ['image', 'code-block']
            ]
        },
        placeholder: 'Ketikkan soal di sini ...',
        theme: 'snow' // or 'bubble'
    });

    opsi_a = new Quill('#opsi_a', {
        modules: {
            toolbar: [
                [{ header: [1, 2, false] }],
                ['bold', 'italic', 'underline'],
                ['image', 'code-block']
            ]
        },
        placeholder: 'Ketikkan opsi di sini ...',
        theme: 'snow' // or 'bubble'
    });

    opsi_b = new Quill('#opsi_b', {
        modules: {
            toolbar: [
                [{ header: [1, 2, false] }],
                ['bold', 'italic', 'underline'],
                ['image', 'code-block']
            ]
        },
        placeholder: 'Ketikkan opsi di sini ...',
        theme: 'snow' // or 'bubble'
    });

    opsi_c = new Quill('#opsi_c', {
        modules: {
            toolbar: [
                [{ header: [1, 2, false] }],
                ['bold', 'italic', 'underline'],
                ['image', 'code-block']
            ]
        },
        placeholder: 'Ketikkan opsi di sini ...',
        theme: 'snow' // or 'bubble'
    });

    opsi_d = new Quill('#opsi_d', {
        modules: {
            toolbar: [
                [{ header: [1, 2, false] }],
                ['bold', 'italic', 'underline'],
                ['image', 'code-block']
            ]
        },
        placeholder: 'Ketikkan opsi di sini ...',
        theme: 'snow' // or 'bubble'
    });

    opsi_e = new Quill('#opsi_e', {
        modules: {
            toolbar: [
                [{ header: [1, 2, false] }],
                ['bold', 'italic', 'underline'],
                ['image', 'code-block']
            ]
        },
        placeholder: 'Ketikkan opsi di sini ...',
        theme: 'snow' // or 'bubble'
    });
}

function setButtonCancel() {
    button_cancel = document.getElementsByClassName("batal")[0];
    button_cancel.addEventListener('click', () => {
        close_form();
    });
}

function setFormSubmit() {
    form = document.querySelector('form');
    form.onsubmit = function(e) {
        e.preventDefault();
        save(soal.getContents());
        return false;
    };
}

function save(data) {
    $.ajax({
        url: '../save',
        method: 'post',
        data: {
            data: JSON.parse(JSON.stringify(data)),
            token: document.querySelector('input[name=token]').value,
        },
        dataType: 'json',
        success: function(response) {
            document.querySelector('input[name=token]').value = response.token
        }
    })
}

function load_add_form(e) {
    (e || window.event).preventDefault();

    fetch(button_add.dataset.href)
        .then((response) => response.text())
        .then((html) => {
            create_form.classList.toggle('d-none');
            list.classList.toggle('d-none');
            create_form.innerHTML = html;

            setEditor();
            setButtonCancel();
            setFormSubmit();

        })
        .catch((error) => {
            console.warn(error);
        });
}