
const C_EXERCISES = Array.from({length:32}).map((_,i)=>({
  id:i+1,
  topic:['Hello','Variables','I/O','Conditionals','Loops','Arrays','Strings','Functions','Recursion','Pointers','File I/O','Structures'][Math.floor(i/3)] || 'Mixed',
  title:`Exercise ${i+1}`,
  problem:`Solve C programming task #${i+1}.`,
  starter:`#include <stdio.h>
int main(){
    // TODO: write solution ${i+1}
    return 0;
}`,
  hint:`Think about algorithm for task ${i+1}.`,
  solution:`// Sample solution for task ${i+1}`,
  output:`Expected output pattern for exercise ${i+1}`
}));
const HTML_CSS_EXERCISES = Array.from({length:8}).map((_,i)=>({
  id:i+1,title:`Web Exercise ${i+1}`,theory:`Concept explanation ${i+1}`,
  html:`<div class="box">Lesson ${i+1}</div>`,css:`.box{padding:1rem;background:#00897b;color:#fff}`
}));
function initCM(id,mode,value,ro=false){ if(!window.CodeMirror) return null; const ta=document.getElementById(id); if(!ta) return null; return CodeMirror.fromTextArea(ta,{mode,lineNumbers:true,theme:'material-darker',readOnly:ro,value}); }
function saveProgress(key,obj){localStorage.setItem(key,JSON.stringify(obj));}
function getProgress(key){return JSON.parse(localStorage.getItem(key)||'{}');}
document.addEventListener('DOMContentLoaded',()=>{
  const cList=document.getElementById('cExerciseList'); const cTitle=document.getElementById('cTitle');
  if(cList){
    const prog=getProgress('neb_c_progress');
    C_EXERCISES.forEach(ex=>{const b=document.createElement('button');b.className='btn btn-outline-primary w-100 text-start mb-2';b.textContent=`${ex.id}. ${ex.title}${prog[ex.id]?' ✓':''}`;b.onclick=()=>loadEx(ex);cList.appendChild(b)});
    const cm=initCM('cEditor','text/x-csrc','');
    const loadEx=(ex)=>{cTitle.textContent=`${ex.title} - ${ex.topic}`;document.getElementById('cProblem').textContent=ex.problem;document.getElementById('cHint').textContent=ex.hint;document.getElementById('cSolution').textContent=ex.solution;document.getElementById('expectedOutput').textContent=ex.output;cm?.setValue(ex.starter);};
    loadEx(C_EXERCISES[0]);
    document.getElementById('markDoneBtn')?.addEventListener('click',()=>{const id=+document.getElementById('cTitle').textContent.match(/Exercise (\d+)/)?.[1]; if(id){prog[id]=true;saveProgress('neb_c_progress',prog); nebToast('Exercise marked as completed');}})
  }
  if(document.getElementById('htmlEditor')){
    const htmlCM=initCM('htmlEditor','xml','<h1>Hello</h1>'); const cssCM=initCM('cssEditor','css','body{font-family:sans-serif;}');
    const render=()=>{const frame=document.getElementById('previewFrame'); if(!frame)return; const doc=frame.contentDocument||frame.contentWindow.document; doc.open(); doc.write(`<style>${cssCM?.getValue()||''}</style>${htmlCM?.getValue()||''}`); doc.close();};
    let t; [htmlCM,cssCM].forEach(cm=>cm?.on('change',()=>{clearTimeout(t); t=setTimeout(render,250);})); render();
    const lesson=document.getElementById('htmlLessonList');
    HTML_CSS_EXERCISES.forEach(ex=>{const li=document.createElement('button');li.className='btn btn-outline-secondary w-100 text-start mb-2';li.textContent=ex.title;li.onclick=()=>{htmlCM?.setValue(ex.html);cssCM?.setValue(ex.css);document.getElementById('lessonTheory').textContent=ex.theory;render();};lesson?.appendChild(li)});
  }
});
