const bConfirmDataYes = document.querySelector('#bConfirmDataYes'),
    bConfirmDataNo = document.querySelector('#bConfirmDataNo'),
    ConfirmToken = document.querySelector('#confirmToken'),
    bConfirmToken = document.querySelector('#bConfirmToken'),
    confirmCountdown = document.querySelector('#confirmCountdown'),
    confirmInfo = document.querySelector('#confirmInfo'),
    bStartTest = document.querySelector('#bStartTest'),
    tTimeLeft = document.querySelector('#tTimeLeft'),
    examSchedule = document.querySelector('#examScheduleID'),
    token_form = document.querySelector('input[name=token]');

let timeNow = new Date().getTime(),
    timeTarget = new Date().getTime();

function getExamInfo() {
    $.ajax({
        url: '../../get_header_data/' + examSchedule,
        method: 'post',
        data: {
            token: token_form.value,
        },
        dataType: 'json',
        success: function(response) {
            token_form.value = response.token;
            tStudy.innerHTML = response.study;
            tOrder.innerHTML = response.order;
            timeLeft = new Date(response.time_left * 1000).getTime();
            timeServerNow = new Date(response.time_server_now * 1000).getTime();
            showTimeLeft();
        }
    })
}

console.log(examSchedule);

bConfirmDataYes.addEventListener('click', () => {
    ConfirmToken.classList.remove('d-none')
    bConfirmDataYes.disabled = true;
    bConfirmDataNo.disabled = true;
})

bConfirmToken.addEventListener('click', () => {
    confirmCountdown.classList.remove('d-none')
    bConfirmToken.disabled = true;
    iConfirmToken.disabled = true;
    showTimeLeft(timeTarget, timeNow);
})

function showConfirmInfo() {
    confirmCountdown.classList.add('d-none')
    confirmInfo.classList.remove('d-none')
    bStartTest.classList.remove('d-none')
}

function showZero(x) {
    return data = (x < 10) ? '0' + x : x;
}

function showTimeLeft(timeTarget, timeNow) {
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
            showConfirmInfo()
        }
    }, 1000);
}