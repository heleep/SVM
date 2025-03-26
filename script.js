
const images = ["assets/images/1.jpg","assets/images/2.jpg","assets/images/3.jpg"];
let currentIndex = 0;
const sliderImg = document.getElementById("slider-img");

// Change the image every 3 seconds
setInterval(() => {
  currentIndex = (currentIndex + 1) % images.length;
  sliderImg.src = images[currentIndex];
}, 5000);