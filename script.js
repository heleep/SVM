// Slider functionality
const slides = [
  {
    image: 'https://www.pixelstalk.net/wp-content/uploads/2016/06/Nature-Wallpaper.jpg',
    title: 'Welcome to SVM Institute of Technology',
    description: 'Empowering future innovators with excellence in education.'
  },
  {
    image: 'https://picsum.photos/600/400',
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

document.addEventListener('click', (e) => {
  if (e.target.closest('.event-link')) {
    console.log('✅ Event link clicked');
  }
});


function updateStatus(message, isError = false) {
  const statusElement = document.getElementById('statusMessage');
  if (!statusElement) return;
  
  statusElement.textContent = message;
  statusElement.className = isError ? 'status error' : 'status';
}

function formatDate(dateString) {
  try {
    const eventDate = new Date(dateString);
    if (isNaN(eventDate)) {
      throw new Error('Invalid date');
    }
    return eventDate.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  } catch (e) {
    console.error('Date formatting error:', e);
    return 'Invalid date';
  }
}

// CORRECTED createEventCard function
function createEventCard(event) {
  if (!event?.id || !event.SVM_Event || !event.Event_date) {
    console.warn("Incomplete event data", event);
    return null;
  }

  const link = document.createElement('a');
  link.href = `template/events_detail.php?id=${event.id}`;
  link.classList.add('event-card-link');

  const card = document.createElement('div');
  card.className = 'event-card';
  card.innerHTML = `
    <div class="event-id">${event.id}</div>
    <h3>${event.SVM_Event}</h3>
    <p><strong>Date:</strong> ${formatDate(event.Event_date)}</p>
    <p>Click to view event details</p>
  `;
  link.appendChild(card);
  return link;
}
