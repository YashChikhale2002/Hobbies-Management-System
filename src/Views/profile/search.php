<?php $pageTitle = 'Search Users - Hobbies Management'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">
            <i class="fas fa-search text-primary"></i> Search Users
        </h1>
        
        <!-- Search Form -->
        <form method="GET" action="<?php echo url('search-users'); ?>" class="max-w-2xl">
            <div class="flex space-x-3">
                <input type="text" 
                       name="q" 
                       value="<?php echo htmlspecialchars($searchTerm); ?>"
                       placeholder="Search by username or name..." 
                       class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                       required>
                <button type="submit" 
                        class="bg-primary hover:bg-indigo-700 text-white font-bold px-8 py-3 rounded-lg transition">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
        </form>
    </div>
    
    <?php if (!empty($searchTerm)): ?>
        <div class="bg-white rounded-xl shadow-md p-6">
            <?php if (empty($results)): ?>
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-search text-6xl mb-4 text-gray-300"></i>
                    <p class="text-xl font-semibold mb-2">No users found</p>
                    <p class="text-gray-600">Try searching with different keywords</p>
                </div>
            <?php else: ?>
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    Found <?php echo count($results); ?> user(s)
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($results as $user): ?>
                        <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-primary hover:shadow-lg transition">
                            <div class="flex flex-col items-center text-center">
                                <div class="w-20 h-20 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-2xl font-bold text-white mb-4">
                                    <?php echo strtoupper(substr($user['username'], 0, 2)); ?>
                                </div>
                                
                                <h3 class="text-xl font-bold text-gray-800 mb-1">
                                    <?php echo htmlspecialchars($user['full_name']); ?>
                                </h3>
                                <p class="text-gray-600 mb-2">@<?php echo htmlspecialchars($user['username']); ?></p>
                                
                                <?php if (!empty($user['country'])): ?>
                                    <p class="text-sm text-gray-500 mb-3">
                                        <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($user['country']); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <?php if (!empty($user['bio'])): ?>
                                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                        <?php echo htmlspecialchars(substr($user['bio'], 0, 100)); ?>
                                        <?php echo strlen($user['bio']) > 100 ? '...' : ''; ?>
                                    </p>
                                <?php endif; ?>
                                
                                <a href="<?php echo url('view-profile', ['id' => $user['id']]); ?>" 
                                   class="w-full bg-primary hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition">
                                    <i class="fas fa-user-circle"></i> View Profile
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
