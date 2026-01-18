<?php $pageTitle = 'Register - Hobbies Management'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-secondary to-primary py-12">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Create Account</h2>
                <p class="text-gray-600 mt-2">Join our hobbies community</p>
            </div>
            
            <?php echo getSessionMessage(); ?>
            
            <form method="POST" action="<?php echo url('register', ['action' => 'submit']); ?>" class="space-y-4" id="registerForm">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Username *</label>
                    <input type="text" name="username" required minlength="3" maxlength="50"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Choose a unique username">
                    <p class="text-xs text-gray-500 mt-1">Minimum 3 characters</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" name="full_name" required minlength="2" maxlength="100"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Your full name">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" required maxlength="100"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="your@email.com">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                    <input type="text" name="country" required maxlength="50"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Your country">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                    <input type="password" name="password" id="password" required minlength="6"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Minimum 6 characters">
                    <p class="text-xs text-gray-500 mt-1">Minimum 6 characters</p>
                </div>
                
                <button type="submit" 
                        class="w-full bg-secondary hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105">
                    <i class="fas fa-user-plus"></i> Register
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-gray-600">Already have an account? 
                    <a href="<?php echo url('login'); ?>" class="text-primary hover:text-indigo-700 font-semibold">
                        Login here
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// Client-side validation
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const username = document.querySelector('input[name="username"]').value;
    const password = document.getElementById('password').value;
    
    if (username.length < 3) {
        alert('Username must be at least 3 characters long');
        e.preventDefault();
        return false;
    }
    
    if (password.length < 6) {
        alert('Password must be at least 6 characters long');
        e.preventDefault();
        return false;
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
