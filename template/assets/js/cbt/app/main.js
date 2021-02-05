const token_form = document.querySelector('input[name=token]'),
    // aside = document.querySelector('.aside'),
    // tOrder = document.getElementById("tOrder"),
    // tStudy = document.getElementById("tStudy"),
    // tTimeLeft = document.getElementById("tTimeLeft"),
    // tNotifWarning = document.getElementById("tNotif"),
    // tNotifInfo = document.getElementById("tNotifInfo"),
    // fNotifInfo = document.getElementById("fNotifInfo"),
    // tExamDetail = document.getElementById("tExamDetail"),
    // bNext = document.getElementById("bNext"),
    // bPrev = document.getElementById("bPrev"),
    // exam = document.querySelector("#exam"),
    // examQuestionDetails = document.querySelector("#examQuestionDetails"),
    // tListOfNumber = document.getElementById("tListOfNumber"),
    today = new Date();

let timeLeft = 0,
    timeServerNow = 0,
    notif = 0,
    numbersOfExam = [],
    examDetail = [],
    next = 0,
    prev = 0,
    studentAnswer = '',
    token = 0;

// register();

// function is_register() {
//     loading('Memeriksa register peserta ujian');
// }

// function register() {
//     $.ajax({
//         url: '../../get_header_data/' + examSchedule,
//         method: 'post',
//         data: {
//             token: token_form.value,
//         },
//         dataType: 'json',
//         success: function(response) {
//             token_form.value = response.token;
//             tStudy.innerHTML = response.study;
//             tOrder.innerHTML = response.order;
//             timeLeft = new Date(response.time_left * 1000).getTime();
//             timeServerNow = new Date(response.time_server_now * 1000).getTime();
//             showTimeLeft();
//         }
//     })
}

//set_numbers_of_exam
//set_exam_details
//set_next
//set_prev
//answer, lock, set_studentAnswer, post, set_next, set_prev, unlock
loadingTime();

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
        var distance = timeLeft - timeServerNow;

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

function loadingTime() {
    $.ajax({
        url: '../../get_header_data/' + examSchedule,
        method: 'post',
        data: {
            token: token_form.value,
        },
        dataType: 'json',
        success: function(response) {
            token_form.value = response.token;
            // tStudy.innerHTML = response.study;
            // tOrder.innerHTML = response.order;
            timeLeft = new Date(response.time_left * 1000).getTime();
            timeServerNow = new Date(response.time_server_now * 1000).getTime();
            showTimeLeft();
        }
    })
}