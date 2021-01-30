const bperiod = document.getElementById("bperiod");

const token_form = document.querySelector('input[name=token]');
const id = document.querySelector('input[name=id]');
const bexam = document.getElementById("bexam");
let token = 0,
    period = 0,
    exam = '';

const start = new Cleave('#start', {
    time: true,
    timePattern: ['h', 'm']
});

const finish = new Cleave('#finish', {
    time: true,
    timePattern: ['h', 'm']
});

function setToken(data) {
    token = data;
    token_form.value = data;
}

function setPeriod(data) {
    period = data;
}

function setExam(data) {
    opsi = '<option></option>';
    data.forEach((value, index) => {
        opsi += '<option value="' + value.id + '">' + value.exam + '</option>';
    });
    exam = opsi;
}

setToken(token_form.value);

bperiod.onchange = () => {
    setPeriod(bperiod.value);
    loadExam();
}

function loadExam() {
    $('#bexam').val([]).trigger('change');

    url = (id.value == 0) ? '../../app/exam_schedule/get_json/' : '../../../data/exam_schedule/get_json/';

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
            setExam(response.exam);
            bexam.innerHTML = exam;
        }
    })
}