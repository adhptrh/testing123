let token = document.querySelector('input[name=token]').value,
    limit = 0,
    pause = false,
    x = null,
    answer = 0,
    no = 0,
    exam = 0, // exam_temp_id
    examQuestionDetail = 0;

getQuestion();

function getQuestion() {
    loading();
    $.ajax({
        url: '../get_question/',
        method: 'post',
        data: {
            token: token,
            exam_schedule_id: examSchedule.getAttribute('data-value'),
            exam_question_id: examQuestion.getAttribute('data-value'),
            student_grade_exam_id: studentGradeExam.getAttribute('data-value'),
        },
        dataType: 'json',
        success: function(response) {
            token = response.token;

            if (response.is_available == '1') {
                exam = response.exam_question.exam_id;
                examQuestionDetail = response.exam_question.id;
                no = response.exam_question.no;
                tNoExamHeader.innerHTML = response.exam_question.no;
                limit = response.exam_question.timeleft;
                tExamDetail.innerHTML = doConvert(response.exam_question.question);
                tOpsiA.innerHTML = doConvert(response.exam_question.opsi_a);
                tOpsiB.innerHTML = doConvert(response.exam_question.opsi_b);
                tOpsiC.innerHTML = doConvert(response.exam_question.opsi_c);
                tOpsiD.innerHTML = doConvert(response.exam_question.opsi_d);
                tOpsiE.innerHTML = doConvert(response.exam_question.opsi_e);
                showTimeLeft();
            } else {
                showButtonNext(1);
                notifFinishTime.classList.remove('d-none')
                tFinishTime.innerHTML = response.message;
                fNotifCountDownExamThinking.classList.add('d-none');
                fExamDetail.classList.add('d-none');
            }
            // if (response.is_done == '1') {
            //     showButtonNext(1);
            //     notifFinishTime.classList.remove('d-none')
            //     tFinishTime.innerHTML = "Terimakasih Anda telah menyelesaikan ujian, silahkan logout.";
            //     fNotifCountDownExamThinking.classList.add('d-none');
            //     fExamDetail.classList.add('d-none');
            // } else {
            //     if (response.exam_question.is_intime == '1') {
            //         exam = response.exam_question.exam_id;
            //         examQuestionDetail = response.exam_question.id;
            //         limit = response.exam_question.timeleft;
            //         tExamDetail.innerHTML = doConvert(response.exam_question.question);
            //         tOpsiA.innerHTML = doConvert(response.exam_question.opsi_a);
            //         tOpsiB.innerHTML = doConvert(response.exam_question.opsi_b);
            //         tOpsiC.innerHTML = doConvert(response.exam_question.opsi_c);
            //         tOpsiD.innerHTML = doConvert(response.exam_question.opsi_d);
            //         tOpsiE.innerHTML = doConvert(response.exam_question.opsi_e);
            //         showTimeLeft();
            //     } else {
            //         showButtonNext(1);
            //         notifFinishTime.classList.remove('d-none')
            //         tFinishTime.innerHTML = "Maaf, Anda tidak dapat melanjutkan ujian, karena waktu ujian sudah berakhir, silahkah logout";
            //         fNotifCountDownExamThinking.classList.add('d-none');
            //         fExamDetail.classList.add('d-none');
            //     }
            // }
        },
        fail: function() {
            console.log('fail load detail exam');
        }
    })
}

function showTimeLeft() {
    tick = 0;
    let x = setInterval(function() {
        if (tick == limit || tick < 0) {
            clearInterval(x);
            tTimeleft.innerHTML = 'habis';
            showButtonNext();
            // getQuestion();
        } else {
            tTimeleft.innerHTML = limit - tick + ' detik';
        }

        if (!pause) {
            tick++;
        }
    }, 1000);
}

function loading() {
    showButtonNext(1);
    fNotifCountDownExamShow.classList.add('d-none');
    fNotifCountDownExamThinking.classList.remove('d-none');
    fExamDetail.classList.remove('d-none');
    tTimeleft.innerHTML = 'sedang memuat ...';
    tExamDetail.innerHTML = 'sedang memuat ...';
    tOpsiA.innerHTML = 'sedang memuat ...';
    tOpsiB.innerHTML = 'sedang memuat ...';
    tOpsiC.innerHTML = 'sedang memuat ...';
    tOpsiD.innerHTML = 'sedang memuat ...';
    tOpsiE.innerHTML = 'sedang memuat ...';

    ftOpsiA.checked = false;
    ftOpsiB.checked = false;
    ftOpsiC.checked = false;
    ftOpsiD.checked = false;
    ftOpsiE.checked = false;
}

function doConvert(data) {
    data = data.replaceAll("upload", baseURL.getAttribute('data-value') + "/upload");
    try {
        converter.setContents(JSON.parse(data));
        return converter.root.innerHTML;
    } catch (error) {
        return data;
    }
}

converter = new Quill('#converter', {
    modules: {
        toolbar: [
            [{ header: [1, 2, false] }],
            ['bold', 'italic', 'underline', 'strike', 'image'],
            ['link'],
            ['blockquote'],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            [{ 'script': 'sub' }, { 'script': 'super' }, 'formula'],
            ['align', { 'align': 'center' }, { 'align': 'right' }, { 'align': 'justify' }],
        ]
    },
    theme: 'snow'
});

bIzin.onclick = () => {
    // pause = true;
    Swal.fire({
        width: '500px',
        title: 'Peringatan',
        text: "Izin menghentikan-sementara (pause) proses ujian hanya dapat dilakukan 1 kali selama ujian, apakah Anda yakin ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../pause/',
                method: 'post',
                data: {
                    token: token,
                    student_grade_exam_id: studentGradeExam.getAttribute('data-value'),
                },
                dataType: 'json',
                success: function(response) {
                    token = response.token;
                    if (response.is_allow == "1") {
                        window.location.href = '../../exam_schedule';
                    } else {
                        Swal.fire({
                            width: '500px',
                            title: 'Peringatan',
                            text: "Maaf, Anda telah mencapai limit izin",
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Baik',
                        }).then((result) => {
                            // pause = false;
                        })
                    }
                },
                fail: function() {
                    console.log('fail load detail exam');
                    // pause = false;
                }
            })
        } else {
            // pause = false;
        }
    })
}

function showButtonNext(status = 0) {
    if (status) {
        fNotifNext.classList.add('d-none');
        bNext.classList.add('d-none');
        bIzin.classList.add('d-none');
    } else {
        tNo.innerHTML = no;
        fExamDetail.classList.add('d-none');
        fNotifNext.classList.remove('d-none');
        bNext.classList.remove('d-none');
        bIzin.classList.remove('d-none');
        fNotifCountDownExamThinking.classList.add('d-none');
    }
}

bNext.onclick = () => {
    getQuestion();
}

document.querySelectorAll('.bOpsi').forEach((item, index) => {
    item.addEventListener('click', () => {
        pause = true;
        answer = item.getAttribute('data-value');
        save();
    })
});

function save() {
    pause = true;
    $.ajax({
        url: '../save/',
        method: 'post',
        data: {
            token: token,
            answer: answer,
            exam: exam,
            exam_question_detail_id: examQuestionDetail,
            student_grade_exam_id: studentGradeExam.getAttribute('data-value'),
        },
        dataType: 'json',
        success: function(response) {
            token = response.token;
            if (!response.status == 200) {
                showInfo(response.message)
            }
            pause = false;
        },
        error: function() {
            Swal.fire({
                title: 'Peringatan',
                text: 'Aplikasi tidak berhasil menyimpan jawaban peserta ujian, mohon hubungi penyelenggara ujian.',
                icon: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ya',
            }).then((result) => {
                // if (result.isConfirmed) {
                // }
                pause = true;
            })
        }
    })
}