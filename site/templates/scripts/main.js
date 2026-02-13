document.addEventListener('DOMContentLoaded',function(){
  var hamburger=document.querySelector('.hamburger');
  var menu=document.getElementById('nav-menu');
  if(hamburger&&menu){
    hamburger.addEventListener('click',function(){
      var expanded=this.getAttribute('aria-expanded')==='true';
      this.setAttribute('aria-expanded',expanded?'false':'true');
      menu.classList.toggle('open');
    });
  }
  var links=document.querySelectorAll('#nav-menu a');
  var path=location.pathname.replace(/\/+$/,'/');
  links.forEach(function(a){
    var href=a.getAttribute('href')||'';
    if(href.replace(/\/+$/,'/')===path)a.classList.add('active');
  });
  var observer=new IntersectionObserver(function(entries){
    entries.forEach(function(entry){
      if(entry.isIntersecting)entry.target.classList.add('in-view');
    });
  },{threshold:0.15});
  document.querySelectorAll('[data-animate]').forEach(function(el){observer.observe(el)});
  document.querySelectorAll('a[href^="#"]').forEach(function(a){
    a.addEventListener('click',function(e){
      var id=this.getAttribute('href').slice(1);
      var target=document.getElementById(id);
      if(target){
        e.preventDefault();
        target.scrollIntoView({behavior:'smooth',block:'start'});
      }
    });
  });
  var filterBar=document.querySelector('.filters');
  if(filterBar){
    var chips=filterBar.querySelectorAll('.chip--filter');
    var cards=document.querySelectorAll('[data-categories]');
    chips.forEach(function(chip){
      chip.addEventListener('click',function(){
        chips.forEach(function(c){c.classList.remove('active')});
        this.classList.add('active');
        var cat=this.getAttribute('data-cat');
        cards.forEach(function(card){
          var cats=(card.getAttribute('data-categories')||'').split(/\s+/).filter(Boolean);
          var show=(cat==='all')||(cats.indexOf(cat)!==-1);
          card.style.display=show?'':'none';
        });
      });
    });
  }
});
