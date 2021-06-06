let token = document.querySelector('input[name=token]').value,
    limit = 0,
    x = null;

getQuestion();

function getQuestion() {
    y_tick = 0;
    y_limit = 5;
    let y = setInterval(function() {
        if (y_tick == 5) {
            clearInterval(y);
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
                    limit = response.exam_question.timeleft;
                    tExamDetail.innerHTML = doConvert(response.exam_question.question);
                    tOpsiA.innerHTML = doConvert(response.exam_question.opsi_a);
                    tOpsiB.innerHTML = doConvert(response.exam_question.opsi_b);
                    tOpsiC.innerHTML = doConvert(response.exam_question.opsi_c);
                    tOpsiD.innerHTML = doConvert(response.exam_question.opsi_d);
                    tOpsiE.innerHTML = doConvert(response.exam_question.opsi_e);
                    showTimeLeft();
                },
                fail: function() {
                    console.log('fail load detail exam');
                }
            })

        } else {
            fNotifCountDownExamShow.classList.remove('d-none');
            fNotifCountDownExamThinking.classList.add('d-none');
            fExamDetail.classList.add('d-none');
            tNotifCountDownExamShow.innerHTML = 'Aplikasi akan memuat soal baru dalam ' + (y_limit - y_tick) + ' detik';
        }
        y_tick++;
    }, 1000);
}

function showTimeLeft() {
    tick = 0;
    let x = setInterval(function() {
        if (tick == limit || tick < 0) {
            clearInterval(x);
            tTimeleft.innerHTML = 'habis';
            getQuestion();
        } else {
            tTimeleft.innerHTML = limit - tick + ' detik';
        }
        tick++;
    }, 1000);
}

function loading() {
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