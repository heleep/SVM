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

function addTestCard() {
  const container = document.getElementById('eventContainer');
  if (!container) return;
  
  const testEvent = {
    id: 999,
    SVM_Event: "Test Event (Debug)",
    Event_date: new Date().toISOString().split('T')[0]
  };
  
  const testCard = createEventCard(testEvent);
  testCard.querySelector('.event-card').classList.add('test-card');
  container.appendChild(testCard);
  
  updateStatus("Test card added - Check if clickable");
}

async function fetchEvents() {
  const container = document.getElementById('eventContainer');
  if (!container) {
    console.error("Event container not found");
    return;
  }
  
  updateStatus("Loading events from database...");
  
  try {
    const response = await fetch('template/get_events.php');
    
    if (!response.ok) {
      throw new Error(`Server returned ${response.status} ${response.statusText}`);
    }
    
    const data = await response.json();
    console.log("Fetched events data:", data); // For debugging
    
    if (!Array.isArray(data)) {
      throw new Error("Response data is not an array");
    }
    
    // Clear container
    container.innerHTML = '';
    
    if (data.length === 0) {
      updateStatus("No upcoming events found");
      return;
    }
    
    data.forEach(event => {
      if (!event.id) {
        console.error('Event missing ID:', event);
        return;
      }
      
      const eventCardElement = createEventCard(event);
      container.appendChild(eventCardElement);
    });
    
    updateStatus(`Loaded ${data.length} events`);
    
  } catch (error) {
    console.error('Error fetching events:', error);
    updateStatus("Error loading events. Please try again later.", true);
  }
}

// Highlight active navigation link
document.addEventListener('DOMContentLoaded', function() {
  const currentPage = window.location.pathname.split('/').pop() || 'index.php';
  const navLinks = document.querySelectorAll('.nav-link');
  
  navLinks.forEach(link => {
    const linkHref = link.getAttribute('href');
    if (linkHref === currentPage || 
        (currentPage === 'index.php' && linkHref === '#')) {
      link.classList.add('active');
    } else {
      link.classList.remove('active');
    }
  });

  // Add event listeners
  document.getElementById('reloadBtn')?.addEventListener('click', fetchEvents);
  document.getElementById('testBtn')?.addEventListener('click', addTestCard);
  
  // Add simulate error button if needed
  document.getElementById('simulateError')?.addEventListener('click', function() {
    updateStatus("Simulated error for testing", true);
  });
  
  // Fetch events only if event container exists
  if (document.getElementById('eventContainer')) {
    fetchEvents();
  }
});

const debounce = (func, wait) => {
  let timeout;
  return (...args) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => func.apply(this, args), wait);
  };
};
