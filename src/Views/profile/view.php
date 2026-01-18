<?php $pageTitle = htmlspecialchars($user['full_name']) . ' - Profile'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <?php echo getSessionMessage(); ?>
    
    <!-- Profile Header -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
        <div class="h-32 bg-gradient-to-r from-primary to-secondary"></div>
        
        <div class="px-8 pb-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between -mt-16">
                <div class="flex flex-col md:flex-row items-center md:items-end space-y-4 md:space-y-0 md:space-x-6">
                    <!-- Profile Picture -->
                    <div class="w-32 h-32 bg-white rounded-full border-4 border-white shadow-xl flex items-center justify-center">
                        <div class="w-full h-full bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-4xl font-bold text-white">
                            <?php echo strtoupper(substr($user['username'], 0, 2)); ?>
                        </div>
                    </div>
                    
                    <!-- User Info -->
                    <div class="text-center md:text-left mb-4 md:mb-0">
                        <h1 class="text-3xl font-bold text-gray-800"><?php echo htmlspecialchars($user['full_name']); ?></h1>
                        <p class="text-xl text-gray-600">@<?php echo htmlspecialchars($user['username']); ?></p>
                        <?php if (!empty($user['country'])): ?>
                            <p class="text-gray-600 mt-1">
                                <i class="fas fa-map-marker-alt text-primary"></i> 
                                <?php echo htmlspecialchars($user['country']); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex space-x-3 mt-4 md:mt-0">
                    <?php if ($isOwnProfile): ?>
                        <a href="<?php echo url('profile'); ?>" 
                           class="bg-primary hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg transition">
                            <i class="fas fa-edit"></i> Edit Profile
                        </a>
                    <?php else: ?>
                        <?php if ($isFollowing): ?>
                            <a href="<?php echo url('unfollow-user', ['id' => $user['id']]); ?>" 
                               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition"
                               onclick="return confirm('Unfollow this user?')">
                                <i class="fas fa-user-minus"></i> Unfollow
                            </a>
                        <?php else: ?>
                            <a href="<?php echo url('follow-user', ['id' => $user['id']]); ?>" 
                               class="bg-primary hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg transition">
                                <i class="fas fa-user-plus"></i> Follow
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Bio -->
            <?php if (!empty($user['bio'])): ?>
                <div class="mt-6">
                    <p class="text-gray-700 text-lg"><?php echo nl2br(htmlspecialchars($user['bio'])); ?></p>
                </div>
            <?php endif; ?>
            
            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-3xl font-bold text-primary"><?php echo $stats['hobby_count'] ?? 0; ?></p>
                    <p class="text-gray-600 font-medium">Hobbies</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-3xl font-bold text-primary"><?php echo $stats['followers'] ?? 0; ?></p>
                    <p class="text-gray-600 font-medium">Followers</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-3xl font-bold text-primary"><?php echo $stats['following'] ?? 0; ?></p>
                    <p class="text-gray-600 font-medium">Following</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-3xl font-bold text-primary"><?php echo $stats['profile_views'] ?? 0; ?></p>
                    <p class="text-gray-600 font-medium">Profile Views</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Content Tabs -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Hobbies Section -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-heart text-red-500"></i> Hobbies (<?php echo count($hobbies); ?>)
                </h2>
                
                <?php if (empty($hobbies)): ?>
                    <p class="text-gray-600">No hobbies added yet.</p>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($hobbies as $hobby): ?>
                            <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-primary transition">
                                <div class="flex items-center space-x-3 mb-2">
                                    <span class="text-3xl"><?php echo $hobby['icon']; ?></span>
                                    <div>
                                        <h3 class="font-bold text-gray-800"><?php echo htmlspecialchars($hobby['name']); ?></h3>
                                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($hobby['category']); ?></p>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full">
                                        <?php echo $hobby['proficiency_level']; ?>
                                    </span>
                                    <span class="text-gray-600">
                                        <?php echo $hobby['years_of_experience']; ?> years
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Recent Activity -->
            <?php if (!empty($activities)): ?>
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-clock text-blue-500"></i> Recent Activity
                </h2>
                
                <div class="space-y-4">
                    <?php foreach (array_slice($activities, 0, 5) as $activity): ?>
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white flex-shrink-0">
                                <i class="fas fa-<?php echo $activity['activity_type'] == 'added_hobby' ? 'plus' : 'user'; ?>"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-gray-800">
                                    <?php
                                    switch ($activity['activity_type']) {
                                        case 'added_hobby':
                                            echo 'Added a new hobby';
                                            break;
                                        case 'updated_profile':
                                            echo 'Updated profile';
                                            break;
                                        case 'connected':
                                            echo 'Made a new connection';
                                            break;
                                        default:
                                            echo 'Activity update';
                                    }
                                    ?>
                                </p>
                                <p class="text-sm text-gray-500">
                                    <?php echo date('M j, Y g:i A', strtotime($activity['created_at'])); ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            
            <!-- Followers -->
            <?php if (!empty($followers)): ?>
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-users text-blue-500"></i> Followers (<?php echo count($followers); ?>)
                </h3>
                <div class="space-y-3">
                    <?php foreach (array_slice($followers, 0, 5) as $follower): ?>
                        <a href="<?php echo url('view-profile', ['id' => $follower['id']]); ?>" 
                           class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg transition">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white font-bold">
                                <?php echo strtoupper(substr($follower['username'], 0, 2)); ?>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($follower['full_name']); ?></p>
                                <p class="text-sm text-gray-600">@<?php echo htmlspecialchars($follower['username']); ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Following -->
            <?php if (!empty($following)): ?>
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-user-friends text-green-500"></i> Following (<?php echo count($following); ?>)
                </h3>
                <div class="space-y-3">
                    <?php foreach (array_slice($following, 0, 5) as $followedUser): ?>
                        <a href="<?php echo url('view-profile', ['id' => $followedUser['id']]); ?>" 
                           class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg transition">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white font-bold">
                                <?php echo strtoupper(substr($followedUser['username'], 0, 2)); ?>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($followedUser['full_name']); ?></p>
                                <p class="text-sm text-gray-600">@<?php echo htmlspecialchars($followedUser['username']); ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Member Since -->
            <div class="bg-gradient-to-br from-primary to-secondary rounded-xl shadow-md p-6 text-white">
                <h3 class="text-xl font-bold mb-2">
                    <i class="fas fa-calendar-alt"></i> Member Since
                </h3>
                <p class="text-2xl font-bold">
                    <?php echo date('F Y', strtotime($stats['created_at'])); ?>
                </p>
                <p class="text-sm opacity-90 mt-2">
                    <?php 
                    $memberDays = floor((time() - strtotime($stats['created_at'])) / 86400);
                    echo $memberDays . ' days on Hobbies Hub';
                    ?>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
