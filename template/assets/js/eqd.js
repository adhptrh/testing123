const button_add = document.getElementsByClassName("add")[0];
const create_form = document.getElementsByClassName("create")[0];
const list = document.getElementsByClassName("list")[0]; // Frame of list
const eq_list = document.getElementById("eq_list"); //list of exam_questions
const token = document.getElementById("eq_list").getAttribute('data-token');
let soal = 0,
    opsi_a = 0,
    opsi_b = 0,
    opsi_c = 0,
    opsi_d = 0,
    opsi_e = 0,
    content = 0,
    dsimpan = 0,
    keyword = 0;
let button_cancel, form, item;

getList();

button_add.addEventListener("click", () => {
    load_add_form();
})

function setButtonOption() {
    document.querySelectorAll('.opsi').forEach(item => {
        item.addEventListener('click', event => {
            setButtonOptionAllClear();
            item.classList.add('btn-success')
            item.classList.remove('btn-outline-success')
            keyword = item.getAttribute('data-opsi');
        })
    })
}

function setButtonOptionAllClear() {
    document.querySelectorAll('.opsi').forEach(item => {
        item.classList.remove('btn-success');
        item.classList.add('btn-outline-success');
    })
}

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
        if (SetFormSubmitCek()) {
            save({
                master_soal_id: document.querySelector('input[name=master_soal_id]').value,
                soal: soal.getContents(),
                opsi_a: opsi_a.getContents(),
                opsi_b: opsi_b.getContents(),
                opsi_c: opsi_c.getContents(),
                opsi_d: opsi_d.getContents(),
                opsi_e: opsi_e.getContents(),
                keyword: keyword,
            });
        }

        return false;
    };
}

function SetFormSubmitCek() {
    if (soal.getLength() < 2 || opsi_b.getLength() < 2 || opsi_c.getLength() < 2 || opsi_d.getLength() < 2 || opsi_e.getLength() < 2 || opsi_a.getLength() < 2 || keyword == 0) {
        Swal.fire({
            title: 'Peringatan',
            text: "Mohon lengkapi seluruh isian",
            icon: 'warning',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Baiklah',
        })
        return false;
    } else {
        return true;
    }
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
            if (response.status != 200) {
                Swal.fire({
                    title: 'Peringatan',
                    text: "Terjadi kesalahan, silahkan hubungi Administrator",
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Baiklah',
                })
            } else {
                close_form();
                getList();
            }
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
            setButtonOption();
            setFormSubmit();

        })
        .catch((error) => {
            console.warn(error);
        });
}

function getList() {
    $.ajax({
        url: '../reload/' + button_add.getAttribute('data-id'),
        method: 'get',
        data: {
            // token: token,
        },
        dataType: 'json',
        success: function(response) {
            makeSoal(response.data)
        }
    })
}

function makeSoal(data) {
    item = '';
    let no = 1;
    data.forEach(function(value, index) {
        item += '<h5>No. ' + no++ + '</h5>';
        item += '<p>' + value['question'] + '</p>'
        item += '<h6>Opsi A</h6>'
        item += '<p>' + value['opsi_a'] + '</p>'
        item += '<h6>Opsi B</h6>'
        item += '<p>' + value['opsi_b'] + '</p>'
        item += '<h6>Opsi C</h6>'
        item += '<p>' + value['opsi_c'] + '</p>'
        item += '<h6>Opsi D</h6>'
        item += '<p>' + value['opsi_d'] + '</p>'
        item += '<h6>Opsi E</h6>'
        item += '<p>' + value['opsi_e'] + '</p>'
        item += '<h6>Kunci Jawaban</h6>'
        item += '<p>' + value['keyword'] + '</p>'
        item += '<hr>';
    })

    eq_list.innerHTML = item;

}