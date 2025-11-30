<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Chào mừng - Todo List</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .feature-icon {
            background: linear-gradient(135deg, #10b981, #059669);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .blob {
            animation: blob 7s infinite;
        }

        .blob2 {
            animation: blob 7s infinite;
            animation-delay: -3s;
        }

        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .floating {
            animation: floating 6s ease-in-out infinite;
        }

        @keyframes floating {
            0% {
                transform: translate(0, 0px);
            }

            50% {
                transform: translate(0, -20px);
            }

            100% {
                transform: translate(0, -0px);
            }
        }

        .fade-in {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        .fade-in-delay-1 {
            animation-delay: 0.2s;
        }

        .fade-in-delay-2 {
            animation-delay: 0.4s;
        }

        .fade-in-delay-3 {
            animation-delay: 0.6s;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .text-gradient {
            background: linear-gradient(135deg, #10b981, #059669);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body class="bg-slate-50 overflow-x-hidden">
    <!-- Header -->
    <header class="relative z-10 bg-white/80 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <span class="material-icons text-emerald-600 text-3xl">check_circle</span>
                    <h1 class="text-2xl font-bold ml-2 text-slate-800">Todo</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="#features" class="text-slate-600 hover:text-emerald-600 font-medium transition-colors">Tính
                        năng</a>
                    <a href="{{ route('login') }}"
                        class="bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-2 rounded-lg font-medium transition-all duration-200 shadow-lg hover:shadow-emerald-100">
                        Bắt đầu ngay
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center gradient-bg overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <div
                class="absolute top-20 left-20 w-72 h-72 bg-emerald-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-pulse blob">
            </div>
            <div
                class="absolute top-40 right-20 w-72 h-72 bg-teal-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-pulse blob2">
            </div>
            <div
                class="absolute bottom-20 left-1/2 w-72 h-72 bg-green-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-pulse">
            </div>
        </div>

        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="fade-in">
                <h1 class="text-5xl md:text-7xl font-extrabold text-white mb-6 leading-tight">
                    Quản lý công việc
                    <span class="block text-transparent bg-clip-text bg-gradient-to-r from-emerald-200 to-teal-200">
                        thông minh hơn
                    </span>
                </h1>
            </div>

            <div class="fade-in fade-in-delay-1">
                <p class="text-xl md:text-2xl text-emerald-100 mb-8 max-w-3xl mx-auto leading-relaxed">
                    Tối ưu hóa năng suất của bạn với công cụ quản lý công việc cá nhân hiện đại,
                    trực quan và dễ sử dụng.
                </p>
            </div>

            <div class="fade-in fade-in-delay-2">
                <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <a href="{{ route('login') }}"
                        class="bg-white text-emerald-600 hover:bg-emerald-50 px-8 py-4 rounded-xl font-bold text-lg shadow-2xl hover:shadow-3xl transform hover:scale-105 transition-all duration-200 flex items-center">
                        <span class="material-icons mr-2">rocket_launch</span>
                        Bắt đầu miễn phí
                    </a>
                    <a href="#features"
                        class="border-2 border-white text-white hover:bg-white hover:text-emerald-600 px-8 py-4 rounded-xl font-bold text-lg transition-all duration-200 flex items-center">
                        <span class="material-icons mr-2">explore</span>
                        Khám phá tính năng
                    </a>
                </div>
            </div>

            <div class="fade-in fade-in-delay-3 mt-12">
                <div class="floating">
                    <div class="inline-block bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                        <div class="grid grid-cols-3 gap-6 text-center">
                            <div>
                                <div class="text-3xl font-bold text-white">1000+</div>
                                <div class="text-emerald-200 text-sm">Công việc đã hoàn thành</div>
                            </div>
                            <div>
                                <div class="text-3xl font-bold text-white">99%</div>
                                <div class="text-emerald-200 text-sm">Tỷ lệ hài lòng</div>
                            </div>
                            <div>
                                <div class="text-3xl font-bold text-white">24/7</div>
                                <div class="text-emerald-200 text-sm">Luôn sẵn sàng</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-slate-800 mb-4">
                    Tính năng <span class="text-gradient">nổi bật</span>
                </h2>
                <p class="text-xl text-slate-600 max-w-3xl mx-auto">
                    Được thiết kế để giúp bạn tổ chức, theo dõi và hoàn thành công việc một cách hiệu quả nhất
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="card-hover bg-white rounded-2xl p-8 border border-slate-100 shadow-lg">
                    <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mb-6">
                        <span class="material-icons text-3xl feature-icon">task_alt</span>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-4">Quản lý thông minh</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Tạo, chỉnh sửa và tổ chức công việc với giao diện trực quan.
                        Phân loại theo độ ưu tiên và thời hạn.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="card-hover bg-white rounded-2xl p-8 border border-slate-100 shadow-lg">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mb-6">
                        <span class="material-icons text-3xl text-blue-600">insights</span>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-4">Thống kê chi tiết</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Theo dõi tiến độ với biểu đồ và báo cáo chi tiết.
                        Phân tích năng suất và xu hướng làm việc.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="card-hover bg-white rounded-2xl p-8 border border-slate-100 shadow-lg">
                    <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mb-6">
                        <span class="material-icons text-3xl text-purple-600">schedule</span>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-4">Nhắc nhở thông minh</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Không bao giờ bỏ lỡ deadline với hệ thống lọc theo thời hạn.
                        Ưu tiên công việc quan trọng.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="card-hover bg-white rounded-2xl p-8 border border-slate-100 shadow-lg">
                    <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center mb-6">
                        <span class="material-icons text-3xl text-orange-600">devices</span>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-4">Đa nền tảng</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Sử dụng mượt mà trên máy tính, tablet và điện thoại.
                        Giao diện responsive hiện đại.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="card-hover bg-white rounded-2xl p-8 border border-slate-100 shadow-lg">
                    <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mb-6">
                        <span class="material-icons text-3xl text-red-600">security</span>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-4">Bảo mật tuyệt đối</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Dữ liệu của bạn được bảo vệ an toàn với công nghệ mã hóa hiện đại.
                        Privacy là ưu tiên hàng đầu.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="card-hover bg-white rounded-2xl p-8 border border-slate-100 shadow-lg">
                    <div class="w-16 h-16 bg-teal-100 rounded-2xl flex items-center justify-center mb-6">
                        <span class="material-icons text-3xl text-teal-600">speed</span>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-4">Hiệu suất cao</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Tải nhanh, phản hồi mượt mà. Được tối ưu hóa
                        để mang lại trải nghiệm người dùng tốt nhất.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 gradient-bg relative overflow-hidden">
        <div class="absolute inset-0">
            <div
                class="absolute top-0 left-0 w-96 h-96 bg-emerald-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20">
            </div>
            <div
                class="absolute bottom-0 right-0 w-96 h-96 bg-teal-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20">
            </div>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Sẵn sàng nâng cao năng suất?
            </h2>
            <p class="text-xl text-emerald-100 mb-8 leading-relaxed">
                Hàng ngàn người đã tin tưởng sử dụng Todo List để quản lý công việc hiệu quả.
                Bạn cũng có thể bắt đầu ngay hôm nay!
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                <a href="todo-list.html"
                    class="bg-white text-emerald-600 hover:bg-emerald-50 px-10 py-4 rounded-xl font-bold text-xl shadow-2xl hover:shadow-3xl transform hover:scale-105 transition-all duration-200 flex items-center">
                    <span class="material-icons mr-3 text-2xl">arrow_forward</span>
                    Trải nghiệm ngay
                </a>
            </div>

            <div class="mt-12 text-emerald-200">
                <p class="text-lg">✨ Hoàn toàn miễn phí • 🚀 Không cần đăng ký • 🎯 Bắt đầu trong 30 giây</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-800 text-slate-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center mb-4">
                        <span class="material-icons text-emerald-400 text-3xl">check_circle</span>
                        <h3 class="text-2xl font-bold ml-2 text-white">Todo</h3>
                    </div>
                    <p class="text-slate-400 leading-relaxed max-w-md">
                        Công cụ quản lý công việc cá nhân hiện đại, giúp bạn tối ưu hóa năng suất
                        và đạt được mục tiêu một cách hiệu quả.
                    </p>
                </div>

                <div>
                    <h4 class="font-semibold text-white mb-4">Sản phẩm</h4>
                    <ul class="space-y-2">
                        <li><a href="todo-list.html" class="hover:text-emerald-400 transition-colors">Quản lý công
                                việc</a></li>
                        <li><a href="reports.html" class="hover:text-emerald-400 transition-colors">Báo cáo thống
                                kê</a>
                        </li>
                        <li><a href="profile.html" class="hover:text-emerald-400 transition-colors">Hồ sơ cá nhân</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold text-white mb-4">Hỗ trợ</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Trung tâm hỗ trợ</a>
                        </li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Hướng dẫn sử dụng</a>
                        </li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Liên hệ</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-700 mt-8 pt-8 flex flex-col sm:flex-row justify-between items-center">
                <p class="text-slate-400">© 2025 Todo List. All rights reserved.</p>
                <div class="flex items-center space-x-6 mt-4 sm:mt-0">
                    <a href="#" class="text-slate-400 hover:text-emerald-400 transition-colors">Điều khoản</a>
                    <a href="#" class="text-slate-400 hover:text-emerald-400 transition-colors">Bảo mật</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'fadeInUp 0.8s ease-out forwards';
                }
            });
        }, observerOptions);

        // Observe feature cards
        document.querySelectorAll('.card-hover').forEach(card => {
            observer.observe(card);
        });
    </script>
</body>

</html>
