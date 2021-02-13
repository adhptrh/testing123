const token_form = document.querySelector('input[name=token]'),
    today = new Date(),
    bExamItems = document.querySelectorAll('.bExamItems'),
    bOpsi = document.querySelectorAll('input[name="bOpsi"]');

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
        'answer': 0,
    },
    studentAnswer = '',
    token = 0,
    message = 0,
    answer = '',
    exam = 0, // exam_temp_id
    examQuestionDetail = 0;

if (roller.getAttribute('data-height') == 'minimal') {
    footer.classList.add('fixed-bottom');
} else {
    footer.classList.remove('fixed-bottom');
}

bNext.addEventListener('click', () => {
    next();
})

bPrev.addEventListener('click', () => {
    prev();
})

bOpsi.forEach((item, index) => {
    item.addEventListener('click', () => {
        lock();
        answer = item.getAttribute('data-value');
        hitAnswer();
    })
});

bExamItems.forEach((item, index) => {
    item.addEventListener('click', () => {
        if (item.getAttribute('data-is-last-exam-item') == 1) {
            fBFinish.classList.remove('d-none');
        } else {
            fBFinish.classList.add('d-none');
        }

        lock();
        showInfo();
        exam = item.getAttribute('data-exam-item');
        examQuestionDetail = item.getAttribute('data-exam-question-detail');
        numberOfExam = index;
        loadExamDetails();

        // Set all item clear
        bExamItems.forEach(item1 => {
            if (item1.getAttribute('data-answer') == 0) {
                item1.classList.add('btn-outline-secondary');
                item1.classList.remove('btn-secondary');
            }
        })

        // Mempertahakan tanda sudah dijawab (jika sudah dijawab)
        answer = item.getAttribute('data-answer');
        if (answer != 0) {
            // tandai sudah pernah di jawab
            bNumberButtonColor();
        } else {
            // Set this item current
            item.classList.add('btn-secondary');
            item.classList.remove('btn-outline-secondary');
        }

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
            examDetail.answer = response.answer;
            showExamDetails();
            lock(false);
        },
        error: function() {
            Swal.fire({
                title: 'Peringatan',
                text: 'Aplikasi tidak berhasil mengunduh detail soal, mohon hubungi penyelenggara ujian.',
                icon: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ya',
            })
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
    fExamDetail.classList.remove('d-none');
    noExam.innerHTML = (numberOfExam + 1)

    if (examDetail['answer'] == 'opsi_a') {
        ftOpsiA.checked = true;
    } else if (examDetail['answer'] == 'opsi_b') {
        ftOpsiB.checked = true;
    } else if (examDetail['answer'] == 'opsi_c') {
        ftOpsiC.checked = true;
    } else if (examDetail['answer'] == 'opsi_d') {
        ftOpsiD.checked = true;
    } else if (examDetail['answer'] == 'opsi_e') {
        ftOpsiE.checked = true;
    } else if (examDetail['answer'] == 'dubious') {
        ftOpsiX.checked = true;
    } else {
        ftOpsiA.checked = false;
        ftOpsiB.checked = false;
        ftOpsiC.checked = false;
        ftOpsiD.checked = false;
        ftOpsiE.checked = false;
        ftOpsiX.checked = false;
    }
}

bFinish.addEventListener('click', () => {
    count_dubious = 0;
    count_answer_is_zero = 0;
    bExamItems.forEach((item) => {
        if (item.getAttribute('data-answer') == 0) {
            count_answer_is_zero++;
        }

        if (item.getAttribute('data-answer') == 'dubious') {
            count_dubious++;
        }
    });

    if (count_dubious > 0 || count_answer_is_zero > 0) {
        confirmTimeOut(`Anda memiliki ${count_dubious} soal yang masih ragu-ragu dan ${count_answer_is_zero} soal yang belum dijawab. Apakah Anda yakin akan menyelesaikan ujian ini?`);
    } else {
        confirmTimeOut();
    }
})

function showZero(x) {
    return data = (x < 10) ? '0' + x : x;
}

function showTimeLeft() {
    x = setInterval(function() {

        // Find the distance between now and the count down date
        const distance = timeTarget - timeServerNow;
        timeServerNow = timeServerNow + 1000;

        // Time calculations for days, hours, minutes and seconds
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in the element with id="demo"
        tTimeLeft.innerHTML = showZero(hours) + ":" + showZero(minutes) + ":" + showZero(seconds);

        if (hours < 1 && minutes < 10 && seconds == 59) {
            tNotifWarningTimeOut.innerHTML = `Waktu mengerjakan ujian tinggal ${minutes} menit lagi`;
            fNotifWarningTimeOut.classList.remove('d-none');
        }

        // If the count down is finished, write some text
        if (distance < 0) {
            clearInterval(x);
            tTimeLeft.innerHTML = "HABIS";
            lock();
            timeOut(true);
        }
    }, 1000);
}

function confirmTimeOut(message = 'Apakah Anda yakin akan menyelesaikan ujian ini') {
    Swal.fire({
        title: 'Peringatan 1',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            confirmTimeOut2();
        }
    })
}

function confirmTimeOut2(message = 'Jika Anda yakin ingin menyelesaikan ujian, silahkan cheklist di bawah ini') {
    Swal.fire({
        title: 'Peringatan 2',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal',
        input: 'checkbox',
        inputPlaceholder: 'Saya yakin'
    }).then((result) => {
        if (result.value) {
            timeOut();
        }
    })
}

function timeOut(is_time_out = false) {
    if (is_time_out) {
        url = '../closing/' + studentGradeExam.getAttribute('data-value') + '/1';
    } else {
        url = '../closing/' + studentGradeExam.getAttribute('data-value');
    }

    $.ajax({
        url: url,
        method: 'post',
        data: {
            token: token_form.value,
        },
        success: function(response) {
            fTest.innerHTML = response;
            timeServerNow = timeTarget;
        }
    })
}

function loadingLandingData() {
    $.ajax({
        url: '../get_landing_data/',
        method: 'post',
        data: {
            token: token_form.value,
            exam_schedule_id: examSchedule.getAttribute('data-value'),
            exam_question_id: examQuestion.getAttribute('data-value'),
            student_grade_exam_id: studentGradeExam.getAttribute('data-value'),
        },
        dataType: 'json',
        success: function(response) {
            token_form.value = response.token;
            timeTarget = new Date(response.time_left * 1000).getTime();
            timeServerNow = new Date(response.time_server_now * 1000).getTime();
            showTimeLeft();
            asignBExamItems(response.exam_questions);
            numberOfExamMax = response.number_of_exam;
            bExamItems[0].click();
        }
    })
}

function asignBExamItems(data) {
    data.forEach((item, index) => {
        bExamItems[index].setAttribute('data-exam-item', item['id']);
        bExamItems[index].setAttribute('data-exam-question-detail', item['exam_question_detail_id']);
        if (item['answer']) {
            bExamItems[index].setAttribute('data-answer', item['answer']);
            if (item['answer'] == 'dubious') {
                bExamItems[index].classList.add('btn-warning');
                bExamItems[index].classList.remove('btn-outline-secondary');
                bExamItems[index].classList.remove('btn-secondary');
            } else {
                bExamItems[index].classList.add('btn-primary');
                bExamItems[index].classList.remove('btn-outline-secondary');
                bExamItems[index].classList.remove('btn-secondary');
            }
        }

    });
}

function imageShow(data) {
    data = data.replace(/upload\/img/g, "<img src='" + baseURL.getAttribute('data-value') + "upload/img");
    return data.replace(/.png/g, ".png'>");
}

function hitAnswer() {
    // close show info
    showInfo();

    // lock()
    lock();

    // save(answer, exam)
    save();
}

function save() {
    $.ajax({
        url: '../save/',
        method: 'post',
        data: {
            token: token_form.value,
            answer: answer,
            exam: exam,
            exam_question_detail_id: examQuestionDetail,
            student_grade_exam_id: studentGradeExam.getAttribute('data-value'),
        },
        dataType: 'json',
        success: function(response) {
            token_form.value = response.token;
            if (response.status == 200) {
                bExamItems[numberOfExam].setAttribute('data-answer', answer);
                bNumberButtonColor();
            } else {
                showInfo(response.message)
            }
            lock(false);
        },
        error: function() {
            Swal.fire({
                title: 'Peringatan',
                text: 'Aplikasi tidak berhasil menyimpan jawaban peserta ujian, mohon hubungi penyelenggara ujian.',
                icon: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ya',
            })
        }
    })
}

function showInfo(message = 0) {
    tNotif.innerHTML = message;
    if (message == 0) {
        fNotif.classList.add('d-none');
    } else {
        fNotif.classList.remove('d-none');
    }
}

function bNumberButtonColor() {
    if (answer == 'dubious') {
        bExamItems[numberOfExam].classList.add('btn-warning');
        bExamItems[numberOfExam].classList.remove('btn-outline-secondary');
        bExamItems[numberOfExam].classList.remove('btn-secondary');
    } else {
        bExamItems[numberOfExam].classList.add('btn-primary');
        bExamItems[numberOfExam].classList.remove('btn-warning');
        bExamItems[numberOfExam].classList.remove('btn-outline-secondary');
        bExamItems[numberOfExam].classList.remove('btn-secondary');
    }
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

function lock(status = true) {
    bOpsi.forEach(item => {
        item.disabled = status;
    })

    bExamItems.forEach(item => {
        item.disabled = status;
    })

    bPrev.disabled = status;
    bNext.disabled = status;
}