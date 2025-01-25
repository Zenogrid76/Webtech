document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.review-box'); // All testimonial slides
    const prevBtn = document.getElementById('prev-btn'); // Previous button
    const nextBtn = document.getElementById('next-btn'); // Next button
    let currentSlide = 0; // Track the current slide

    // Show the slide based on the index
    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.remove('active'); // Hide all slides
            if (i === index) {
                slide.classList.add('active'); // Show the current slide
            }
        });
    }

    // Event listener for Previous button
    prevBtn.addEventListener('click', () => {
        currentSlide = (currentSlide > 0) ? currentSlide - 1 : slides.length - 1;
        showSlide(currentSlide);
    });

    // Event listener for Next button
    nextBtn.addEventListener('click', () => {
        currentSlide = (currentSlide < slides.length - 1) ? currentSlide + 1 : 0;
        showSlide(currentSlide);
    });

    // Initialize the first slide
    showSlide(currentSlide);
});
