const button_add = document.getElementsByClassName("add")[0];
const create_form = document.getElementsByClassName("create")[0];
const list = document.getElementsByClassName("list")[0]; // Frame of list
const eq_list = document.getElementById("eq_list"); //list of exam_questions
const token = document.getElementById("eq_list").getAttribute('data-token');
const falert = document.getElementById("falert");
const malert = document.getElementById("malert");
const span_jsoal = document.getElementById("jsoal");
let soal = 0,
    opsi_a = 0,
    opsi_b = 0,
    opsi_c = 0,
    opsi_d = 0,
    opsi_e = 0,
    content = 0,
    dsimpan = 0,
    keyword = 0,
    base_url = 'http://localhost/';

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

function xdelete(data) {
    Swal.fire({
        title: 'Peringatan',
        text: "Apakah Anda yakin akan menghapus data ini? ",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus saja!',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../delete/' + data.getAttribute('data-id'),
                method: 'post',
                data: {
                    token: document.querySelector('#eq_list').getAttribute('data-token'),
                },
                dataType: 'json',
                success: function(response) {
                    document.querySelector('#eq_list').setAttribute('data-token', response.token);
                    if (response.status != 200) {
                        Swal.fire({
                            title: 'Peringatan',
                            text: "Terjadi kesalahan, silahkan hubungi Administrator",
                            icon: 'warning',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Baiklah',
                        })
                    } else {
                        falert.classList.remove('d-none');
                        malert.innerHTML = response.message;
                        getList();
                    }
                }
            })
        }
    })
}

function xedit(data) {
    fetch(base_url + '/app/exam_question_detail/edit/' + data.getAttribute('data-id'))
        .then((response) => response.text())
        .then((html) => {
            create_form.classList.toggle('d-none');
            list.classList.toggle('d-none');
            create_form.innerHTML = html;

            setEditor();
            loadExamDetail(data.getAttribute('data-id'));
            setButtonCancel();
            setButtonOption();
            setFormSubmit('update');

            keyword = document.getElementsByClassName("btn-keyword")[0].getAttribute('data-opsi');

        })
        .catch((error) => {
            console.warn(error);
        });
}

// function setButtonOptionAllClear() {
//     document.querySelectorAll('.opsi').forEach(item => {
//         item.classList.remove('btn-success');
//         item.classList.add('btn-outline-success');
//     })
// }

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
                ['bold', 'italic', 'underline', 'strike', 'image'],
                ['link'],
                ['blockquote'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                [{ 'script': 'sub' }, { 'script': 'super' }],
                ['align', { 'align': 'center' }, { 'align': 'right' }, { 'align': 'justify' }],
            ]
        },
        placeholder: 'Ketikkan soal di sini ...',
        theme: 'snow' // or 'bubble'
    });

    opsi_a = new Quill('#opsi_a', {
        modules: {
            toolbar: [
                [{ header: [1, 2, false] }],
                ['bold', 'italic', 'underline', 'strike', 'image'],
                ['link'],
                ['blockquote'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                [{ 'script': 'sub' }, { 'script': 'super' }],
                ['align', { 'align': 'center' }, { 'align': 'right' }, { 'align': 'justify' }]
            ]
        },
        placeholder: 'Ketikkan opsi di sini ...',
        theme: 'snow' // or 'bubble'
    });

    opsi_b = new Quill('#opsi_b', {
        modules: {
            toolbar: [
                [{ header: [1, 2, false] }],
                ['bold', 'italic', 'underline', 'strike', 'image'],
                ['link'],
                ['blockquote'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                [{ 'script': 'sub' }, { 'script': 'super' }],
                ['align', { 'align': 'center' }, { 'align': 'right' }, { 'align': 'justify' }]
            ]
        },
        placeholder: 'Ketikkan opsi di sini ...',
        theme: 'snow' // or 'bubble'
    });

    opsi_c = new Quill('#opsi_c', {
        modules: {
            toolbar: [
                [{ header: [1, 2, false] }],
                ['bold', 'italic', 'underline', 'strike', 'image'],
                ['link'],
                ['blockquote'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                [{ 'script': 'sub' }, { 'script': 'super' }],
                ['align', { 'align': 'center' }, { 'align': 'right' }, { 'align': 'justify' }]
            ]
        },
        placeholder: 'Ketikkan opsi di sini ...',
        theme: 'snow' // or 'bubble'
    });

    opsi_d = new Quill('#opsi_d', {
        modules: {
            toolbar: [
                [{ header: [1, 2, false] }],
                ['bold', 'italic', 'underline', 'strike', 'image'],
                ['link'],
                ['blockquote'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                [{ 'script': 'sub' }, { 'script': 'super' }],
                ['align', { 'align': 'center' }, { 'align': 'right' }, { 'align': 'justify' }]
            ]
        },
        placeholder: 'Ketikkan opsi di sini ...',
        theme: 'snow' // or 'bubble'
    });

    opsi_e = new Quill('#opsi_e', {
        modules: {
            toolbar: [
                [{ header: [1, 2, false] }],
                ['bold', 'italic', 'underline', 'strike', 'image'],
                ['link'],
                ['blockquote'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                [{ 'script': 'sub' }, { 'script': 'super' }],
                ['align', { 'align': 'center' }, { 'align': 'right' }, { 'align': 'justify' }]
            ]
        },
        placeholder: 'Ketikkan opsi di sini ...',
        theme: 'snow' // or 'bubble'
    });
}

function loadExamDetail(data) {
    $.ajax({
        url: '../data_for_edit/' + data,
        method: 'post',
        data: {
            token: document.querySelector('input[name=token]').value,
        },
        dataType: 'json',
        success: function(response) {
            // console.log(response);
            soal.setContents(response.soal);
            opsi_a.setContents(response.opsi_a);
            opsi_b.setContents(response.opsi_b);
            opsi_c.setContents(response.opsi_c);
            opsi_d.setContents(response.opsi_d);
            opsi_e.setContents(response.opsi_e);
            document.querySelector('input[name=token]').value = response.token;
        }
    })
}

function setButtonCancel() {
    button_cancel = document.getElementsByClassName("batal")[0];
    button_cancel.addEventListener('click', () => {
        close_form();
        document.getElementById('top_content').scrollIntoView();
    });
}

function setFormSubmit(method = 'save') {
    form = document.querySelector('form');
    form.onsubmit = function(e) {
        e.preventDefault();
        if (SetFormSubmitCek()) {
            data = {
                master_soal_id: document.querySelector('input[name=master_soal_id]').value,
                soal: soal.getContents(),
                opsi_a: opsi_a.getContents(),
                opsi_b: opsi_b.getContents(),
                opsi_c: opsi_c.getContents(),
                opsi_d: opsi_d.getContents(),
                opsi_e: opsi_e.getContents(),
                keyword: keyword,
            }

            if (method == 'save') {
                save(data);
            } else {
                data['id'] = document.querySelector('input[name=id]').value;
                save(data, 'update');
            }
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

function save(data, method = 'save') {
    $.ajax({
        url: '../' + method,
        method: 'post',
        data: {
            data: JSON.parse(JSON.stringify(data)),
            token: document.querySelector('input[name=token]').value,
        },
        dataType: 'json',
        success: function(response) {
            document.querySelector('#eq_list').setAttribute('data-token', response.token);
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
                falert.classList.remove('d-none');
                malert.innerHTML = response.message;
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
            base_url = response.base_url;
            makeSoal(response.data);
            span_jsoal.innerHTML = response.data.length;
            document.getElementById('top_content').scrollIntoView();
        }
    })
}

function makeSoal(data) {
    item = '';
    let no = 1;
    data.forEach(function(value, index) {
        item += '<div class="card mg-t-20">';
        item += '<div class="card-header d-flex align-items-center justify-content-between">';
        item += '<h6 class="mg-t-10">No. ' + no++ + '</h6>';
        item += '<div class="d-flex align-items-center tx-18">';
        item += '<button data-id="' + value['id'] + '" onclick="xedit(this)" class="btn btn-xs btn-info col-md mg-r-10">Edit</button>';
        item += '<button data-id="' + value['id'] + '" onclick="xdelete(this)" class="btn btn-xs btn-warning col-md delete">Hapus</button>';
        item += '</div>';
        item += '</div>';
        item += '<div class="card-body">';
        item += '<p>' + imageShow(value['question']) + '</p>'
        item += '<h6>Opsi A</h6>'
        item += '<p>' + imageShow(value['opsi_a']) + '</p>'
        item += '<h6>Opsi B</h6>'
        item += '<p>' + imageShow(value['opsi_b']) + '</p>'
        item += '<h6>Opsi C</h6>'
        item += '<p>' + imageShow(value['opsi_c']) + '</p>'
        item += '<h6>Opsi D</h6>'
        item += '<p>' + imageShow(value['opsi_d']) + '</p>'
        item += '<h6>Opsi E</h6>'
        item += '<p>' + imageShow(value['opsi_e']) + '</p>'
        item += '<h6>Kunci Jawaban</h6>'
        item += '<p>' + imageShow(value['keyword']) + '</p>'
        item += '</div>';
        item += '</div>';
    })

    eq_list.innerHTML = item;
}

function imageShow(data) {
    data = data.replace(/upload\/img/g, "<img src='" + base_url + "upload/img");
    return data.replace(/.png/g, ".png'>");
}