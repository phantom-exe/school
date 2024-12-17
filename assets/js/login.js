document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const errorMessage = document.getElementById('error-message');

    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Clear previous error messages
            errorMessage.textContent = '';
            
            try {
                const formData = new FormData(this);
                
                // Debug log
                console.log('Sending login request with data:', {
                    username: formData.get('username'),
                    userType: formData.get('userType')
                });

                const response = await fetch('login.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();
                console.log('Server response:', data);

                if (data.success) {
                    errorMessage.textContent = 'Login successful! Redirecting...';
                    errorMessage.style.color = 'green';
                    
                    // Debug log
                    console.log('Redirecting to:', data.redirect);
                    
                    // Redirect after a short delay
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 500);
                } else {
                    errorMessage.textContent = data.message;
                    errorMessage.style.color = 'red';
                }
            } catch (error) {
                console.error('Login error:', error);
                errorMessage.textContent = 'An error occurred during login';
                errorMessage.style.color = 'red';
            }
        });
    }
}); 