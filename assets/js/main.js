
(() => {
  const root = document.documentElement;
  const saved = localStorage.getItem('neb-theme');
  if (saved) root.setAttribute('data-theme', saved);
  const toggle = document.getElementById('themeToggle');
  if (toggle) toggle.addEventListener('click', () => {
    const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    root.setAttribute('data-theme', next);
    localStorage.setItem('neb-theme', next);
  });
  if (window.AOS) AOS.init({duration:700, once:true});
  const topBtn = document.getElementById('scrollTop');
  const onScroll = () => topBtn && topBtn.classList.toggle('show', window.scrollY > 250);
  window.addEventListener('scroll', onScroll); onScroll();
  if (topBtn) topBtn.onclick = () => window.scrollTo({top:0,behavior:'smooth'});
  document.querySelectorAll('[data-counter]').forEach(el=>{const t=+el.dataset.counter;let n=0;const step=Math.max(1,Math.floor(t/80));const i=setInterval(()=>{n+=step;if(n>=t){n=t;clearInterval(i)}el.textContent=n},22)});
  document.querySelectorAll('[data-mcq]').forEach(block=>{
    const data = JSON.parse(block.dataset.mcq);
    let i=0,score=0; const qEl=block.querySelector('.q'); const opts=block.querySelector('.opts'); const fb=block.querySelector('.feedback'); const next=block.querySelector('.next'); const meter=block.querySelector('.meter');
    const draw=()=>{const q=data[i]; qEl.textContent=`${i+1}. ${q.q}`; opts.innerHTML=''; fb.textContent=''; meter.textContent=`${i+1}/${data.length}`;
      q.o.forEach((o,idx)=>{const b=document.createElement('button'); b.className='quiz-option'; b.textContent=o; b.onclick=()=>{if(block.dataset.locked==='1')return; block.dataset.locked='1'; const ok=idx===q.a; b.classList.add(ok?'correct':'wrong'); if(!ok) opts.children[q.a].classList.add('correct'); if(ok) score++; fb.textContent=(ok?'Correct! ':'Try this: ')+q.e;}; opts.appendChild(b);}); block.dataset.locked='0';};
    next.onclick=()=>{if(i<data.length-1){i++;draw()}else{qEl.textContent=`Quiz complete! Score ${score}/${data.length}`;opts.innerHTML='';fb.textContent='Great effort.';next.disabled=true;meter.textContent='Done'}};
    draw();
  });
  window.nebToast = msg => { const t=document.createElement('div'); t.textContent=msg; t.style.cssText='position:fixed;left:50%;bottom:20px;transform:translateX(-50%);background:#1a237e;color:#fff;padding:.6rem 1rem;border-radius:999px;z-index:9999'; document.body.appendChild(t); setTimeout(()=>t.remove(),2000)};
  fetch((window.NEB_API_PROGRESS || 'api/progress.php'), {method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({page:location.pathname, viewed_at:new Date().toISOString()})}).catch(()=>{});
})();
