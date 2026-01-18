<?php $pageTitle = 'My Profile - Hobbies Management'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <?php echo getSessionMessage(); ?>
    
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        
        <!-- Profile Header -->
        <div class="bg-gradient-to-r from-primary to-secondary p-8 text-white">
            <div class="flex items-center space-x-6">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-4xl font-bold text-primary">
                    <?php echo strtoupper(substr($user['username'], 0, 2)); ?>
                </div>
                <div>
                    <h1 class="text-3xl font-bold"><?php echo htmlspecialchars($user['full_name']); ?></h1>
                    <p class="text-xl opacity-90">@<?php echo htmlspecialchars($user['username']); ?></p>
                    <p class="mt-1"><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($user['email']); ?></p>
                </div>
            </div>
        </div>
        
        <!-- Profile Content -->
        <div class="p-8">
            
            <!-- Edit Profile Form -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-edit text-primary"></i> Edit Profile
                </h2>
                
                <form method="POST" action="index.php?page=profile&action=update" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                        <textarea name="bio" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                                  placeholder="Tell us about yourself..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                        <input type="text" name="country" value="<?php echo htmlspecialchars($user['country']); ?>" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                    </div>
                    
                    <button type="submit" 
                            class="bg-primary hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition">
                        <i class="fas fa-save"></i> Update Profile
                    </button>
                </form>
            </div>
            
            <!-- My Hobbies Display -->
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-heart text-red-500"></i> My Hobbies (<?php echo count($hobbies); ?>)
                </h2>
                
                <?php if (empty($hobbies)): ?>
                    <p class="text-gray-600">No hobbies added yet.</p>
                <?php else: ?>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($hobbies as $hobby): ?>
                            <span class="px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-full text-sm font-medium">
                                <?php echo $hobby['icon'] . ' ' . htmlspecialchars($hobby['name']); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
