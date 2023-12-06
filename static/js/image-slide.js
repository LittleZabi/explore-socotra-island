const animations = ["slide-in", "scale-in", "fade-in"];
const selectAnim = () =>
  animations[Math.ceil(Math.random() * animations.length - 1)];
let slideShow = undefined;
let imageIndex = 0;
window.addEventListener('load', (e=> {
    startSlides()
}))
const startSlides = () => {
  const images = document.querySelectorAll(".top-view-slide");
  images[0].style.display = "block";
  const displayNone = () =>
    images.forEach((img) => {
      img.style.display = "none";
      img.classList.remove("fade-in");
    });
  const playSlide = () => {
    if (imageIndex >= images.length) imageIndex = 0;
    if (imageIndex < 0) imageIndex = images.length - 1;
    displayNone();
    images[imageIndex].style.display = "block";
    images[imageIndex].classList.add("fade-in");
    slideShow();
  };
  let interval = undefined;
  slideShow = (num) => {
    if (num) {
      if (num === -1) imageIndex = imageIndex - 1 - 1;
      else imageIndex = imageIndex - 1 + num;
      num = undefined;
      if (interval) clearTimeout(interval);
      playSlide();
    } else {
      interval = setTimeout(playSlide, 3000);
      imageIndex++;
    }
  };
  slideShow();
};
