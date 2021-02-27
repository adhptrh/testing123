let token = 0,
    dHref = '';

token = document.querySelector('input[name=token]');

letMeBack.addEventListener("click", () => {
    window.history.back();
})

bShowQuestion = document.querySelectorAll('.bShowQuestion');

bShowQuestion.forEach((item, index) => {
    item.addEventListener('click', () => {
        dHref = item.getAttribute('data-href');
        loadDetailQuestion();
    })
});

function loadDetailQuestion() {

    axios.get(dHref)
        .then(function(response) {
            console.log(response);
            iDetail.innerHTML = response.data.question;
            iOpsi_A.innerHTML = response.data.opsi_a;
            iOpsi_B.innerHTML = response.data.opsi_b;
            iOpsi_C.innerHTML = response.data.opsi_c;
            iOpsi_D.innerHTML = response.data.opsi_d;
            iOpsi_E.innerHTML = response.data.opsi_e;
            iKeyword.innerHTML = response.data.keyword;
            iAnswer.innerHTML = response.data.answer;
            fDetail.classList.remove('d-none');
            fDetails.classList.add('d-none');
        });
}

bCloseDetail.addEventListener('click', () => {
    fDetail.classList.add('d-none');
    fDetails.classList.remove('d-none');
})