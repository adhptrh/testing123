const bgrade = document.getElementById("bgrade");
const token_form = document.querySelector('input[name=token]');
const bperiod = document.getElementById("bperiod");
const dContent = document.getElementById("dContent");
const fContent = document.getElementById("fContent");
const dtp_cari = document.querySelector("dtp_cari");

let token = 0,
    period = 0,
    grade = '',
    html = '',
    studentGrade = 0,
    gradePeriod = 0;

function setToken(data) {
    token = data;
    token_form.value = data;
}

function setPeriod(data) {
    period = data;
}

function setStudentGrade(data) {
    studentGrade = data;
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

bgrade.onchange = () => {
    fContent.classList.add('d-none');
    setGradePeriod(bgrade.value);
    loadStudentGrade();
}

function createTable() {
    fContent.classList.remove('d-none');
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
      <a href="#" class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5 float-right"><i class="fa fa-plus"></i> Tambah</a>
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
}

function generate_row() {
    createTable();
    const tableRef = document.querySelector('.dtable').getElementsByTagName('tbody')[0];
    studentGrade.forEach((item, index) => {
        html = `
            <tr>
            <td>${index + 1}</td>
            <td><button class='btn btn-sm btn-danger'>Hapus</button></td>
            <td>${item['name']}</td>
            <td>${item['nisn']}</td>
            </tr>
        `;
        tableRef.insertRow().innerHTML = html;
    });

    tInit();
}

function loadStudentGrade() {
    dContent.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div>';
    url = '../data/student_grade/get_student_grade_json';
    const xtoken = token;
    $.ajax({
        url: url,
        method: 'post',
        data: {
            token: xtoken,
            filter: {
                'gradePeriod': gradePeriod
            }
        },
        dataType: 'json',
        success: function(response) {
            dContent.innerHTML = '';
            setToken(response.token);
            setStudentGrade(response.data);
            generate_row();
        }

    })
}