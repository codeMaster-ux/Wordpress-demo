document.addEventListener("DOMContentLoaded", function () {
  const navbar = document.querySelector(".drone-navbar");
  let bannerHeight;

  // 判断是否为手机端
  if (window.innerWidth <= 768) {
    // 假设 768px 以下为手机端
    bannerHeight =
      document.querySelector(".mobile-drone-banner-cl").offsetHeight - 60;
  } else {
    bannerHeight = document.querySelector(".drone-banner-cl").offsetHeight - 60;
  }

  // Debounce 滚动事件，限制触发频率
  let isScrolling;
  window.addEventListener("scroll", function () {
    clearTimeout(isScrolling);
    isScrolling = setTimeout(function () {
      if (window.scrollY > bannerHeight) {
        navbar.classList.add("drone-scrolled");
      } else {
        navbar.classList.remove("drone-scrolled");
      }
    }, 100); // 100ms 间隔
  });
});
