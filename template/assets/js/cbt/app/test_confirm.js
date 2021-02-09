const token_form = document.querySelector('input[name=token]');

let timeNow = new Date().getTime(),
    timeTarget = new Date().getTime(),
    examSchedule = 0;

examSchedule = examScheduleID.getAttribute('data-id');

// function getTimeInfo() {
//     $.ajax({
//         url: '../../get_header_data/' + examSchedule,
//         method: 'post',
//         data: {
//             token: token_form,
//         },
//         dataType: 'json',
//         success: function(response) {
//             token_form.value = response.token;
//             timeTarget = new Date(response.time_start * 1000).getTime();
//             timeNow = new Date(response.time_server_now * 1000).getTime();
//         }
//     })
// }

bStartTest.addEventListener('click', () => {
    window.location.href = `../execute/${examSchedule}`;
})

bConfirmDataNo.addEventListener('click', () => {
    Swal.fire({
        title: 'Info',
        text: "Silahkan hubungi pihak penyelenggara ujian.",
        icon: 'warning',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK',
    }).then((result) => {})
});

bConfirmDataYes.addEventListener('click', () => {
    confirmToken.classList.remove('d-none')
    bConfirmDataYes.disabled = true;
    bConfirmDataNo.disabled = true;
})

bConfirmToken.addEventListener('click', () => {
    $.ajax({
        url: '../cek_token/',
        method: 'post',
        data: {
            token: token_form.value,
            examSchedule: examSchedule,
            token_exam: iConfirmToken.value,
        },
        dataType: 'json',
        success: function(response) {
            token_form.value = response.token;
            if (response.token_exam == 1) {
                timeTarget = new Date(response.time_start * 1000).getTime();
                timeNow = new Date(response.time_server_now * 1000).getTime();

                confirmCountdown.classList.remove('d-none')
                bConfirmToken.disabled = true;
                iConfirmToken.disabled = true;
                showTimeLeft(timeTarget, timeNow);
            } else {
                Swal.fire({
                    title: 'Info',
                    text: "Token Salah",
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                }).then((result) => {})
            }
        }
    })
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