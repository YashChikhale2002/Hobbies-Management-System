<?php $pageTitle = 'Find Users - Hobbies Management'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">
            <i class="fas fa-users text-primary"></i> Find Similar Users
        </h1>
        <p class="text-gray-600">Connect with people who share your hobbies</p>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">

        <?php if (empty($similarUsers)): ?>
        <div class="text-center py-12 text-gray-500">
            <i class="fas fa-user-friends text-6xl mb-4 text-gray-300"></i>
            <p class="text-xl font-semibold mb-2">No users with similar hobbies found yet!</p>
            <p class="mt-2 text-gray-600">Add more hobbies to find like-minded people.</p>
            <a href="<?php echo url('hobbies'); ?>"
                class="inline-block mt-6 bg-primary hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg transition transform hover:scale-105">
                <i class="fas fa-plus-circle"></i> Add Hobbies
            </a>
        </div>
        <?php else: ?>
        <div class="mb-6">
            <p class="text-gray-600">
                <i class="fas fa-info-circle text-primary"></i>
                Found <strong><?php echo count($similarUsers); ?></strong> user(s) with similar interests
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($similarUsers as $user): ?>
            <div
                class="border-2 border-gray-200 rounded-xl p-6 hover:border-primary hover:shadow-xl transition duration-300">
                <div class="flex flex-col items-center text-center">
                    <!-- User Avatar -->
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-2xl font-bold text-white mb-4 shadow-lg">
                        <?php echo strtoupper(substr($user['username'], 0, 2)); ?>
                    </div>

                    <!-- User Info -->
                    <h3 class="text-xl font-bold text-gray-800 mb-1">
                        <?php echo htmlspecialchars($user['full_name']); ?>
                    </h3>
                    <p class="text-gray-600 mb-2">@<?php echo htmlspecialchars($user['username']); ?></p>

                    <?php if (!empty($user['country'])): ?>
                    <p class="text-sm text-gray-500 mb-3">
                        <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($user['country']); ?>
                    </p>
                    <?php endif; ?>

                    <!-- Common Hobbies Badge -->
                    <div class="mb-4">
                        <span
                            class="inline-flex items-center px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-bold shadow-sm">
                            <i class="fas fa-heart mr-2"></i>
                            <?php echo $user['common_hobbies']; ?>
                            <?php echo $user['common_hobbies'] == 1 ? 'hobby' : 'hobbies'; ?> in common
                        </span>
                    </div>

                    <!-- View Profile Button -->
                    <!-- Replace the View Profile button with: -->
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

    <!-- Additional Info Card -->
    <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2">
            <i class="fas fa-lightbulb text-yellow-500"></i> Tip: Expand Your Network
        </h3>
        <p class="text-gray-700">
            The more hobbies you add, the more people you can connect with!
            Add diverse interests to discover users from different communities.
        </p>
    </div>
</div>

<script>
function viewUserProfile(userId) {
    // You can implement a modal or redirect to user profile page
    alert('Profile viewing feature coming soon! User ID: ' + userId);
    // Future: window.location.href = '<?php echo url('view-user'); ?>&id=' + userId;
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>