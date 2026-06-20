        let currentIndex = 0;
        let autoSlideInterval;

        // Function to move the slider
        function moveSlide(direction) {
            const slider = document.querySelector('.slider');
            const slides = document.querySelectorAll('.slide');
            const totalSlides = slides.length;

            // Update currentIndex
            currentIndex += direction;

            // Wrap around logic
            if (currentIndex < 0) {
                currentIndex = totalSlides - 1; // Go to the last slide
            } else if (currentIndex >= totalSlides) {
                currentIndex = 0; // Go to the first slide
            }

            // Update slider position
            slider.style.transform = `translateX(-${currentIndex * 100}%)`;
        }

        // Auto-move slider
        function autoMoveSlide() {
            autoSlideInterval = setInterval(() => {
                moveSlide(1);
            }, 3000); // Change slide every 3 seconds
        }

        // Stop auto-slide when user interacts
        function manualMoveSlide(direction) {
            clearInterval(autoSlideInterval); // Stop auto-slide
            moveSlide(direction); // Move in desired direction
            autoMoveSlide(); // Restart auto-slide after interaction
        }

        // Start auto-slide on page load
        window.onload = autoMoveSlide;


        // Function to fetch products based on the search query
        function searchProducts() {
            const query = document.getElementById('search-query').value;

            if (query.length > 0) {
                // If there is a query, send it to the server via AJAX
                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'search_products.php?query=' + encodeURIComponent(query), true);

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Update the product container with the search results
                        document.querySelector('.product-container').innerHTML = xhr.responseText;
                    }
                };

                xhr.send();
            } else {
                // If no query, reset the product list to show all products
                loadAllProducts();
            }
        }

        // Optionally, you can load all products initially when the page is loaded
        function loadAllProducts() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'load_all_products.php', true);

            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Update the product container with all products
                    document.querySelector('.product-container').innerHTML = xhr.responseText;
                }
            };

            xhr.send();
        }

        // Call this function on page load to load all products initially
        document.addEventListener('DOMContentLoaded', loadAllProducts);
