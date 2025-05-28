$('#cetak-nota').on('click', function () {
  const registrationNo = sessionStorage.getItem('registrationNo');
  const issuerName = sessionStorage.getItem('issuerName');

  if (!registrationNo) {
    alert('No registrasi tidak ditemukan di sessionStorage.');
    return;
  }

  $.ajax({
    // url: `http://10.100.18.154:8000/api/print-bill`,
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
      return qz.printers.find("80 Printer"); // Ganti sesuai printermu
    }).then((printer) => {
      // alert(printer);
      const config = qz.configs.create(printer);

      const data = [{
        type: 'raw', // raw untuk langsung cetak, html untuk uji coba print to PDF. 
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
        didClose: () => {
          sessionStorage.clear();
          // window.location.href = "/"; // local side
          window.location.href = "/apm/"; // server side
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
    const center = (text, width = 48) => {
      const space = Math.max(0, Math.floor((width - text.length) / 2));
      return ' '.repeat(space) + text;
    };

    const line = '='.repeat(48);
    let str = '';

    str += `${center('RUMAH SAKIT DR. OEN SOLO BARU')}\n`;
    str += `${center('Jl.Bahu Dlopo, Gedangan, Sukoharjo 57552')}\n`;
    str += `${center('Telp. (0271) 620220')}\n`;
    str += `${line}\n`;

    const now = new Date();
    str += ` Tanggal, Waktu : ${now.toLocaleDateString('id-ID')}, ${now.toLocaleTimeString('id-ID')}\n`;
    str += ` Status Bayar   : BERHASIL\n`;

    str += `${line}\n`;
    str += ` Pasien       : ${data[0].FullName}\n`;
    str += ` Pembayaran   : ${issuerName}\n`;
    str += `${line}\n`;

    let total = 0;
    data.forEach(item => {
      const ket = pad(item.ItemName, 30);
      const jumlah = right(formatRupiah(item.HargaAkhir), 15);
      str += ` ${ket}${jumlah}\n`;
      total += Number(item.HargaAkhir);
    });

    str += ' ----------------------------------------------\n';
    str += ` Total Pembayaran         ${right(formatRupiah(total), 15)}\n`;
    str += `${line}\n`;
  
    str += ` Terima kasih atas kunjungan anda.\n\n`;
    str += `\n\n`;
    str += `\n\n`;
    str += `\n\n`;
    str += `\n\n\n`;

    return str.trimStart();
  }

});