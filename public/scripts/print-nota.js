$('#cetak-nota').on('click', function () {
  const registrationNo = sessionStorage.getItem('registrationNo');

  if (!registrationNo) {
    alert('No registrasi tidak ditemukan di sessionStorage.');
    return;
  }

  $.ajax({
    url: `http://192.167.4.250/apm/public/api/print-bill`,
    type: 'POST',
    data: { registrationNo: registrationNo },
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    success: function (response) {
      console.log('Response:', response);

      if (!response.data || response.data.length === 0) {
        alert('Data tidak ditemukan.');
        return;
      }

      // Ambil info umum dari baris pertama
      // $('#nota-container').show();
      $('#reg-no').text(response.data[0].RegistrationNo);
      $('#nama-pasien').text(response.data[0].FullName);
      $('#payment-id').text(response.data[0].PaymentID);
      $('#total-tagihan').text(formatRupiah(response.data[0].TagihanTotal));

      // Bersihkan table rincian
      $('#rincian-nota').empty();

      // Loop isi rincian tagihan
      response.data.forEach(item => {
        $('#rincian-nota').append(`
          <tr>
            <td style="text-align:left;">${item.ItemName}</td>
            <td style="text-align:right;">${formatRupiah(item.HargaAkhir)}</td>
          </tr>
        `);
      });

      // Ambil HTML untuk dicetak
      const notaHTML = document.getElementById('nota-container').innerHTML;
      printNotaQZ(notaHTML);
    },
    error: function (xhr) {
      alert('Terjadi kesalahan: ' + xhr.responseText);
    }
  });

  // Fungsi bantu untuk format rupiah
  function formatRupiah(angka) {
    return 'Rp ' + Number(angka).toLocaleString('id-ID');
  }

  function printNotaQZ(notaHTML) {
    qz.websocket.connect().then(() => {
      return qz.printers.find("Microsoft Print to PDF"); // Ganti sesuai printermu
    }).then((printer) => {
      // alert(printer);
      const config = qz.configs.create(printer);
      const data = [{
        type: 'html',
        format: 'plain',
        data: notaHTML
      }];
      return qz.print(config, data);
    }).then(() => {
      console.log("Nota berhasil dikirim ke printer.");
    }).catch((e) => {
      console.error("Gagal mencetak nota:", e);
    });
  }
});