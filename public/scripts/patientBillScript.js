/****************** SCRIPT DUMMY ******************/
// $(document).ready(function() {
//     function getPatientBill() {
//         let RM = sessionStorage.getItem('MR');
//         console.log("MR tersimpan di session: ", RM);
//         if (RM) {
//             $.ajax({
//                 type: 'GET',
//                 url: `/api/patients-bill/${RM}`,
//                 success: function(response) {
//                     let tbody = $("table tbody");
//                     tbody.empty();
    
//                     if (response.data.length === 0) {
//                         tbody.append('<tr><td colspan="4" class="text-center">Tidak ada tagihan ditemukan</td></tr>');
//                         return;
//                     }
    
//                     response.data.forEach(item => {
//                         let harga = item.harga ? item.harga.Biaya : 0;
//                         let row = `
//                             <tr>
//                                 <td>${item.Layanan}</td>
//                                 <td>${item.Qty}</td>
//                                 <td>Rp ${harga.toLocaleString()}</td>
//                                 <td>Rp ${(harga * item.Qty).toLocaleString()}</td>
//                             </tr>
//                         `;
//                         tbody.append(row);
//                     });

//                     $('#total-tagihan').html(`<strong>Total tagihan pasien: Rp ${response.total.toLocaleString()}</strong>`);
    
//                     if (response.data.length > 0 && response.data[0].pasien) {
//                         let pasien = response.data[0].pasien;
//                         $('#patient-info').html(`
//                             <h5>${pasien.name} / ${pasien.MR}</h5>
//                             <p>${pasien.address}</p>                       
//                         `);
//                     }
//                 },
//                 error: function() {
//                     console.log('Ada kendala menampilkan data.');
//                     Swal.fire({
//                         icon: 'error',
//                         title: 'Pasien tidak ditemukan!',
//                     });
//                 }
//             });
//         } else {
//             Swal.fire({
//                 icon: 'warning',
//                 title: 'Nomor RM tidak ditemukan!',
//                 text: 'Silakan kembali dan masukkan ulang No. RM.',
//             }).then(() => {
//                 window.location.href = "/index"; // Arahkan user kembali ke home
//             });
//         }
//     }

//     getPatientBill();
// });

/****************** SCRIPT LIVE ******************/
$(document).ready(function() {
    function getPatientBill() {
        let RM = sessionStorage.getItem('RM');
        let dob = sessionStorage.getItem('dob');
        
        if (RM && dob) {
            $.ajax({
                type: 'POST',
                url: `/api/patients-bill`,
                data: {
                    RM: RM,
                    dob: dob
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // kalau perlu CSRF
                },
                success: function(response) {
                    let accordion = $('#accordionTagihan');
                    accordion.empty();

                    if (response.data.length === 0) {
                        accordion.append(`
                                <div class="text-center text-muted">
                                    <p>Tidak ada tagihan ditemukan.</p>
                                </div>
                            `);
                        return;
                    }

                    // Mengelompokkan berdasarkan TransactionDate.
                    let grouped = {};
                    response.data.forEach(item => {
                        if (!grouped[item.Tanggal]) {
                            grouped[item.Tanggal] = [];
                        }
                        grouped[item.Tanggal].push(item);
                    });

                    let index = 0;
                    for (let date in grouped) {
                        let items = grouped[date];
                        let rows = "";

                        let transCount = {};
                        let transPrinted = {};

                        // Hitung jumlah layanan per TransactionNo
                        items.forEach(item => {
                            if (!transCount[item.TransactionNo]) {
                                transCount[item.TransactionNo] = 0;
                            }
                            transCount[item.TransactionNo]++;
                        });

                        items.forEach(item => {
                            const noTrans = item.TransactionNo;
                    
                            rows += `<tr>`;
                    
                            // Cetak TransactionNo hanya sekali, pakai rowspan
                            if (!transPrinted[noTrans]) {
                                rows += `<td rowspan="${transCount[noTrans]}">${noTrans}</td>`;
                                transPrinted[noTrans] = true;
                            }
                    
                            rows += `
                                <td>${item.Layanan}</td>
                                <td>${parseFloat(item.Banyak).toFixed(2)}</td>
                                <td>Rp ${Number(item.HargaSatuan).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                                <td>Rp ${Number(item.HargaSatuan * item.Banyak).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                            </tr>`;
                        });                    

                        accordion.append(`
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading${index}">
                                    <button class="accordion-button ${index !== 0 ? 'collapsed' : ''}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapse${index}" aria-expanded="${index === 0}" aria-controls="collapse${index}">
                                        Tanggal transaksi:&nbsp;<strong>${date}</strong>
                                    </button>
                                </h2>
                                <div id="collapse${index}" class="accordion-collapse collapse ${index === 0 ? 'show' : ''}" aria-labelledby="heading${index}" data-bs-parent="#accordionTagihan">
                                    <div class="accordion-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No. Transaksi</th>
                                                    <th>Layanan</th>
                                                    <th>Jumlah (desimal)</th>
                                                    <th>Harga Satuan</th>
                                                    <th>Harga Akhir</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${rows}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        `);

                        index++;
                    }

                    // Menampilkan total tagihan.
                    $('#total-tagihan').html(`
                        <strong>TOTAL TAGIHAN PASIEN: Rp ${response.total.toLocaleString()}</strong>
                    `);

                    // Info Pasien.
                    if (response.pasien) {
                        let pasien = response.pasien;
                        let registration = response.registration;

                        if (registration.RegistrationNo && response.total) {
                            sessionStorage.setItem('reg_no', registration.RegistrationNo);
                            sessionStorage.setItem('total', response.total);
                        }

                        $('#patient-info').html(`
                            <h5>${pasien.Nama} / ${pasien.MedicalNo}</h5>                       
                        `);
                        $('#info-kunjungan').html(`
                            No. Registrasi: ${registration.RegistrationNo}
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Status:', status);
                    console.log('Error:', error);
                    console.log('Response:', xhr.responseText);

                    Swal.fire({
                        icon: 'error',
                        title: 'Data tagihan tidak ditemukan!',
                        text: 'Tidak ada tagihan untuk pasien ini.',
                        confirmButtonText: 'Kembali ke Beranda',
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            sessionStorage.clear(); // Hapus semua data di sessionStorage
                            window.location.href = "/welcome"; // Arahkan user kembali ke home
                        }
                    });
                }
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Nomor RM tidak ditemukan!',
                text: 'Silakan kembali dan masukkan ulang No. RM.',
            }).then(() => {
                window.location.href = "/index"; // Arahkan user kembali ke home
            });
        }
    }

    getPatientBill();
});