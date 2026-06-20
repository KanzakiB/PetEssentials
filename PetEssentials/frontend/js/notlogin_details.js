function updateMainImage(thumbnail) {
    const mainImage = document.getElementById('main-image');
    mainImage.src = thumbnail.src;

    const thumbnails = document.querySelectorAll('.thumbnail');
    thumbnails.forEach(thumb => thumb.classList.remove('active'));
    thumbnail.classList.add('active');
}

function scrollThumbnailsLeft() {
    const container = document.querySelector('.thumbnails-container');
    const scrollAmount = 500; 
    container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    updateArrows();
}

function scrollThumbnailsRight() {
    const container = document.querySelector('.thumbnails-container');
    const scrollAmount = 500; 
    container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    updateArrows();
}

function updateArrows() {
    const container = document.querySelector('.thumbnails-container');
    const leftArrow = document.querySelector('.left-arrow');
    const rightArrow = document.querySelector('.right-arrow');

    leftArrow.disabled = container.scrollLeft <= 0;
    rightArrow.disabled = container.scrollLeft + container.offsetWidth >= container.scrollWidth;
}

document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('.thumbnails-container');
    const thumbnails = document.querySelectorAll('.thumbnail');

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', () => updateMainImage(thumbnail));
    });

    container.addEventListener('scroll', updateArrows);
    updateArrows();
});


window.onload = function() {
    var productNameElement = document.getElementById("product-name");
    var productName = productNameElement.textContent || productNameElement.innerText;
    
    if (productName.length > 135) {
      productName = productName.substring(0, 135) + "...";
      productNameElement.textContent = productName; 
    }
  };
  

function changeQuantity(change) {
    const quantityElement = document.getElementById("userproduct-quantity");
    let currentQuantity = parseInt(quantityElement.textContent); 
    
    if (change === 1) {
        quantityElement.textContent = currentQuantity + 1; 
    }
    
    else if (change === -1 && currentQuantity > 0) {
        quantityElement.textContent = currentQuantity - 1; 
    }
}


document.addEventListener("DOMContentLoaded", () => {
    const flavorButtons = document.querySelectorAll(".type-flavor");

    flavorButtons.forEach(button => {
        button.addEventListener("click", () => {
            flavorButtons.forEach(btn => btn.classList.remove("active"));

            button.classList.add("active");
        });
    });
});


document.addEventListener("DOMContentLoaded", function () {
    const addToCartButton = document.getElementById("AddCartbtn");
    const buyNowButton = document.getElementById("Buybtn");
    const modal = document.getElementById("loginModal");
    const overlay = document.getElementById("overlay");
    const modalOkButton = document.getElementById("modalOkButton");

    function showModal() {
        modal.style.display = "block";
        overlay.style.display = "block";
    }

    function hideModal() {
        modal.style.display = "none";
        overlay.style.display = "none";
    }

    addToCartButton.addEventListener("click", showModal);
    buyNowButton.addEventListener("click", showModal);

    modalOkButton.addEventListener("click", hideModal);

    overlay.addEventListener("click", hideModal);
});



function searchProducts() {
    var query = document.getElementById('search-query').value;

    if (query.length > 0) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'search_products_details.php?query=' + query, true);
        xhr.onload = function() {
            if (xhr.status == 200) {
                document.getElementById('search-results').innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    } else {
        document.getElementById('search-results').innerHTML = '';
    }
}