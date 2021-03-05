const bgrade = document.getElementById("bgrade");
const token_form = document.querySelector('input[name=token]');
const bperiod = document.getElementById("bperiod");
const dContent = document.getElementById("dContent");
const fContent = document.getElementById("fContent");
const hContent = document.getElementById("hContent");
const bCloseAddForm = document.getElementById("bCloseAddForm");
const dtp_cari = document.querySelector("dtp_cari");

let token = 0,
    period = 0,
    grade = '',
    html = '',
    studentGrades = 0,
    students = 0,
    student = 0,
    orders = 0,
    order = 0,
    ID = 0,
    gradePeriod = 0;

function setID(data) {
    ID = data;
}

function setStudent(data) {
    student = data;
}

function setStudentGrades(data) {
    studentGrades = data;
}

function setOrder(data) {
    order = data;
}

function setOrders(data) {
    orders = data;
}

function setToken(data) {
    token = data;
    token_form.value = data;
}

function setPeriod(data) {
    period = data;
}

function setStudents(data) {
    students = data;
}

function setGradePeriod(data) {
    gradePeriod = data;
}

function setGrade(data) {
    opsi = '<option></option>';
    data.forEach((value, index) => {
        opsi += '<option value="' + value.id + '">' + value.kelas + '</option>';
    });
    grade = opsi;
}

setToken(token_form.value);

bperiod.onchange = () => {
    fContent.classList.add('d-none');
    setPeriod(bperiod.value);
    loadGrade();
}

function loadGrade() {
    url = '../data/grade_period/get_json/';
    $.ajax({
        url: url,
        method: 'post',
        data: {
            token: token,
            filter: {
                'period': period
            }
        },
        dataType: 'json',
        success: function(response) {
            setToken(response.token);
            setGrade(response.grade);
            bgrade.innerHTML = grade;
        }
    })
}

function loadOrders() {
    url = '../data/student_grade/get_orders/';
    $.ajax({
        url: url,
        method: 'post',
        data: {
            token: token,
        },
        dataType: 'json',
        success: function(response) {
            setToken(response.token);
            setOrders(response.data);
        }
    })
}

bgrade.onchange = () => {
    fContent.classList.add('d-none');
    setGradePeriod(bgrade.value);
    loadStudentGrade();
}

bCloseAddForm.addEventListener('click', () => {
    loadStudentGrade()
})

loadOrders();

function createTableList() {
    dContent.innerHTML = `
    <div class="row">
    <div class="col-md-6">
      <div class="input-group mg-b-10">
        <div class="input-group-prepend">
          <span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>
        </div>
        <input type="text" class="form-control dtp_cari" placeholder="Cari di sini" aria-label="Username" aria-describedby="basic-addon1">
      </div>
    </div>
    <div class="col-md-6 d-none d-md-block">
        <a id="bAdd" href="#" class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5 float-right"><i class="fa fa-plus"></i> Tambah</a>
        <a target="_blank" id="bCreateCard" href="student_grade/card_print/${bgrade.value}" class="btn btn-sm pd-x-15 btn-success btn-uppercase mg-l-5 float-right"><i class="fa fa-print"></i> Cetak Kartu Ujian</a>
    </div>
    </div>
    <table class='dtable table table-striped'>
        <thead>
            <tr>
                <th>#</th>
                <th style="width:20%">Aksi</th>
                <th>Nama</th>
                <th>NISN</th>
                <th>Pilih Sesi</th>
            </tr>
        </thead>
        <tbody id='tStudentGrade'>
        </tbody>
    </table>
    `;

    hContent.innerHTML = 'Daftar Siswa';
    bCloseAddForm.classList.add('d-none');

    const bAdd = document.getElementById('bAdd');
    bAdd.addEventListener('click', () => {
        loadStudentNonGrade();
    })
}

function createTableAdd() {
    dContent.innerHTML = `
    <div class="row">
    <div class="col-md-6">
      <div class="input-group mg-b-10">
        <div class="input-group-prepend">
          <span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>
        </div>
        <input type="text" class="form-control dtp_cari" placeholder="Cari di sini" aria-label="Username" aria-describedby="basic-addon1">
      </div>
    </div>
    <div class="col-md-6 d-none d-md-block">
      <a id="bAdd" href="#" class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5 float-right"><i class="fa fa-plus"></i> Tambah</a>
    </div>
    </div>
    <table class='dtable table table-striped'>
        <thead>
            <tr>
                <th>#</th>
                <th style="width:20%">Aksi</th>
                <th>Nama</th>
                <th>NISN</th>
            </tr>
        </thead>
        <tbody id='tStudentGrade'>
        </tbody>
    </table>
    `;

    bAdd.classList.add('d-none');
    hContent.innerHTML = 'Silahkan pilih siswa untuk menambahkan';
    bCloseAddForm.classList.remove('d-none');
}

function deleteStudent() {
    url = `../data/student_grade/delete/`;

    $.ajax({
        url: url,
        method: 'post',
        data: {
            token: token,
            ID: ID,
        },
        dataType: 'json',
        success: function(response) {
            setToken(response.token);
            loadStudentGrade();
        }

    })
}

function addStudent() {
    url = `../data/student_grade/save/`;

    $.ajax({
        url: url,
        method: 'post',
        data: {
            token: token,
            student: student,
            grade_period: gradePeriod,
            period: period,
        },
        dataType: 'json',
        success: function(response) {
            setToken(response.token);
            // loadStudentGrade();
        }

    })
}

function generate_row() {
    createTableList();
    const tableRef = document.querySelector('.dtable').getElementsByTagName('tbody')[0];
    studentGrades.forEach((item, index) => {
        html = `
            <tr>
            <td>${index + 1}</td>
            <td><button data-id='${item['id']}' class='btn btn-sm btn-danger bDelete'>Hapus</button></td>
            <td>${item['name']}</td>
            <td>${item['nisn']}</td>
            <td class='order'></td>
            </tr>
        `;
        tableRef.insertRow().innerHTML = html;

        const fOrder = document.getElementsByClassName('order');
        let bOrders = '';

        orders.forEach((item1) => {
            let button = 'btn-outline-success';
            if (item1['name'] == item['order']) {
                button = 'btn-success';
            }
            bOrders += `<button data-student-grade-id="${item['id']}" data-order-id="${item1['id']}" class="bOrder btn btn-sm ${button} mg-r-5">${item1['name']}</button>`;
        });
        fOrder[index].innerHTML = bOrders;

        bOrders = fOrder[index].querySelectorAll('.bOrder');

        bOrders.forEach((item2) => {
            item2.addEventListener('click', (e) => {
                setID(item2.getAttribute('data-student-grade-id'));
                setOrder(item2.getAttribute('data-order-id'));
                saveOrder();
                bOrders.forEach((item2) => {
                    item2.classList.remove('btn-success');
                    item2.classList.add('btn-outline-success');
                });
                item2.classList.remove('btn-outline-success');
                item2.classList.add('btn-success');
            })
        });

        const bDelete = document.getElementsByClassName('bDelete');
        bDelete[index].addEventListener('click', (e) => {
            setID(e.target.getAttribute('data-id'));
            isConfirm();
        })

    });
    tInit();
}

function generate_row_nongrade() {

    createTableAdd();
    const tableRef = document.querySelector('.dtable').getElementsByTagName('tbody')[0];
    students.forEach((item, index) => {
        html = `
            <tr id='r${index+1}'>
            <td>${index + 1}</td>
            <td><button data-id='${item['id']}' class='btn btn-sm btn-success bAddStudent'>Tambahkan</button></td>
            <td>${item['name']}</td>
            <td>${item['nisn']}</td>
            </tr>
        `;
        tableRef.insertRow().innerHTML = html;

        const bAddStudent = document.getElementsByClassName('bAddStudent');
        bAddStudent[index].addEventListener('click', (e) => {
            setStudent(e.target.getAttribute('data-id'));
            addStudent();
            e.target.closest("tr").classList.add('d-none');
        })
    });

    tInit();
}

function loadStudentGrade() {
    fContent.classList.remove('d-none');
    dContent.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div>';
    url = '../data/student_grade/get_student_grade_json';

    $.ajax({
        url: url,
        method: 'post',
        data: {
            token: token,
            filter: {
                'gradePeriod': gradePeriod
            }
        },
        dataType: 'json',
        success: function(response) {
            dContent.innerHTML = '';
            setToken(response.token);
            setStudentGrades(response.data);
            generate_row();
        }

    })
}

function loadStudentNonGrade() {
    fContent.classList.remove('d-none');
    dContent.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div>';
    url = '../data/student_grade/get_student_nongrade_json';
    $.ajax({
        url: url,
        method: 'post',
        data: {
            token: token,
            filter: {
                'gradePeriod': gradePeriod
            }
        },
        dataType: 'json',
        success: function(response) {
            dContent.innerHTML = '';
            setToken(response.token);
            setStudents(response.data);
            generate_row_nongrade();
        }

    })
}

function saveOrder() {

    url = '../data/student_grade/set_order';
    $.ajax({
        url: url,
        method: 'post',
        data: {
            token: token,
            order_id: order,
            student_grade_id: ID,
        },
        dataType: 'json',
        success: function(response) {
            setToken(response.token);
        }

    })
}

function isConfirm() {
    Swal.fire({
        title: 'Peringatan',
        text: "Apakah Anda yakin akan menghapus data ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus saja!',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            deleteStudent();
        }
    })
}