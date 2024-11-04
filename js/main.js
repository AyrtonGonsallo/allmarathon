


document.addEventListener("DOMContentLoaded", function() {
    const hamburgerButton = document.getElementById("hamburger-menu");
    const navCategory = document.querySelector(".nav-category");
    const header = document.querySelector(".header"); // Select the header element

    hamburgerButton.addEventListener("click", function() {
        // Toggle the menu open class
        navCategory.classList.toggle("open");

        // Toggle the fixed-header class
        if (navCategory.classList.contains("open")) {
            header.classList.add("fixed-header");
            document.body.classList.add("menu-open"); // Prevent scrolling
        } else {
            header.classList.remove("fixed-header");
            document.body.classList.remove("menu-open"); // Allow scrolling
        }
    });

    // Close the menu when clicking outside of it
    document.addEventListener("click", function(event) {
        if (!hamburgerButton.contains(event.target) && !navCategory.contains(event.target)) {
            navCategory.classList.remove("open");
            header.classList.remove("fixed-header");
            document.body.classList.remove("menu-open");
        }
    });
    console.log("fixed header chargé")
});

// ok recherche btn
document.addEventListener('DOMContentLoaded', function() {
    const searchButton = document.getElementById('mobile-search-button');
    searchButton.value = 'Ok';
});
// supp le placeholder recherche
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('header-search');
    searchInput.removeAttribute('placeholder');
});



//delete flag not found
document.querySelectorAll('.marathon-title-flag').forEach(img => {
    const src = img.getAttribute('src');
    if (!src || !src.endsWith('.jpg')) {
        img.style.display = 'none';
    }
});

//bg transparent ads
document.addEventListener("DOMContentLoaded", function() {
    var banElements = document.querySelectorAll('.ban');

    banElements.forEach(function(banElement) {
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' || mutation.type === 'attributes') {
                    var iframe = banElement.querySelector('iframe');
                    // Check if the iframe has a valid src and non-zero height
                    if (iframe && iframe.src && iframe.style.height !== '0px' && iframe.style.height !== '') {
                        banElement.classList.add('transparent-bg');
                        observer.disconnect(); // Stop observing once the class is added
                    }
                }
            });
        });

        // Observe the .ban element for changes
        observer.observe(banElement, { attributes: true, childList: true, subtree: true });

        // Fallback to remove transparent background if the ad doesn't load
        setTimeout(function() {
            var iframe = banElement.querySelector('iframe');
            if (!iframe || iframe.style.height === '0px' || iframe.style.height === '') {
                banElement.classList.remove('transparent-bg');
            }
        }, 5000); // Adjust timeout as needed for your specific case
    });
});








    









