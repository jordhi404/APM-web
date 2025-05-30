/****************** SCRIPT DUMMY ******************/
// $(document).ready(function() {
//     function getPatientInfo() {
//         $('#btn-check').click(function() {
//             let RM = $('#input-RM').val();

//             if(!RM) {
//                 Swal.fire({
//                     icon: 'warning',
//                     title: 'No. MR belum diisi',
//                 });
//                 return;
//             }

//             $.ajax({
//                 type: 'GET',
//                 url: `/api/patients-info/${RM}`,
//                 success: function(response) {
//                     Swal.fire ({
//                         icon: 'success',
//                         title: 'Pasien ditemukan!',
//                         html: `
//                                 <p style="color: #555; font-size: 16px;"> Harap pastikan data pasien sudah benar. </p>
//                                 <p><strong>Nama:</strong> ${response.name}</p>        
//                             `, // Pasien dari pgsql: response.name || Pasien dari medin_ws: response.FullName
//                         confirmButtonText: "Lanjut Pembayaran",
//                     }).then((result) => {
//                         if (result.isConfirmed) {
//                             sessionStorage.setItem('MR', $('#input-RM').val());
//                             window.location.href= `/details`;
//                         }
//                     });
//                 },
//                 error: function() {
//                     console.log('Masalah koneksi ke api');
//                     Swal.fire({
//                         icon: 'error',
//                         title: 'Pasien tidak ditemukan!',
//                     });
//                 }
//             });
//         });
//     }

//     function pembayaranBerhasil() {
//         $('#back-btn').click(function() {
//             sessionStorage.clear();
//             window.location.href = '/welcome';
//             console.log('Session cleared!');
//         });
//     }

//     getPatientInfo();
//     pembayaranBerhasil();
// });

/****************** SCRIPT LIVE ******************/
$(document).ready(function() {
    function getPatientInfo() {
        $('#btn-check').click(function() {
            let RM = $('#input-RM').val();
            let dob = $('#dob-display').val();

            if(!RM || !dob) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Harap isi no. RM dan tanggal lahir pasien.',
                });
                return;
            }

            $.ajax({
                type: 'POST',
                url: `http://192.167.4.250/apm/public/api/patients-info`, // Server
                // url: `http://10.100.18.154:8000/api/patients-info`, // Local
                data: {
                    RM: RM,
                    dob: dob
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('bill_no: ', response.bill_no);
                    let billNote = response.bill_no 
                        ? '<div class="alert alert-success mt-2 text-center">Pasien sudah dibuat nomor tagihan.</div>' 
                        : '<div class="alert alert-warning mt-2 text-center">Pasien belum dibuat nomor tagihan.</div>';

                    Swal.fire ({
                        icon: 'success',
                        title: 'Pasien ditemukan!',
                        html: `
                                <p style="color: #555; font-size: 16px;"> Harap pastikan data pasien sudah benar. </p>
                                <div style="text-align: left;">
                                    <div class="row d-flex">
                                        <div class="col-2">
                                            <i class= "fa-regular fa-user"></i>
                                        </div>
                                        <div class="col">
                                            <p><strong>${response.data.FullName}</strong></p>
                                        </div>
                                    </div>        
                                    <div class="row d-flex">
                                        <div class="col-2">
                                            <i class= "fa-regular fa-clipboard"></i>
                                        </div>
                                        <div class="col">
                                            <p><strong>${response.reg_no}</strong></p>
                                        </div>
                                    </div>
                                    <div class="row d-flex">
                                        <div class="col">
                                            <p><strong>${billNote}</strong></p>
                                        </div>
                                    </div>
                                </div>        
                            `, // Pasien dummy dari pgsql: response.name || Pasien dari medin dan medin_ws: response.FullName
                        confirmButtonText: "Lanjut Pembayaran",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            sessionStorage.setItem('RM', RM);
                            sessionStorage.setItem('dob', dob);
                            sessionStorage.setItem('registrationNo', response.reg_no);
                            sessionStorage.setItem('DB_bill_no', response.bill_no);
                            window.location.href= `/apm/details`;
                            // window.location.href= `/details`;
                        }
                    });
                },
                error: function() {
                    console.log('Pasien tidak ditemukan!');
                    Swal.fire({
                        icon: 'error',
                        title: 'Pasien tidak ditemukan!',
                    });
                }
            });
        });
    }

    function kembaliKeAwal() {
        $('#back-btn').click(function() {
            sessionStorage.clear();
            window.location.href = '/apm/';
            // window.location.href = '/';
            console.log('Session cleared!');
        });
    }

    getPatientInfo();
    kembaliKeAwal();
});