// Slider functionality
const slides = [
  {
    image: 'https://hd.wallpaperswide.com/thumbs/most_beautiful_mountain_scenery-t2.jpg',
    title: 'Welcome to SVM Institute of Technology',
    description: 'Empowering future innovators with excellence in education.'
  },
  {
    image: 'assets/images/1.jpg',
    title: 'Innovative Research',
    description: 'Advancing technology through groundbreaking research.'
  },
  {
    image: 'https://images.unsplash.com/photo-1562774053-701939374585?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80',
    title: 'Modern Facilities',
    description: 'Experience state-of-the-art labs and learning spaces.'
  },
  {
    image: 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80',
    title: 'Campus Life',
    description: 'A vibrant community that fosters growth and creativity.'
  },
  {
    image: 'https://images.unsplash.com/photo-1592280771190-3e2e4d571952?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80',
    title: 'Global Perspective',
    description: 'Connecting students and ideas from around the world.'
  }
];

// Create slides
const slidesContainer = document.querySelector('.slides');
slides.forEach((slide, index) => {
  const slideElement = document.createElement('div');
  slideElement.className = `slide ${index === 0 ? 'active' : ''}`;
  slideElement.style.backgroundImage = `url(${slide.image})`;

  const content = document.createElement('div');
  content.className = 'slide-content';
  content.innerHTML = `
    <h1>${slide.title}</h1>
    <p>${slide.description}</p>
    <button>
      Learn More
      <i class="lucide lucide-chevron-right"></i>
    </button>
  `;

  slideElement.appendChild(content);
  slidesContainer.appendChild(slideElement);
});

// Automatic slider
let currentSlide = 0;
const slideElements = document.querySelectorAll('.slide');

function nextSlide() {
  slideElements[currentSlide].classList.remove('active');
  currentSlide = (currentSlide + 1) % slideElements.length;
  slideElements[currentSlide].classList.add('active');
}

setInterval(nextSlide, 3000);

// Feature cards animation
const featureCards = document.querySelectorAll('.feature-card');
const observerOptions = {
  threshold: 0.1,
  rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
    }
  });
}, observerOptions);

featureCards.forEach(card => {
  observer.observe(card);
});

// Initialize Lucide icons
// lucide.createIcons();

// Fetch events from the database and display them in the event section
fetch('template/get_events.php')
  .then(response => response.json())
  .then(data => {
    const container = document.getElementById('eventContainer');
    if(data.length === 0) {
      container.innerHTML = "<p>No events available at the moment.</p>";
      return;
    }
    data.forEach(eventItem => {
      const eventCard = document.createElement('div');
      eventCard.className = 'event-card';
      eventCard.innerHTML = `
        <h3>${eventItem.SVM_Event}</h3>
        <p>${eventItem.Event_date}</p>
      `;
      container.appendChild(eventCard);
    });
  })
  .catch(error => console.error('Error fetching events:', error));
