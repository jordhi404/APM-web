$('#cetak-nota').on('click', function () {
  const registrationNo = sessionStorage.getItem('registrationNo');
  const issuerName = sessionStorage.getItem('issuerName');

  if (!registrationNo) {
    alert('No registrasi tidak ditemukan di sessionStorage.');
    return;
  }

  $.ajax({
    // url: `http://127.0.0.1:8000/api/print-bill`,
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
      const notaText = generateNotaStruk(response.data);
      // const notaText = document.getElementById('nota-container').innerHTML;
      printNotaQZ(notaText);
    },
    error: function (xhr) {
      alert('Terjadi kesalahan: ' + xhr.responseText);
    }
  });

  // Fungsi bantu untuk format rupiah
  function formatRupiah(angka) {
    return 'Rp ' + Number(angka).toLocaleString('id-ID');
  }

  function printNotaQZ(notaText) {
    qz.websocket.connect().then(() => {
      return qz.printers.find("Microsoft Print to PDF"); // Ganti sesuai printermu
    }).then((printer) => {
      // alert(printer);
      const config = qz.configs.create(printer);
      // const htmlFormatted = `
      //   <html>
      //     <body>
      //       <pre style="font-family: 'Courier New', monospace; font-size: 10pt;">${notaText}</pre>
      //     </body>
      //   </html>
      // `;

      const data = [{
        type: 'html', // raw untuk langsung cetak, html untuk uji coba print to PDF. 
        format: 'plain',
        data: notaText
      }];
      return qz.print(config, data);
    }).then(() => {
      console.log("Nota berhasil dikirim ke printer.");
      Swal.fire({
        icon: 'success',
        title: 'Nota berhasil dicetak!',
        text: 'Kembali ke beranda sebentar lagi.',
        timer: 5000,
        showTimerProgressBar: true,
        showConfirmButton: false,
        allowOutsideClick: false,
      }).then((result) => {
        if (result.isConfirmed) {
          sessionStorage.clear();
          window.location.href = "/"; // local side
          // window.location.href = "/apm/"; // server side
        }
      });
    }).catch((e) => {
      console.error("Gagal mencetak nota:", e);
    });
  }

  function generateNotaStruk(data) {
    const pad = (text, len) => (text + '').padEnd(len).substring(0, len);
    const right = (text, len) => (text + '').padStart(len).substring(0, len);
    const formatRupiah = (angka) => 'Rp ' + Number(angka).toLocaleString('id-ID');

    const line = '='.repeat(48);
    let str = '';
    str += '     RUMAH SAKIT DR. OEN SOLO BARU\n';
    str += '     Jl. Bahu Dlopo, Dusun II, Gedangan, Kec. Grogol, Kab. Sukoharjo, Jawa Tengah 57552\n';
    str += '     Telp. (0271) 620220\n\n';
    str += `${line}\n`;

    const now = new Date();
    str += ` Tanggal      : ${now.toLocaleDateString('id-ID')}\n`;
    str += ` Waktu        : ${now.toLocaleTimeString('id-ID')}\n`;
    str += ` Status Bayar : BERHASIL\n`;

    str += `${line}\n`;
    str += ` Pasien       : ${data[0].FullName}\n`;
    str += ` No. Reg      : ${data[0].RegistrationNo}\n`;
    str += ` Pembayaran   : ${issuerName}\n`;
    str += `${line}\n`;
    str += ` RINCIAN TAGIHAN\n`;
    str += ' ----------------------------------------------\n';

    let total = 0;
    data.forEach(item => {
      const ket = pad(item.ItemName, 30);
      const jumlah = right(formatRupiah(item.HargaAkhir), 15);
      str += ` ${ket}${jumlah}\n`;
      total += Number(item.HargaAkhir);
    });

    str += ' ----------------------------------------------\n';
    str += ` Total Pembayaran         ${right(formatRupiah(total), 15)}\n`;
    str += `${line}\n\n`;
  
    str += ` Terima kasih atas pembayaran Anda.\n`;
    str += ` Simpan nota ini sebagai bukti resmi.\n\n`;
    str += `${line}\n\n\n`;

    return str;
  }

});