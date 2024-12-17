// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', () => {
    // Initialize all components
    initializeNavigation();
    initializeLoginForm();
    initializeSmoothScroll();
});

// Navigation functionality
function initializeNavigation() {
    const burger = document.querySelector('.burger');
    const nav = document.querySelector('.nav-links');
    const navLinks = document.querySelectorAll('.nav-links li');

    if (burger) {
        burger.addEventListener('click', () => {
            // Toggle Navigation
            nav.classList.toggle('nav-active');

            // Animate Links
            navLinks.forEach((link, index) => {
                if (link.style.animation) {
                    link.style.animation = '';
                } else {
                    link.style.animation = `navLinkFade 0.5s ease forwards ${index / 7 + 0.3}s`;
                }
            });

            // Burger Animation
            burger.classList.toggle('toggle');
        });
    }
}

// Login form handling
function initializeLoginForm() {
    const loginForm = document.getElementById('loginForm');
    const errorElement = document.getElementById('error-message');

    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            try {
                // Show loading state
                const submitButton = loginForm.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.textContent;
                submitButton.disabled = true;
                submitButton.textContent = 'Logging in...';
                
                // Get form data
                const formData = new FormData(loginForm);
                
                // Debug log
                console.log('Sending login request...');
                
                // Send login request
                const response = await fetch('login.php', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Server response:', data); // Debug log
                
                if (data.success) {
                    showMessage(errorElement, 'Login successful, redirecting...', 'success');
                    
                    // Use the redirect URL from server response
                    setTimeout(() => {
                        console.log('Redirecting to:', data.redirect); // Debug log
                        window.location.href = data.redirect;
                    }, 500);
                } else {
                    showMessage(errorElement, data.message || 'Login failed', 'error');
                }
            } catch (error) {
                console.error('Login error:', error);
                showMessage(errorElement, 'Network or server error occurred', 'error');
            } finally {
                // Reset button state
                submitButton.disabled = false;
                submitButton.textContent = originalButtonText;
            }
        });
    }
}

// Smooth scrolling functionality
function initializeSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Utility Functions
function showMessage(element, message, type = 'error') {
    if (element) {
        element.textContent = message;
        element.className = `message ${type}`;
        element.style.color = type === 'error' ? 'red' : 'green';
    }
}

function logFormData(formData) {
    console.log('Form data being sent:');
    for (let pair of formData.entries()) {
        console.log(`${pair[0]}: ${pair[1]}`);
    }
}

// Add CSS for messages
const style = document.createElement('style');
style.textContent = `
    .message {
        padding: 10px;
        margin: 10px 0;
        border-radius: 4px;
        text-align: center;
    }
    .message.error {
        background-color: #fee;
        color: #e33;
    }
    .message.success {
        background-color: #efe;
        color: #3c3;
    }
`;
document.head.appendChild(style);