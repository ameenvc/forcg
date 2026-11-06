(function(){
    'use strict';
    const root=document.getElementById('organization-dashboard');if(!root)return;
    const search=document.getElementById('od-search'),area=document.getElementById('od-area');
    const tabs=[...root.querySelectorAll('[data-dashboard-tab]')];
    const tablist=root.querySelector('.od-dashboard-tabs');tablist.setAttribute('role','tablist');
    function filter(){let count=0;const term=search.value.trim().toLocaleLowerCase();root.querySelectorAll('.od-module').forEach(card=>{card.hidden=!(card.dataset.moduleName.toLocaleLowerCase().includes(term)&&(!area.value||card.dataset.moduleArea===area.value));if(!card.hidden)count++;});document.getElementById('od-no-matches').hidden=count>0||!root.querySelector('.od-module');document.getElementById('od-visible-count').textContent='Showing '+count+' modules.';}
    function select(id,focus){tabs.forEach(tab=>{const active=tab.dataset.dashboardTab===id;tab.setAttribute('aria-selected',String(active));tab.tabIndex=active?0:-1;document.getElementById(tab.dataset.dashboardTab).hidden=!active;if(active&&focus)tab.focus();});}
    tabs.forEach((tab,index)=>{tab.id='tab-'+tab.dataset.dashboardTab;tab.setAttribute('role','tab');tab.setAttribute('aria-controls',tab.dataset.dashboardTab);const panel=document.getElementById(tab.dataset.dashboardTab);panel.setAttribute('role','tabpanel');panel.setAttribute('aria-labelledby',tab.id);tab.addEventListener('click',()=>select(tab.dataset.dashboardTab,false));tab.addEventListener('keydown',event=>{let next;if(event.key==='ArrowRight')next=(index+1)%tabs.length;if(event.key==='ArrowLeft')next=(index-1+tabs.length)%tabs.length;if(event.key==='Home')next=0;if(event.key==='End')next=tabs.length-1;if(next!==undefined){event.preventDefault();select(tabs[next].dataset.dashboardTab,true);history.replaceState(null,'','#'+tabs[next].dataset.dashboardTab);}});});
    root.querySelectorAll('[data-open-area],[data-dashboard-open]').forEach(link=>link.addEventListener('click',()=>{select('od-modules',false);if(link.dataset.openArea){area.value=link.dataset.openArea;search.value='';filter();}document.getElementById('od-modules').focus({preventScroll:true});}));
    window.addEventListener('hashchange',()=>{if(['#od-executive','#od-modules'].includes(location.hash))select(location.hash.slice(1),false);});
    select(location.hash==='#od-modules'?'od-modules':'od-executive',false);
    search.addEventListener('input',filter);area.addEventListener('change',filter);
}());
