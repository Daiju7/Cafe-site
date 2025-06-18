document.addEventListener("DOMContentLoaded", function() {
  const nav = document.querySelector('.l_header-nav');
  const hamburger = document.querySelector('.m_hamburger');
  const overlay = document.querySelector('.hamburger-overlay');
  
  // ハンバーガーアイコンで開閉
  hamburger.addEventListener('click', function(e) {
    nav.classList.toggle('open');
    e.stopPropagation();
  });
  
  // オーバーレイ押して閉じる
  overlay.addEventListener('click', function() {
    nav.classList.remove('open');
  });

  document.querySelectorAll('.l_header-nav_item a').forEach(item => {
    item.addEventListener('click', function() {
      nav.classList.remove('open');
    });
  });

  // --- プライバシーポリシーモーダル ---
  const modal = document.querySelector('.privacyPolicy-modal');
  const openBtn = document.querySelector('.privacyPolicy-check');
  const closeBtn = modal ? modal.querySelector('.close-button') : null;

  openBtn.addEventListener('click', function(e) {
    e.preventDefault();
    modal.classList.add('open');
    document.body.style.overflow = 'hidden'; // 背景スクロール禁止
  });

  // 閉じるボタンで閉じる
  closeBtn.addEventListener('click', function() {
    modal.classList.remove('open');
    document.body.style.overflow = ''; // 背景スクロール許可
  });

  // モーダルの背景部分クリックで閉じる
  modal.addEventListener('click', function(e) {
    if (e.target === modal) {
      modal.classList.remove('open');
      document.body.style.overflow = '';
    }
  });
});

window.addEventListener("load", () => {
  gsap.from(".kv1", {
    x: -100,
    opacity: 0,
    duration: 3,
    ease: "power2.out"
  });
});

gsap.registerPlugin(ScrollTrigger);
gsap.from(".kv2", {
  x: 150,
  opacity: 0,
  delay: 0.3,
  duration: 3,
  ease: "power2.out",
  scrollTrigger: {
    trigger: ".kv2",     
    start: "top 60%", 
    toggleActions: "play none none none"
  }
});