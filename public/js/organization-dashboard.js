(function(){
    'use strict';
    const root=document.getElementById('organization-dashboard');if(!root)return;
    const search=document.getElementById('od-search'),area=document.getElementById('od-area');
    function filter(){let count=0;const term=search.value.trim().toLocaleLowerCase();root.querySelectorAll('.od-module').forEach(card=>{card.hidden=!(card.dataset.moduleName.toLocaleLowerCase().includes(term)&&(!area.value||card.dataset.moduleArea===area.value));if(!card.hidden)count++;});document.getElementById('od-no-matches').hidden=count>0||!root.querySelector('.od-module');document.getElementById('od-visible-count').textContent='Showing '+count+' modules.';}
    search.addEventListener('input',filter);area.addEventListener('change',filter);
}());
