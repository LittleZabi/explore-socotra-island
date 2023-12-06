const IntersectionOptions = {
  root: null,
  rootMargin: "-140px 0px -140px 0px",
  threshold: 0.45,
};
function viewPort(element) {
  let onView = element.attributes["data-onview"];
  let onOut = element.attributes["data-onout"];
  onView = onView ? onView.value : "fade-in";
  onOut = onOut ? onOut.value : "fade-out";
  const intersectionObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) element.classList.add(onView);
      else {
        element.classList.remove(onView);
        element.classList.add(onOut);
      }
    });
  }, IntersectionOptions);
  intersectionObserver.observe(element);
}
const lazyLoad = (element) => {
   const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        element.src = element.dataset.src;
        element.classList.remove("lazy-load");
        observer.unobserve(element);
      }
    });
  }, {});
  observer.observe(element)
};
window.addEventListener("DOMContentLoaded", (e) => {
  // intersection observer on view 
  const intersectionElement = document.querySelectorAll(".Observe");
  intersectionElement.forEach((element) => viewPort(element));
  // lazy loading images
  const lazyLoadImages = document.querySelectorAll(".lazy-load");
  lazyLoadImages.forEach((e) => lazyLoad(e));
  // adding and remvoing effect on header by scroll
  const header = document.getElementById("header");
  window.addEventListener("scroll", (e) => {
    if (scrollY > 150) {
      header.classList.add("bg");
    } else {
      header.classList.remove("bg");
    }
  });
});
