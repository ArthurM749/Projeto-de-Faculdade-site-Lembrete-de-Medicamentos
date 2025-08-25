// notificacoes_css.js (versão Opera GX - parar som notification.mp3 ao fechar)

(function(){
  if (typeof window === 'undefined') return;

  const CHECK_INTERVAL_MS = 30000;
  const SOUND_FILE = 'notification.mp3';
  const TOAST_DURATION_MS = 10000;
  const lastNotified = new Map();

  // Controle do áudio
  let audioAtual = null; // variável global

  function tocarSom() {
    console.log('Tocando som... audioAtual antes:', audioAtual);
    pararSom();
    console.log('Após parar, audioAtual:', audioAtual);
    audioAtual = new Audio(SOUND_FILE);
    console.log('Novo áudio criado:', audioAtual);

    audioAtual.play().catch(err => console.warn('Erro ao tocar som:', err));
  }
  
  function pararSom() {
    if (audioAtual) {
      try {
        audioAtual.pause();
        audioAtual.currentTime = 0;
      } catch(e) {
        console.warn('Erro ao parar som:', e);
      }
      audioAtual = null;
      console.log('Parando som... audioAtual:', audioAtual);

    }
  }
  

  // Utilitários de tempo
  function pad2(n){ return String(n).padStart(2,'0'); }
  function agoraHHMM(date){ const d = date || new Date(); return pad2(d.getHours()) + ':' + pad2(d.getMinutes()); }
  function formatKeyForNow(date){ const d = date || new Date(); return d.getFullYear() + '-' + pad2(d.getMonth()+1) + '-' + pad2(d.getDate()) + ' ' + pad2(d.getHours()) + ':' + pad2(d.getMinutes()); }

  // CSS
  const CSS = `
.__medtoast__container {
  position: fixed;
  left: 50%;
  top: 20px;
  transform: translateX(-50%);
  right: auto;
  bottom: auto;
  z-index: 999999;
  display: flex;
  flex-direction: column;
  gap: 12px;
  align-items: center;
  pointer-events: none;
  font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
}
.__medtoast {
  pointer-events: auto;
  min-width: 320px;
  max-width: 420px;
  background: linear-gradient(180deg, #ffffff, #fbfdff);
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(11,20,30,0.18);
  border-left: 6px solid #0b78e3;
  padding: 14px 16px;
  color: #111;
  transform-origin: center top;
  animation: __medtoast_in 260ms cubic-bezier(.2,.9,.3,1);
  overflow: hidden;
}
.__medtoast h4 { margin: 0 0 6px 0; font-size: 16px; font-weight: 700; }
.__medtoast p { margin: 0; font-size: 14px; color: #222; }
.__medtoast .__medtoast_actions { margin-top: 10px; display: flex; gap: 8px; justify-content: flex-end; }
.__medtoast button { cursor: pointer; border: none; padding: 7px 12px; border-radius: 8px; font-weight: 700; font-size: 13px; }
.__medtoast .closebtn { background: transparent; color: #555; border: 1px solid #e6e6e6; padding: 6px 10px; }
.__medtoast .takenbtn { background: #0b78e3; color: #fff; padding: 7px 12px; }
@keyframes __medtoast_in { from { opacity: 0; transform: translateY(-8px) scale(.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
@keyframes __medtoast_out { from { opacity: 1; transform: translateY(0) scale(1);} to { opacity: 0; transform: translateY(-6px) scale(.98);} }
.__medtoast.closing { animation: __medtoast_out 200ms linear forwards; }
`;

  function injectCSS(){
    if (document.getElementById('__medtoast_css')) return;
    const s = document.createElement('style');
    s.id = '__medtoast_css';
    s.innerHTML = CSS;
    document.head.appendChild(s);
  }

  function getContainer(){
    let c = document.querySelector('.__medtoast__container');
    if (!c){
      c = document.createElement('div');
      c.className = '__medtoast__container';
      document.body.appendChild(c);
    }
    return c;
  }

  function escapeHtml(s){
    return String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  }

  function createToast(opts){
    injectCSS();
    const container = getContainer();
    const toast = document.createElement('div');
    toast.className = '__medtoast';
    toast.tabIndex = 0;
    const title = opts.title || 'Hora do remédio';
    const message = opts.message || 'Tome o seu medicamento';
    const id = opts.id || ('toast-' + Date.now());
    const duration = typeof opts.duration === 'number' ? opts.duration : TOAST_DURATION_MS;

    toast.innerHTML = `
      <h4>${escapeHtml(title)}</h4>
      <p>${escapeHtml(message)}</p>
      <div class="__medtoast_actions">
        ${opts.actionUrl ? '<button class="takenbtn">Tomei</button>' : ''}
        <button class="closebtn">Fechar</button>
      </div>
    `;

    function close(){
      pararSom();
      toast.classList.add('closing');
      setTimeout(()=> { try{ toast.remove(); } catch(e){} }, 220);
    }

    const btnClose = toast.querySelector('.closebtn');
    if (btnClose) {
      btnClose.addEventListener('click', () => {
        pararSom(); // garante que o som pare
        close();
      });
    }
    btnClose && btnClose.addEventListener('click', close);

    const btnTaken = toast.querySelector('.takenbtn');
    if (btnTaken){
      btnTaken.addEventListener('click', () => {
        pararSom();
        btnTaken.disabled = true;
        btnTaken.textContent = 'Registrando...';
        if (opts.actionUrl) {
          fetch(opts.actionUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, takenAt: new Date().toISOString() })
          }).then(() => {
            btnTaken.textContent = 'OK';
            setTimeout(close, 700);
          }).catch(() => {
            btnTaken.textContent = 'Erro';
            setTimeout(close, 1200);
          });
        } else {
          close();
        }
      });
    }

    let timeoutHandle = null;
    function startTimer(){ if (duration > 0) timeoutHandle = setTimeout(()=> close(), duration); }
    function stopTimer(){ if (timeoutHandle) { clearTimeout(timeoutHandle); timeoutHandle = null; } }
    toast.addEventListener('mouseenter', stopTimer);
    toast.addEventListener('focus', stopTimer);
    toast.addEventListener('mouseleave', startTimer);
    toast.addEventListener('blur', startTimer);

    container.appendChild(toast);
    toast.focus();
    tocarSom();
    startTimer();
  }

  function extrairHorario(horEl){
    if (!horEl) return null;
    if (horEl.dataset && horEl.dataset.horario) {
      return horEl.dataset.horario.trim().padStart(5,'0');
    }
    const txt = horEl.textContent.trim();
    const m = txt.match(/(\d{1,2}:\d{2})/);
    return m ? m[1].padStart(5,'0') : null;
  }

  function verificarUmaVez(){
    const nowHHMM = agoraHHMM(new Date());
    const nowKey = formatKeyForNow(new Date());

    document.querySelectorAll('.lembrete').forEach((lembrete, idx) => {
      const horEl = lembrete.querySelector('.horario');
      const horario = extrairHorario(horEl);
      if (!horario) return;

      if (horario === nowHHMM) {
        let keyId = lembrete.dataset.id || lembrete.id || (`lembrete-${idx}`);
        const last = lastNotified.get(keyId);
        if (last === nowKey) return;

        const nome = (lembrete.querySelector('h4') ? lembrete.querySelector('h4').textContent : 'seu medicamento').trim();
        const actionUrl = lembrete.dataset.action || lembrete.getAttribute('data-action') || null;

        createToast({
          title: 'Hora do remédio',
          message: `Tome: ${nome}`,
          id: keyId,
          actionUrl: actionUrl || null,
          duration: TOAST_DURATION_MS
        });

        lastNotified.set(keyId, nowKey);
      }
    });
  }

  document.addEventListener('DOMContentLoaded', function(){
    verificarUmaVez();
    setInterval(verificarUmaVez, CHECK_INTERVAL_MS);
  });

  window.__medtoast = { createToast, getContainer, pararSom };

})();
