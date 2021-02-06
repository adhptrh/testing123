const token_form = document.querySelector('input[name=token]'),
    today = new Date(),
    bExamItems = document.querySelectorAll('.bExamItems');

let timeTarget = new Date().getTime(),
    timeServerNow = new Date().getTime(),
    notif = 0,
    numberOfExam = 0,
    numberOfExamMax = 0,
    examDetail = {
        'question': 0,
        'opsi_a': 0,
        'opsi_b': 0,
        'opsi_c': 0,
        'opsi_d': 0,
        'opsi_e': 0,
    },
    studentAnswer = '',
    token = 0,
    message = 0,
    answers = '',
    exam = 0; // ganti value nya dengan examp_temps_id

bNext.addEventListener('click', () => {
    next();
})

bPrev.addEventListener('click', () => {
    prev();
})

bExamItems.forEach((item, index) => {
    item.addEventListener('click', () => {
        exam = item.getAttribute('data-exam-item'); // ganti value nya dengan examp_temps_id
        numberOfExam = index;
        loadExamDetails();

        // Set all item clear
        bExamItems.forEach(item1 => {
            item1.classList.add('btn-outline-secondary');
            item1.classList.remove('btn-secondary');
        })

        // Set this item current
        item.classList.add('btn-secondary');
        item.classList.remove('btn-outline-secondary');
    })
});

loadingLandingData();

function loadExamDetails() {
    loadIndicator.classList.remove('d-none')
    fExamDetail.classList.add('d-none')

    $.ajax({
        url: '../get_exam_detail/',
        method: 'post',
        data: {
            token: token_form.value,
            exam_item: exam,
        },
        dataType: 'json',
        success: function(response) {
            token_form.value = response.token;
            examDetail.question = response.question;
            examDetail.opsi_a = response.opsi_a;
            examDetail.opsi_b = response.opsi_b;
            examDetail.opsi_c = response.opsi_c;
            examDetail.opsi_d = response.opsi_d;
            examDetail.opsi_e = response.opsi_e;
            showExamDetails();
        }
    })
}

function showExamDetails() {
    loadIndicator.classList.add('d-none');
    tExamDetail.innerHTML = imageShow(examDetail['question']);
    tOpsiA.innerHTML = imageShow(examDetail['opsi_a']);
    tOpsiB.innerHTML = imageShow(examDetail['opsi_b']);
    tOpsiC.innerHTML = imageShow(examDetail['opsi_c']);
    tOpsiD.innerHTML = imageShow(examDetail['opsi_d']);
    tOpsiE.innerHTML = imageShow(examDetail['opsi_e']);
    fExamDetail.classList.remove('d-none')
}

function showZero(x) {
    return data = (x < 10) ? '0' + x : x;
}

function showTimeLeft() {
    x = setInterval(function() {

        // Find the distance between now and the count down date
        var distance = timeTarget - timeNow;
        timeNow = timeNow + 1000;

        // Time calculations for days, hours, minutes and seconds
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in the element with id="demo"
        tTimeLeft.innerHTML = showZero(hours) + ":" + showZero(minutes) + ":" + showZero(seconds);

        // If the count down is finished, write some text
        if (distance < 0) {
            clearInterval(x);
            tTimeLeft.innerHTML = "HABIS";
        }
    }, 1000);
}

function loadingLandingData() {
    $.ajax({
        url: '../get_landing_data/',
        method: 'post',
        data: {
            token: token_form.value,
            exam_schedule_id: examSchedule.getAttribute('data-value'),
            exam_question_id: examQuestion.getAttribute('data-value'),
        },
        dataType: 'json',
        success: function(response) {
            token_form.value = response.token;
            timeTarget = new Date(response.time_left * 1000).getTime();
            timeNow = new Date(response.time_server_now * 1000).getTime();
            showTimeLeft();
            asignBExamItems(response.exam_questions);
            numberOfExamMax = response.number_of_exam;
            bExamItems[0].click();
        }
    })
}

function asignBExamItems(data) {
    data.forEach((item, index) => {
        bExamItems[index].setAttribute('data-exam-item', item);
    });
}

function imageShow(data) {
    data = data.replace(/upload\/img/g, "<img src='" + baseURL.getAttribute('data-value') + "upload/img");
    return data.replace(/.png/g, ".png'>");
}

function hitAnswer() {
    // close show info
    // lock()
    // set_answer
    // save(answer, exam)
    // if 200 -> set bNumberButtonColor -> unlock()
    // if NOT 200 -> show info -> unlock()
}

function lock(status = 0) {
    if (status == 0) {
        // set opsi_a - ragu2 disabled
        // set bnUmbers disabled
        // set bNext/Prev disabled
    } else {
        // set opsi_a - ragu2 disabled-false
        // set bnUmbers disabled-false
        // set bNext/Prev disabled-false
    }
}

function save() {
    $.ajax({
        url: '../save/',
        method: 'post',
        data: {
            token: token_form.value,
            answer: answers,
            exam: exam,
        },
        dataType: 'json',
        success: function(response) {
            token_form.value = response.token;
            //get respon
        }
    })
}

function showInfo(message = 0) {
    tNotif.innerHTML = message;
    if (param == 0) {
        fNotif.classList.add('d-none');
    } else {
        fNotif.classList.remove('d-none');
    }
}

function bNumberButtonColor() {

}

function next() {
    if (numberOfExam < (numberOfExamMax - 1)) {
        bExamItems[numberOfExam + 1].click();
    }
}

function prev() {
    if (numberOfExam > 0) {
        bExamItems[(numberOfExam - 1)].click();
    }
}