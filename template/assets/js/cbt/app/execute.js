const token_form = document.querySelector('input[name=token]'),
    today = new Date(),
    bExamItems = document.querySelectorAll('.bExamItems');

let timeTarget = new Date().getTime(),
    timeServerNow = new Date().getTime(),
    notif = 0,
    numbersOfExam = [],
    examDetail = [],
    next = 0,
    prev = 0,
    studentAnswer = '',
    token = 0,
    answers = [],
    examItems = [],
    examItem = 0;

//set_exam_details
//set_next
//set_prev
//answer, lock, set_studentAnswer, post, set_next, set_prev, unlock

bExamItems.forEach((item, index) => {
    item.addEventListener('click', () => {

        examItem = item.getAttribute('data-exam-item');
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

    // Set data-number
    number = index + 1;
    item.setAttribute('data-number', number);
});

loadingLandingData();

function loadExamDetails() {
    $.ajax({
        url: '../get_exam_detail/',
        method: 'post',
        data: {
            token: token_form.value,
            // exam_question_id: examQuestion.getAttribute('data-value'),
            // exam_schedule_id: examSchedule.getAttribute('data-value'),
            // student_grade_exam_id: studentGradeExam.getAttribute('data-value'),
            // exam_items: examItems,
            exam_item: examItem,
        },
        dataType: 'json',
        success: function(response) {
            token_form.value = response.token;
            console.log(response);
        }
    })
}

function loading(param = 0) {
    tNotifInfo.innerHTML = param;
    if (param == 0) {
        fNotifInfo.classList.add('d-none');
    } else {
        fNotifInfo.classList.remove('d-none');
    }
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
            bExamItems[0].click();
        }
    })
}