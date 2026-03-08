// register.js - multi-step registration handler
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('regForm');
  const steps = Array.from(document.querySelectorAll('.step'));
  const progressBar = document.getElementById('progressBar');
  let current = 0;

  // show initial step
  function showStep(index) {
    steps.forEach((s, i) => {
      s.hidden = i !== index;
    });
    const pct = Math.round(((index + 1) / steps.length) * 100);
    progressBar.style.width = pct + '%';
    // add fade animation on card
    const card = document.querySelector('.card');
    card.classList.remove('fade-in');
    void card.offsetWidth;
    card.classList.add('fade-in');
  }
  showStep(current);

  // next buttons
  document.querySelectorAll('.btn-next').forEach(btn => {
    btn.addEventListener('click', () => {
      const next = Number(btn.dataset.next) - 1;
      // validate current step basic: required inputs not empty
      const visible = steps[current];
      const invalid = Array.from(visible.querySelectorAll('input, select')).some(i => {
        if (i.required && !i.value.trim()) return true;
        return false;
      });
      if (invalid) {
        // highlight simple visual feedback
        visible.querySelectorAll('input,select').forEach(i => {
          if (i.required && !i.value.trim()) {
            i.style.boxShadow = '0 0 0 3px rgba(255,80,80,0.12)';
            setTimeout(()=> i.style.boxShadow = '', 1200);
          }
        });
        return;
      }
      current = next;
      showStep(current);
    });
  });

  // back buttons
  document.querySelectorAll('.btn-back').forEach(btn => {
    btn.addEventListener('click', () => {
      const back = Number(btn.dataset.back) - 1;
      current = back;
      showStep(current);
    });
  });

  // final submit: client-side password match check
  form.addEventListener('submit', (e) => {
    const pw = document.getElementById('password');
    const cpw = document.getElementById('confirm_password');
    if (pw && cpw) {
      if (pw.value !== cpw.value) {
        e.preventDefault();
        cpw.style.boxShadow = '0 0 0 4px rgba(255,80,80,0.18)';
        setTimeout(()=> cpw.style.boxShadow='', 1400);
        alert('Passwords do not match.');
        // jump to step 3
        current = 2;
        showStep(current);
        return false;
      }
      if (pw.value.length < 6) {
        e.preventDefault();
        alert('Password should be at least 6 characters.');
        return false;
      }
    }
    // let the form submit — server-side will validate again
  });
});

