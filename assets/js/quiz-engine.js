
const QUIZ_DATA = (() => {
  const topics11 = ['Computer System','Number System','Boolean Logic','Computer Software','Computer Memory','Networking','Security & Ethics','DBMS','Multimedia','Programming in C'];
  const topics12 = ['Advanced DBMS','Advanced Networking','Web Technology I','Web Technology II','Advanced C','OOP C++','Software Engineering','Recent Trends'];
  const makeQ = (grade, chapter, topic, i) => ({
    grade, chapter, difficulty: i % 3 === 0 ? 'hard' : i % 2 === 0 ? 'medium' : 'easy',
    question: `${topic}: Concept check ${i+1} (Grade ${grade} Chapter ${chapter})`,
    options: ['Option A','Option B','Option C','Option D'],
    answer: i % 4,
    explanation: `Review ${topic} notes for concept ${i+1}.`
  });
  const data = [];
  topics11.forEach((t,ci)=>{ for(let i=0;i<20;i++) data.push(makeQ(11, ci+1, t, i)); });
  topics12.forEach((t,ci)=>{ for(let i=0;i<20;i++) data.push(makeQ(12, ci+1, t, i)); });
  return data;
})();
class QuizEngine {
  constructor(){
    this.state={name:'',grade:11,chapter:'all',difficulty:'all',timer:0,idx:0,score:0,qs:[],answers:[]};
    this.init();
  }
  init(){
    this.setup=document.getElementById('setupScreen');this.quiz=document.getElementById('quizScreen');this.result=document.getElementById('resultScreen');
    document.getElementById('startQuizBtn')?.addEventListener('click',()=>this.start());
    document.getElementById('nextBtn')?.addEventListener('click',()=>this.next());
    document.getElementById('retryBtn')?.addEventListener('click',()=>location.reload());
    this.fillChapters();
  }
  fillChapters(){
    const gSel=document.getElementById('gradeSelect');const cSel=document.getElementById('chapterSelect'); if(!gSel||!cSel)return;
    const draw=()=>{const max=(+gSel.value===11)?10:8;cSel.innerHTML='<option value="all">Full syllabus</option>';for(let i=1;i<=max;i++){cSel.innerHTML+=`<option value="${i}">Chapter ${i}</option>`}};
    gSel.onchange=draw; draw();
  }
  start(){
    const name=(document.getElementById('studentName')?.value||'Student').trim();
    this.state.name=name; this.state.grade=+document.getElementById('gradeSelect').value; this.state.chapter=document.getElementById('chapterSelect').value;
    this.state.difficulty=document.getElementById('difficultySelect').value; this.state.timer=+document.getElementById('timerSelect').value;
    let qs=QUIZ_DATA.filter(q=>q.grade===this.state.grade);
    if(this.state.chapter!=='all')qs=qs.filter(q=>q.chapter===+this.state.chapter);
    if(this.state.difficulty!=='all')qs=qs.filter(q=>q.difficulty===this.state.difficulty);
    this.state.qs=qs.sort(()=>Math.random()-0.5).slice(0,10); this.state.idx=0; this.state.score=0; this.state.answers=[];
    this.setup.classList.add('d-none'); this.quiz.classList.remove('d-none'); this.render(); this.startTimer();
  }
  startTimer(){
    if(!this.state.timer)return; let left=this.state.timer; const timerEl=document.getElementById('timer'); timerEl.textContent=`${left}s`;
    this.ti=setInterval(()=>{left--; timerEl.textContent=`${left}s`; if(left<=0){clearInterval(this.ti); this.showResults();}},1000);
  }
  render(){
    const q=this.state.qs[this.state.idx]; if(!q)return this.showResults();
    document.getElementById('questionText').textContent=q.question;
    document.getElementById('progressBar').style.width=`${((this.state.idx+1)/this.state.qs.length)*100}%`;
    const wrap=document.getElementById('optionsWrap'); wrap.innerHTML='';
    q.options.forEach((o,i)=>{const b=document.createElement('button');b.className='quiz-option';b.textContent=o;b.onclick=()=>this.answer(i,b);wrap.appendChild(b)});
  }
  answer(i,btn){
    const q=this.state.qs[this.state.idx]; const ok=i===q.answer; if(ok)this.state.score++;
    [...document.querySelectorAll('#optionsWrap .quiz-option')].forEach((el,idx)=>{el.disabled=true; if(idx===q.answer)el.classList.add('correct'); if(idx===i&&!ok)el.classList.add('wrong');});
    document.getElementById('answerFeedback').textContent=(ok?'Correct! ':'Incorrect. ')+q.explanation;
    this.state.answers.push({q:q.question, selected:i, correct:q.answer});
  }
  next(){ this.state.idx++; if(this.state.idx<this.state.qs.length){document.getElementById('answerFeedback').textContent=''; this.render();} else this.showResults(); }
  showResults(){ clearInterval(this.ti); this.quiz.classList.add('d-none'); this.result.classList.remove('d-none');
    const pct=Math.round((this.state.score/this.state.qs.length)*100); document.getElementById('resultScore').textContent=`${this.state.score}/${this.state.qs.length}`;
    document.getElementById('resultPct').textContent=`${pct}%`;
    const rec={name:this.state.name,score:this.state.score,grade:this.state.grade,chapter:this.state.chapter,date:new Date().toISOString().slice(0,10)};
    const key='neb_leaderboard'; const arr=JSON.parse(localStorage.getItem(key)||'[]'); arr.push(rec); localStorage.setItem(key,JSON.stringify(arr.sort((a,b)=>b.score-a.score).slice(0,200)));
    fetch('../api/leaderboard.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(rec)}).catch(()=>{});
    document.getElementById('reviewList').innerHTML=this.state.answers.map((a,i)=>`<li>${i+1}. ${a.q} (Correct option: ${a.correct+1})</li>`).join('');
  }
}
document.addEventListener('DOMContentLoaded',()=>new QuizEngine());
