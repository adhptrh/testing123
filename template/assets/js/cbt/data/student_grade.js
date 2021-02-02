const bgrade = document.getElementById("bgrade");
const token_form = document.querySelector('input[name=token]');
const bperiod = document.getElementById("bperiod");
const fContent = document.getElementById("dcontent");

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
    fContent.innerHTML = '';
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
    fContent.innerHTML = '';
    setGradePeriod(bgrade.value);
    loadStudentGrade();
}

function createTable() {
    fContent.innerHTML = `<table class='dtable table table-striped'>
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
    </table>`;
}

function generate_row() {
    const tableRef = document.querySelector('.dtable').getElementsByTagName('tbody')[0];
    studentGrade.forEach((item, index) => {
        html = `
            <tr>
            <td>${index + 1}</td>
            <td><button class='btn btn-sm btn-warning'>Hapus</button></td>
            <td>${item['name']}</td>
            <td>${item['nisn']}</td>
            </tr>
        `;
        tableRef.insertRow().innerHTML = html;
    });
}

function loadStudentGrade() {
    fContent.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"> loading ...</div>';
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
            setToken(response.token);
            setStudentGrade(response.data);
            createTable();
            generate_row();
        }

    })
}