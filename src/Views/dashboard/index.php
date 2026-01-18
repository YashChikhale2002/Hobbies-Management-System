<?php $pageTitle = 'Dashboard - Hobbies Management'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <?php echo getSessionMessage(); ?>
    
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-primary to-secondary rounded-2xl shadow-lg p-8 mb-8 text-white">
        <h1 class="text-4xl font-bold mb-2">Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>! 🎉</h1>
        <p class="text-xl opacity-90">Manage your hobbies and connect with like-minded people</p>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">My Hobbies</p>
                    <p class="text-3xl font-bold text-gray-800"><?php echo count($hobbies); ?></p>
                </div>
                <div class="bg-blue-100 p-4 rounded-full">
                    <i class="fas fa-star text-blue-500 text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Hobbies</p>
                    <p class="text-3xl font-bold text-gray-800"><?php echo count($hobbyStats); ?></p>
                </div>
                <div class="bg-green-100 p-4 rounded-full">
                    <i class="fas fa-list text-green-500 text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Member Since</p>
                    <p class="text-lg font-bold text-gray-800"><?php echo date('M Y', strtotime($user['created_at'])); ?></p>
                </div>
                <div class="bg-purple-100 p-4 rounded-full">
                    <i class="fas fa-calendar text-purple-500 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- My Hobbies & Popular Hobbies -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- My Hobbies -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-heart text-red-500"></i> My Hobbies
            </h2>
            
            <?php if (empty($hobbies)): ?>
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-inbox text-5xl mb-4"></i>
                    <p>No hobbies added yet!</p>
                    <a href="index.php?page=hobbies" class="text-primary hover:underline">Add your first hobby</a>
                </div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach (array_slice($hobbies, 0, 5) as $hobby): ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center space-x-3">
                                <span class="text-2xl"><?php echo $hobby['icon']; ?></span>
                                <div>
                                    <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($hobby['name']); ?></p>
                                    <p class="text-sm text-gray-600"><?php echo $hobby['proficiency_level']; ?></p>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-primary text-white text-xs rounded-full">
                                <?php echo $hobby['years_of_experience']; ?> yrs
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if (count($hobbies) > 5): ?>
                    <a href="index.php?page=hobbies" class="block text-center mt-4 text-primary hover:text-indigo-700 font-medium">
                        View all hobbies →
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        
        <!-- Popular Hobbies -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-fire text-orange-500"></i> Popular Hobbies
            </h2>
            
            <div class="space-y-3">
                <?php foreach (array_slice($hobbyStats, 0, 6) as $stat): ?>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <span class="text-2xl"><?php echo $stat['icon']; ?></span>
                            <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($stat['name']); ?></p>
                        </div>
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-sm rounded-full font-medium">
                            <?php echo $stat['user_count']; ?> users
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
