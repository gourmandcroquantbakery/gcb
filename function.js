const observerCallback = (entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            // Add the 'is-visible' class when the element comes into view
            entry.target.classList.add('is-visible');
            // Optional: Stop observing the element once it has animated
            observer.unobserve(entry.target); 
        }
    });
};

// Options for the observer
const observerOptions = {
    root: null, // Use the document viewport as the root
    rootMargin: '0px', // No margin
    threshold: 0.1 // Trigger when 10% of the element is visible
};

// Create a new Intersection Observer instance
const observer = new IntersectionObserver(observerCallback, observerOptions);

// Select all elements with the 'scroll-reveal' class and observe them
const revealElements = document.querySelectorAll('.scroll-reveal');
revealElements.forEach(element => {
    observer.observe(element);
});