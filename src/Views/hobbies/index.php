<?php $pageTitle = 'My Hobbies - Hobbies Management'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <?php echo getSessionMessage(); ?>
    
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">My Hobbies</h1>
        <p class="text-gray-600">Manage your interests and passions</p>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Add New Hobby Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md p-6 sticky top-4">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-plus-circle text-primary"></i> Add Hobby
                </h2>
                
                <form method="POST" action="<?php echo url('hobbies', ['action' => 'add']); ?>" class="space-y-4" id="addHobbyForm">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Hobby *</label>
                        <select name="hobby_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                            <option value="">Choose a hobby...</option>
                            <?php 
                            // Get IDs of hobbies user already has
                            $userHobbyIds = array_column($userHobbies, 'hobby_id');
                            
                            foreach ($allHobbies as $hobby): 
                                // Check if user already has this hobby
                                $isAdded = in_array($hobby['id'], $userHobbyIds);
                            ?>
                                <option value="<?php echo $hobby['id']; ?>" <?php echo $isAdded ? 'disabled' : ''; ?>>
                                    <?php echo $hobby['icon'] . ' ' . htmlspecialchars($hobby['name']); ?>
                                    <?php echo $isAdded ? ' (Already added)' : ''; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Proficiency Level *</label>
                        <select name="proficiency_level" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                            <option value="Beginner">🌱 Beginner</option>
                            <option value="Intermediate">📈 Intermediate</option>
                            <option value="Advanced">🚀 Advanced</option>
                            <option value="Expert">⭐ Expert</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Years of Experience *</label>
                        <input type="number" name="years_of_experience" min="0" max="50" value="0" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                        <p class="text-xs text-gray-500 mt-1">Enter 0 if you're just starting</p>
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-primary hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition">
                        <i class="fas fa-plus"></i> Add Hobby
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Current Hobbies List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-list text-green-500"></i> Your Hobbies (<?php echo count($userHobbies); ?>)
                </h2>
                
                <?php if (empty($userHobbies)): ?>
                    <div class="text-center py-12 text-gray-500">
                        <i class="fas fa-sad-tear text-6xl mb-4"></i>
                        <p class="text-xl">No hobbies added yet!</p>
                        <p class="mt-2">Start by adding your first hobby from the form.</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($userHobbies as $hobby): ?>
                            <div class="border-2 border-gray-200 rounded-xl p-4 hover:border-primary transition">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-4xl"><?php echo $hobby['icon']; ?></span>
                                        <div>
                                            <h3 class="font-bold text-lg text-gray-800"><?php echo htmlspecialchars($hobby['name']); ?></h3>
                                            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($hobby['category']); ?></p>
                                        </div>
                                    </div>
                                    <a href="<?php echo url('hobbies', ['action' => 'remove', 'id' => $hobby['hobby_id']]); ?>" 
                                       onclick="return confirm('Are you sure you want to remove this hobby?')"
                                       class="text-red-500 hover:text-red-700 transition">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                                
                                <div class="flex items-center justify-between text-sm mb-2">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full font-medium">
                                        <?php echo $hobby['proficiency_level']; ?>
                                    </span>
                                    <span class="text-gray-600">
                                        <i class="fas fa-clock"></i> <?php echo $hobby['years_of_experience']; ?> years
                                    </span>
                                </div>
                                
                                <p class="mt-3 text-sm text-gray-600"><?php echo htmlspecialchars($hobby['description']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
document.getElementById('addHobbyForm').addEventListener('submit', function(e) {
    const hobbyId = document.querySelector('select[name="hobby_id"]').value;
    const experience = document.querySelector('input[name="years_of_experience"]').value;
    
    if (!hobbyId || hobbyId === '') {
        alert('Please select a hobby');
        e.preventDefault();
        return false;
    }
    
    if (experience < 0 || experience > 50) {
        alert('Years of experience must be between 0 and 50');
        e.preventDefault();
        return false;
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
