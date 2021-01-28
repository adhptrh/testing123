const bmajor = document.getElementById("bmajor");
const bgrade = document.getElementById("bgrade");
const token_form = document.querySelector('input[name=token]');
const id = document.querySelector('input[name=id]');
let token = 0,
    major = 0,
    grade = '';

function setToken(data) {
    token = data;
    token_form.value = data;
}

function setMajor(data) {
    major = data;
}

function setGrade(data) {
    opsi = '<option></option>';
    data.forEach((value, index) => {
        opsi += '<option value="' + value.id + '">' + value.name + '</option>';
    });
    grade = opsi;
}

function setTgrade(data) {
    grade = data;
}

setToken(token_form.value);

bmajor.onchange = () => {
    setMajor(bmajor.value);
    loadGrade();
}

function loadGrade() {
    url = (id.value == 0) ? '../../reference/grade_ref/get_json/' + major : '../../../reference/grade_ref/get_json/' + major;

    $.ajax({
        url: url,
        method: 'post',
        data: {
            token: token,
        },
        dataType: 'json',
        success: function(response) {
            setToken(response.token);
            setGrade(response.grade);
            bgrade.innerHTML = grade;
        }
    })
}