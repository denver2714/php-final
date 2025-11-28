<?php
session_start();
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'All fields are required.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {

        $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            $error = 'Email already registered.';
        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
            if ($stmt->execute([':email' => $email, ':password' => $hashed_password])) {
                header('Location: login.php');
                exit;
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Task App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5', 
                        'primary-hover': '#4338ca', 
                        accent: '#0ea5e9', 
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 50%, #e0e7ff 100%);
            background-attachment: fixed;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body class="text-slate-800 min-h-screen flex flex-col overflow-x-hidden">
    <div class="container mx-auto px-6 py-12 flex-1 max-w-5xl animate-[fadeInUp_0.8s_ease-out]">
        <div class="flex justify-between items-center mb-12 animate-[fadeInDown_0.8s_ease]">
            <a href="#" class="text-3xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-primary to-accent hover:scale-105 hover:-rotate-2 transition-transform duration-300">TD</a>
        </div>

        <div class="glass-card rounded-3xl shadow-xl p-12 mb-8 max-w-md mx-auto transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-indigo-500/20">
            <h2 class="text-3xl font-bold mb-8 text-center">Create Account</h2>
            <?php if ($error): ?>
                <div class="text-rose-500 mb-4 text-center font-medium"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST">
                <label class="block font-medium mb-2 ml-1">Email</label>
                <input type="email" name="email" required class="w-full p-4 border-2 border-transparent rounded-2xl bg-white/80 text-lg mb-6 transition-all duration-300 shadow-inner focus:outline-none focus:border-primary focus:bg-white focus:shadow-[0_0_0_4px_rgba(99,102,241,0.15)] focus:scale-[1.01]">
                
                <label class="block font-medium mb-2 ml-1">Password</label>
                <input type="password" name="password" required class="w-full p-4 border-2 border-transparent rounded-2xl bg-white/80 text-lg mb-6 transition-all duration-300 shadow-inner focus:outline-none focus:border-primary focus:bg-white focus:shadow-[0_0_0_4px_rgba(99,102,241,0.15)] focus:scale-[1.01]">
                
                <label class="block font-medium mb-2 ml-1">Confirm Password</label>
                <input type="password" name="confirm_password" required class="w-full p-4 border-2 border-transparent rounded-2xl bg-white/80 text-lg mb-8 transition-all duration-300 shadow-inner focus:outline-none focus:border-primary focus:bg-white focus:shadow-[0_0_0_4px_rgba(99,102,241,0.15)] focus:scale-[1.01]">
                
                <button type="submit" class="w-full py-4 bg-gradient-to-br from-primary to-accent text-white rounded-2xl shadow-md hover:shadow-lg hover:-translate-y-1 transition-all duration-300 font-semibold text-lg">Register</button>
            </form>
            <div class="text-center mt-8 text-slate-500">
                Already have an account? <a href="login.php" class="text-primary font-semibold relative after:content-[''] after:absolute after:w-full after:h-0.5 after:bottom-0 after:left-0 after:bg-primary after:scale-x-0 hover:after:scale-x-100 after:transition-transform after:duration-300">Login here</a>
            </div>
        </div>
    </div>
</body>
</html>
