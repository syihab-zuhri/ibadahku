// guru/js/cetak.js
// Pastikan file ini dimuat setelah Bootstrap JS.
// IIFE untuk menghindari konflik global
(function () {
  'use strict';

  // helper: format Date -> YYYY-MM-DD
  function fmtYMD(d) {
    var mm = String(d.getMonth()+1).padStart(2,'0');
    var dd = String(d.getDate()).padStart(2,'0');
    return d.getFullYear() + '-' + mm + '-' + dd;
  }

  // compute start of week (Monday) for a given date
  function getWeekRange(date) {
    // date is Date object
    var day = date.getDay(); // 0 = Sun, 1 = Mon, ...
    var diffToMon = (day === 0) ? -6 : (1 - day);
    var monday = new Date(date);
    monday.setDate(date.getDate() + diffToMon);
    var sunday = new Date(monday);
    sunday.setDate(monday.getDate() + 6);
    return { start: monday, end: sunday };
  }

  // validasi tanggal format YYYY-MM-DD (frontend)
  function isValidDateStr(s) {
    return /^\d{4}-\d{2}-\d{2}$/.test(s) && !isNaN(Date.parse(s));
  }

  // UPDATE UI boxes visibility based on selected mode
  function updateBoxes() {
    var modeCustom = document.getElementById('modeCustom');
    var modeMonth  = document.getElementById('modeMonth');
    var modeWeek   = document.getElementById('modeWeek');

    var boxCustom = document.getElementById('box-custom');
    var boxMonth  = document.getElementById('box-month');
    var boxWeek   = document.getElementById('box-week');

    if (!boxCustom || !boxMonth || !boxWeek) return;

    if (modeCustom && modeCustom.checked) {
      boxCustom.style.display = '';
      boxMonth.style.display = 'none';
      boxWeek.style.display = 'none';
    } else if (modeMonth && modeMonth.checked) {
      boxCustom.style.display = 'none';
      boxMonth.style.display = '';
      boxWeek.style.display = 'none';
    } else if (modeWeek && modeWeek.checked) {
      boxCustom.style.display = 'none';
      boxMonth.style.display = 'none';
      boxWeek.style.display = '';
    }
  }

  // main init after DOM loaded
  document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('formCetak');
    if (!form) return;

    // radio inputs
    var radios = document.querySelectorAll('input[name="mode"]');
    radios.forEach(function(r) { r.addEventListener('change', updateBoxes); });

    // init UI
    updateBoxes();

    form.addEventListener('submit', function (ev) {
      ev.preventDefault();

      var mode = document.querySelector('input[name="mode"]:checked');
      mode = mode ? mode.value : 'custom';

      var start = '';
      var end = '';

      if (mode === 'custom') {
        var cs = document.getElementById('customStart').value;
        var ce = document.getElementById('customEnd').value;
        if (!cs || !ce) { alert('Silakan pilih tanggal mulai dan akhir untuk Custom.'); return; }
        if (cs > ce) { alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir.'); return; }
        start = cs; end = ce;
      } else if (mode === 'month') {
        var m = document.getElementById('monthInput').value;
        if (!m) { alert('Silakan pilih bulan.'); return; }
        var parts = m.split('-');
        var y = parseInt(parts[0],10);
        var mm = parseInt(parts[1],10) - 1;
        var first = new Date(y, mm, 1);
        var last = new Date(y, mm + 1, 0);
        start = fmtYMD(first);
        end = fmtYMD(last);
      } else if (mode === 'week') {
        var w = document.getElementById('weekInput').value;
        if (!w) { alert('Silakan pilih tanggal dalam minggu yang ingin dicetak.'); return; }
        var date = new Date(w + 'T00:00:00');
        if (isNaN(date.getTime())) { alert('Tanggal tidak valid.'); return; }
        var range = getWeekRange(date);
        start = fmtYMD(range.start);
        end = fmtYMD(range.end);
      }

      // set hidden inputs
      var hiddenStart = document.getElementById('hiddenStart');
      var hiddenEnd   = document.getElementById('hiddenEnd');
      var hiddenMode  = document.getElementById('hiddenMode');
      if (hiddenStart) hiddenStart.value = start;
      if (hiddenEnd)   hiddenEnd.value   = end;
      if (hiddenMode)  hiddenMode.value  = mode;

      // submit form (target _blank will open new tab)
      form.submit();

      // close modal (Bootstrap 5)
      try {
        var modalEl = document.getElementById('modalCetak');
        var modalInstance = bootstrap.Modal.getInstance(modalEl);
        if (modalInstance) modalInstance.hide();
      } catch (e) { /* ignore */ }
    });
  });

  // expose for debugging if needed
  window.CetakPdfHelper = {
    fmtYMD: fmtYMD,
    getWeekRange: getWeekRange,
    updateBoxes: updateBoxes
  };

})();
