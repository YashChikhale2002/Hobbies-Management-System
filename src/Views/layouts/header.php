<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Hobbies Management System'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#6366f1',
                        secondary: '#8b5cf6',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    
    <?php if (isLoggedIn()): ?>
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="<?php echo url('dashboard'); ?>" class="text-2xl font-bold text-primary">
                        <i class="fas fa-heart"></i> Hobbies Hub
                    </a>
                </div>
                
                <div class="flex items-center space-x-1">
                    <a href="<?php echo url('dashboard'); ?>" 
                       class="text-gray-700 hover:text-primary hover:bg-gray-100 px-3 py-2 rounded-md text-sm font-medium transition">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <a href="<?php echo url('hobbies'); ?>" 
                       class="text-gray-700 hover:text-primary hover:bg-gray-100 px-3 py-2 rounded-md text-sm font-medium transition">
                        <i class="fas fa-star"></i> My Hobbies
                    </a>
                    <a href="<?php echo url('activity-feed'); ?>" 
                       class="text-gray-700 hover:text-primary hover:bg-gray-100 px-3 py-2 rounded-md text-sm font-medium transition">
                        <i class="fas fa-stream"></i> Feed
                    </a>
                    <a href="<?php echo url('find-users'); ?>" 
                       class="text-gray-700 hover:text-primary hover:bg-gray-100 px-3 py-2 rounded-md text-sm font-medium transition">
                        <i class="fas fa-users"></i> Find Users
                    </a>
                    <a href="<?php echo url('search-users'); ?>" 
                       class="text-gray-700 hover:text-primary hover:bg-gray-100 px-3 py-2 rounded-md text-sm font-medium transition">
                        <i class="fas fa-search"></i> Search
                    </a>
                    
                    <!-- Profile Dropdown -->
                    <div class="relative ml-3" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center space-x-2 text-gray-700 hover:text-primary hover:bg-gray-100 px-3 py-2 rounded-md text-sm font-medium transition">
                            <i class="fas fa-user-circle text-lg"></i>
                            <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50"
                             style="display: none;">
                            <a href="<?php echo url('view-profile', ['id' => $_SESSION['user_id']]); ?>" 
                               class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition">
                                <i class="fas fa-user"></i> View Profile
                            </a>
                            <a href="<?php echo url('profile'); ?>" 
                               class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition">
                                <i class="fas fa-edit"></i> Edit Profile
                            </a>
                            <hr class="my-2">
                            <a href="<?php echo url('logout'); ?>" 
                               class="block px-4 py-2 text-red-600 hover:bg-red-50 transition">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Alpine.js for dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <?php endif; ?>
    
    <main class="min-h-screen">
