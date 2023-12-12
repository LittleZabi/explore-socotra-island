let qoutes = document.querySelectorAll(".qoute-wrap .qoute");
const images = document.querySelectorAll(".gallery img");
if (window.innerWidth > 1180) {
  qoutes = [...qoutes, ...images];
} else {
  qoutes = images;
}
let prev = [];
const createIndex = () => {
  const c = () => Math.floor(Math.random() * (qoutes.length - 1));
  let index = c();
  while (prev.includes(index)) {
    index = c();
  }
  prev.push(index);
  if (prev.length >= 5) prev.splice(0, 1);
  return index;
};
setInterval(() => {
  let index = createIndex();
  qoutes[index].classList.toggle("active");
}, 300);
