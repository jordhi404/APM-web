document.addEventListener('DOMContentLoaded', () => {
  const yearPicker = document.getElementById('yearPicker');
  const monthPicker = document.getElementById('monthPicker');
  const dayPicker = document.getElementById('dayPicker');
  const dobDisplay = document.getElementById('dob-display');
  const confirmButton = document.getElementById('confirmDob');

  // Pakai Bootstrap modal
  const modal = new bootstrap.Modal(document.getElementById('dob-modal'));

  const currentYear = new Date().getFullYear();

  // Populate Years (1900 - currentYear)
  for (let y = 1900; y <= currentYear; y++) {
    const div = document.createElement('div');
    div.textContent = y;
    div.dataset.value = y;
    div.classList.add('py-1', 'text-center', 'cursor-pointer');
    yearPicker.appendChild(div);
  }

  // Populate Months (01 - 12)
  for (let m = 1; m <= 12; m++) {
    const div = document.createElement('div');
    div.textContent = m.toString().padStart(2, '0');
    div.dataset.value = m.toString().padStart(2, '0');
    div.classList.add('py-1', 'text-center', 'cursor-pointer');
    monthPicker.appendChild(div);
  }

  function populateDays(year, month) {
    dayPicker.innerHTML = '';
    const daysInMonth = new Date(year, month, 0).getDate();
    for (let d = 1; d <= daysInMonth; d++) {
      const div = document.createElement('div');
      div.textContent = d.toString().padStart(2, '0');
      div.dataset.value = d.toString().padStart(2, '0');
      div.classList.add('py-1', 'text-center', 'cursor-pointer');
      dayPicker.appendChild(div);
    }
  }

  let selectedYear = '2000';
  let selectedMonth = '01';
  let selectedDay = '01';

  populateDays(selectedYear, selectedMonth);

  dobDisplay.addEventListener('click', () => {
    modal.show();
  });

  function updateResult() {
    const finalDate = `${selectedYear}-${selectedMonth}-${selectedDay}`;
    dobDisplay.value = finalDate;
  }

  confirmButton.addEventListener('click', () => {
    updateResult();
    modal.hide();
  });

  yearPicker.addEventListener('click', e => {
    if (e.target.dataset.value) {
      selectedYear = e.target.dataset.value;
      populateDays(selectedYear, selectedMonth);
      [...yearPicker.children].forEach(child => child.classList.remove('selected'));
      e.target.classList.add('selected');
    }
  });

  monthPicker.addEventListener('click', e => {
    if (e.target.dataset.value) {
      selectedMonth = e.target.dataset.value;
      populateDays(selectedYear, selectedMonth);
      [...monthPicker.children].forEach(child => child.classList.remove('selected'));
      e.target.classList.add('selected');
    }
  });

  dayPicker.addEventListener('click', e => {
    if (e.target.dataset.value) {
      selectedDay = e.target.dataset.value;
      [...dayPicker.children].forEach(child => child.classList.remove('selected'));
      e.target.classList.add('selected');
    }
  });

  updateResult();
});
