$('.bReset').click(function() {
    Swal.fire({
        title: 'Peringatan',
        text: "Jawaban ujian atas siswa ini akan dihapus, apakah Anda yakin?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            location.href = $(this).data('href');
        }
    })
})

document.querySelectorAll('.filterOrder').forEach(item => {
    item.addEventListener('click', event => {
        filter = item.getAttribute('data-value');
        var t = $('.dtable').DataTable();
        t.search(filter).draw();
    })
});

$('.bSetFinish').click(function() {
    Swal.fire({
        title: 'Peringatan',
        text: "Ujian siswa ini akan diselesaikan secara manual, apakah Anda yakin?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            location.href = $(this).data('href');
        }
    })
})