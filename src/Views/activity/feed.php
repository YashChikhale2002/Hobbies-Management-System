<?php $pageTitle = 'Activity Feed - Hobbies Management'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">
            <i class="fas fa-stream text-primary"></i> Activity Feed
        </h1>
        <p class="text-gray-600">See what people you follow are up to</p>
    </div>
    
    <div class="bg-white rounded-xl shadow-md">
        <?php if (empty($activities)): ?>
            <div class="text-center py-12 px-6 text-gray-500">
                <i class="fas fa-rss text-6xl mb-4 text-gray-300"></i>
                <p class="text-xl font-semibold mb-2">No activities yet</p>
                <p class="text-gray-600 mb-4">Follow users to see their activities here</p>
                <a href="<?php echo url('find-users'); ?>" 
                   class="inline-block bg-primary hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg transition">
                    <i class="fas fa-users"></i> Find Users
                </a>
            </div>
        <?php else: ?>
            <div class="divide-y divide-gray-200">
                <?php foreach ($activities as $activity): ?>
                    <div class="p-6 hover:bg-gray-50 transition">
                        <div class="flex items-start space-x-4">
                            <a href="<?php echo url('view-profile', ['id' => $activity['user_id']]); ?>">
                                <div class="w-12 h-12 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                                    <?php echo strtoupper(substr($activity['username'], 0, 2)); ?>
                                </div>
                            </a>
                            
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-1">
                                    <a href="<?php echo url('view-profile', ['id' => $activity['user_id']]); ?>" 
                                       class="font-bold text-gray-800 hover:text-primary">
                                        <?php echo htmlspecialchars($activity['full_name']); ?>
                                    </a>
                                    <span class="text-gray-600">
                                        <?php
                                        switch ($activity['activity_type']) {
                                            case 'added_hobby':
                                                echo 'added a new hobby';
                                                break;
                                            case 'updated_profile':
                                                echo 'updated their profile';
                                                break;
                                            case 'connected':
                                                echo 'made a new connection';
                                                break;
                                            case 'joined':
                                                echo 'joined Hobbies Hub';
                                                break;
                                            default:
                                                echo 'had an update';
                                        }
                                        ?>
                                    </span>
                                </div>
                                
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-clock"></i> 
                                    <?php 
                                    $time = strtotime($activity['created_at']);
                                    $diff = time() - $time;
                                    if ($diff < 60) echo 'Just now';
                                    elseif ($diff < 3600) echo floor($diff / 60) . ' minutes ago';
                                    elseif ($diff < 86400) echo floor($diff / 3600) . ' hours ago';
                                    else echo date('M j, Y', $time);
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
