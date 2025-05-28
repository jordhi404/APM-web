@extends('layouts.templates')

@section('title', 'index')

@push('styles')
<style>
    #virtual-keypad {
        display: grid;
        grid-template-columns: repeat(3, 60px);
        gap: 10px;
        padding: 10px;
        width: max-content;
        margin-top: 10px;
    }
    .key {
        background: #fff;
        border: 1px solid #999;
        border-radius: 10px;
        box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.2);
        text-align: center;
        font-weight: bold;
        padding: 15px 0;
        font-size: 20px;
        cursor: pointer;
        user-select: none;
    }
    .custom-hidden {
        display: none !important;
    }
    .scroll-group {
        display: flex;
        justify-content: center;
    }
    .scroll-column {
        width: 80px;
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ccc;
        border-radius: 5px;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .scroll-column div {
        padding: 5px 0;
        cursor: pointer;
    }
    .scroll-column div.selected {
        background-color: #0d6efd;
        color: white;
        font-weight: bold;
    }
    #yearPicker, #monthPicker, #dayPicker {
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none;  /* IE 10+ */
    }

    #yearPicker::-webkit-scrollbar, #monthPicker::-webkit-scrollbar, #dayPicker::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera */
    }
</style>

@section('content')
    <div class="container mt-4" id="content-main">
        <div class="d-grid gap-5 d-md-flex justify-content-md-center">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="form-group">
                        <label for="input-RM"><strong>NO. REKAM MEDIS PASIEN:</strong></label>
                        <input type="text" class="form-control" id="input-RM" placeholder="Contoh: 00-11-22-33" readonly data-bs-toggle="modal" data-bs-target="#rmModal"><br>
                        <div class="modal fade" id="rmModal" tabindex="-1" aria-labelledby="rmModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" style="width: fit-content;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="rmModalLabel">Masukkan No. Rekam Medis</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <input type="text" class="form-control mb-3" id="modal-rm-input" readonly>
                                        <div id="virtual-keypad">
                                            <!-- Keypad buttons -->
                                            <div class="key" data-key="1">1</div>
                                            <div class="key" data-key="2">2</div>
                                            <div class="key" data-key="3">3</div>
                                            <div class="key" data-key="4">4</div>
                                            <div class="key" data-key="5">5</div>
                                            <div class="key" data-key="6">6</div>
                                            <div class="key" data-key="7">7</div>
                                            <div class="key" data-key="8">8</div>
                                            <div class="key" data-key="9">9</div>
                                            <div class="key" data-key="del">⌫</div>
                                            <div class="key" data-key="0">0</div>
                                            <div class="key" data-key="clear">C</div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" id="btn-set-rm" class="btn btn-success" data-bs-dismiss="modal">Atur</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="form-group">
                        <label for="dob-display"><strong>TANGGAL LAHIR PASIEN:</strong></label>
                        <input type="text" class="form-control" id="dob-display" readonly placeholder="Pilih Tanggal Lahir"><br>

                        <!-- Modal Scroll Picker -->
                        <div class="modal fade" id="dob-modal" tabindex="-1" aria-labelledby="dobModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content dob-modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="rmModalLabel">SCROLL DAN PILIH</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="scroll-group d-flex justify-content-center">
                                            <div class="mx-2 text-center">
                                                <div id="year-label"><strong>Tahun</strong></div> 
                                                <div class="scroll-column" id="yearPicker" style="max-height: 200px; overflow-y: auto;"></div>
                                            </div>
                                            <div class="mx-2 text-center">
                                                <div id="month-label"><strong>Bulan</strong></div>
                                                <div class="scroll-column" id="monthPicker" style="max-height: 200px; overflow-y: auto;"></div>
                                            </div>
                                            <div class="mx-2 text-center">
                                                <div id="day-label"><strong>Tanggal</strong></div>
                                                <div class="scroll-column" id="dayPicker" style="max-height: 200px; overflow-y: auto;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button id="confirmDob" class="btn btn-success">Atur</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-3 justify-content-center">
                <div class="col-6">
                    <button class="btn btn-danger" id="back-btn" style="width: 100%;">Kembali</button>
                </div>
                <div class="col-6">
                    <button class="btn btn-primary" id="btn-check" style="width: 100%; margin-bottom: 15px;">Cek Pasien</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="scripts/patientInfoScript.js"></script>
    <script src="scripts/date-pick.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: '📢 Pemberitahuan Penting',
                html: `
                    <p>Harap pastikan saldo mencukupi karena program SOBA Pay tidak bisa melakukan pembatalan di tengah proses transaksi.</p>
                    <div style="margin-top:15px;">
                        <input type="checkbox" id="checkSayaMengerti" style="transform: scale(1.5); margin-right: 8px;"/>
                        <label for="checkSayaMengerti">Saya mengerti</label>
                    </div>
                `,
                icon: 'warning',
                showConfirmButton: true,
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    const confirmBtn = Swal.getConfirmButton();
                    confirmBtn.disabled = true;

                    const checkbox = Swal.getPopup().querySelector('#checkSayaMengerti');
                    checkbox.addEventListener('change', () => {
                        confirmBtn.disabled = !checkbox.checked;
                    });
                }
            });
        });
    </script>
    <script>
        const modalInput = document.getElementById('modal-rm-input');
        const mainInput = document.getElementById('input-RM');

        document.querySelectorAll('#virtual-keypad .key').forEach(key => {
            key.addEventListener('click', () => {
                const keyValue = key.getAttribute('data-key');
                let currentValue = modalInput.value.replace(/-/g, '');

                if (keyValue === 'del') {
                    currentValue = currentValue.slice(0, -1);
                } else if (keyValue === 'clear') {
                    currentValue = '';
                } else if (currentValue.length < 8) {
                    currentValue += keyValue;
                }

                modalInput.value = formatRM(currentValue);
            });
        });

        // Tombol atur di modal
        document.getElementById('btn-set-rm').addEventListener('click', () => {
            mainInput.value = modalInput.value;
        });

        function formatRM(value) {
            return value.match(/.{1,2}/g)?.join('-') || '';
        }

        // Reset input modal saat modal dibuka
        document.getElementById('rmModal').addEventListener('show.bs.modal', () => {
            modalInput.value = formatRM(mainInput.value.replace(/-/g, ''));
        });
    </script>
@endpush