
const handleLogin = () => {
    navigate('login.php');
};

function scrollToSection(sectionId) {
    const target = document.getElementById(sectionId);
    if (target) {
      target.scrollIntoView({ behavior: 'smooth' });
    }
  }

// Ensure the modal is hidden on page load
window.onload = function() {
  document.getElementById('successModal').style.display = 'none';
};

// Function to handle the form submission via AJAX
document.getElementById('contact-form').addEventListener('submit', function(event) {
  event.preventDefault(); // Prevent the form from submitting the normal way

  // Create FormData object from the form
  var formData = new FormData(this);

  // Send AJAX request
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'send_email.php', true);
  xhr.onreadystatechange = function() {
      if (xhr.readyState === 4 && xhr.status === 200) {
          // Check if the response is success or failure
          if (xhr.responseText === 'success') {
              // Show success modal
              document.getElementById('successModal').style.display = 'flex';
          } else {
              alert('There was an error sending your message.');
          }
      }
  };
  xhr.send(formData); // Send the form data to the server
});

// Close the modal when the close button is clicked
document.getElementById('closeModal')?.addEventListener('click', function() {
  document.getElementById('successModal').style.display = 'none';
});
