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
                        <label for="input-RM"><strong>MASUKKAN NO. REKAM MEDIS:</strong></label>
                        <input type="text" class="form-control" id="input-RM" placeholder="Contoh: 00-11-22-33" readonly><br>
                        <div id="virtual-keypad" class="custom-hidden">
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
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="form-group">
                        <label for="dob-display"><strong>MASUKKAN TANGGAL LAHIR PASIEN:</strong></label>
                        <input type="text" class="form-control" id="dob-display" readonly placeholder="Pilih Tanggal Lahir"><br>

                        <!-- Modal Scroll Picker -->
                        <div class="modal fade" id="dob-modal" tabindex="-1" aria-labelledby="dobModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content dob-modal-content">
                                    <div class="modal-body">
                                        <div class="d-flex justify-content-center" id="modal-title"><strong>SCROLL DAN PILIH</strong></div><br>
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
                                        <div class="text-center mt-3">
                                            <button id="confirmDob" class="btn btn-success">Atur</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-md-flex justify-content-md-center">
            <button class="btn btn-primary" id="btn-check">Cek Pasien</button>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="scripts/patientInfoScript.js"></script>
    <script src="scripts/date-pick.js"></script>
    <script>
        const input = document.getElementById('input-RM');
        const keypad = document.getElementById('virtual-keypad');

        input.addEventListener('focus', () => {
            keypad.classList.remove('custom-hidden');
        });

        document.querySelectorAll('.key').forEach(key => {
            key.addEventListener('click', () => {
                const keyValue = key.getAttribute('data-key');
                let currentValue = input.value.replace(/-/g, '');

                if (keyValue === 'del') {
                    currentValue = currentValue.slice(0, -1);
                } else if (keyValue === 'clear') {
                    currentValue = '';
                } else if (currentValue.length < 8) {
                    currentValue += keyValue;
                }

                input.value = formatRM(currentValue);
            });
        });

        // Hide the keypad when clicking outside of it.
        document.addEventListener('click', (event) => {
            if (!keypad.contains(event.target) && event.target !== input) {
                keypad.classList.add('custom-hidden');
            }
        });

        function formatRM(value) {
            return value.match(/.{1,2}/g)?.join('-') || '';
        }
    </script>
@endpush