bPeriod.onchange = () => {
    window.location = bPeriod.value;
}

document.querySelectorAll('.bImport').forEach(item => {
    item.addEventListener('click', () => {
        Swal.fire({
            title: 'Peringatan',
            text: "Apakah Anda yakin akan mentransfer butir-butir soal ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = item.getAttribute('data-href');
            }
        })
    })
});