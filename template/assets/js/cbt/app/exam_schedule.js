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