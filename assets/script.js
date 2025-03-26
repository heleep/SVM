
const images = ["assets/images/1.jpg","assets/images/2.jpg","assets/images/3.jpg"];
let currentIndex = 0;
const sliderImg = document.getElementById("slider-img");

// Change the image every 3 seconds
setInterval(() => {
  currentIndex = (currentIndex + 1) % images.length;
  console.log(sliderImg.src);
  sliderImg.src = images[currentIndex];
  
}, 3000);

// Function to include HTML components
function loadComponent(id, file) {
  fetch(file)
      .then(response => response.text())
      .then(data => document.getElementById(id).innerHTML = data)
      .catch(error => console.error(`Error loading ${file}:`, error));
}

// Load Navbar and Footer
document.addEventListener("DOMContentLoaded", () => {
  loadComponent("navbar", "includes/navbar.html");
  loadComponent("footer", "includes/footer.html");
});
