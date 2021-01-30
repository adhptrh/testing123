const bgrade = document.getElementById("bgrade");
const token_form = document.querySelector('input[name=token]');
const id = document.querySelector('input[name=id]');
const bperiod = document.getElementById("bperiod");

let token = 0,
    period = 0,
    grade = '';

function setToken(data) {
    token = data;
    token_form.value = data;
}

function setPeriod(data) {
    period = data;
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
    setPeriod(bperiod.value);
    loadGrade();
}

function loadGrade() {
    url = (id.value == 0) ? '../../data/grade_period/get_json/' : '../../../data/grade_period/get_json/';

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