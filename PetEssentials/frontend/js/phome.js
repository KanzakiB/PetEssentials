function scrollCategories(amount) {
    const categoriesContainer = document.querySelector('.all-categories');
    categoriesContainer.scrollBy({ left: amount, behavior: 'smooth' });
}

let currentIndex = 0;
let autoSlideInterval;

function moveSlide(direction) {
    const slider = document.querySelector('.slider');
    const slides = document.querySelectorAll('.slide');
    const totalSlides = slides.length;

    currentIndex += direction;

    if (currentIndex < 0) {
        currentIndex = totalSlides - 1;
    } else if (currentIndex >= totalSlides) {
        currentIndex = 0; 
    }

    slider.style.transform = `translateX(-${currentIndex * 100}%)`;
}

function autoMoveSlide() {
    autoSlideInterval = setInterval(() => {
        moveSlide(1);
    }, 3000); 
}

function manualMoveSlide(direction) {
    clearInterval(autoSlideInterval); 
    moveSlide(direction);
    autoMoveSlide(); 
}

window.onload = autoMoveSlide;

function redirectToProfile() {
    window.location.href = "customer_profile.php";
}
