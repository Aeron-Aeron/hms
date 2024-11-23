<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedConnect - Find the Right Doctor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center">
                        <span class="text-2xl font-bold text-blue-600">MedConnect</span>
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-gray-900">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Login</a>
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-6">
                        Find the Right Doctor, Right Now
                    </h1>
                    <p class="text-xl mb-8">
                        Connect with qualified healthcare professionals and book appointments instantly.
                    </p>
                    <div class="space-x-4">
                        <a href="{{ route('register') }}"
                           class="bg-white text-blue-600 px-6 py-3 rounded-md font-semibold hover:bg-gray-100">
                            Get Started
                        </a>
                        <a href="#how-it-works"
                           class="border border-white text-white px-6 py-3 rounded-md font-semibold hover:bg-blue-700">
                            Learn More
                        </a>
                    </div>
                </div>
                <div class="hidden md:block">
                    <img src="{{ asset('images/doctor-illustration.jpg') }}" alt="Doctor illustration" class="w-full rounded-lg shadow-lg">
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-16" id="how-it-works">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">How It Works</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Find a Doctor</h3>
                    <p class="text-gray-600">
                        Search for specialists based on your symptoms or specific medical needs.
                    </p>
                </div>

                <div class="text-center p-6">
                    <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Book Appointment</h3>
                    <p class="text-gray-600">
                        Choose a convenient time slot and book your appointment instantly.
                    </p>
                </div>

                <div class="text-center p-6">
                    <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Get Care</h3>
                    <p class="text-gray-600">
                        Visit your doctor and receive quality healthcare services.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Doctors Section -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">Our Featured Doctors</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($featuredDoctors as $doctor)
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-2xl text-blue-600">{{ substr($doctor->name, 0, 1) }}</span>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-semibold">Dr. {{ $doctor->name }}</h3>
                                <p class="text-gray-600">{{ optional($doctor->doctorProfile)->specialization ?? 'Specialist' }}</p>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4">{{ optional($doctor->doctorProfile)->bio ?? 'Professional healthcare provider.' }}</p>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-8">
                        <p class="text-gray-500">No featured doctors available at the moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">What Our Patients Say</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($testimonials as $testimonial)
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="mb-4">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4">{{ $testimonial->comment }}</p>
                        <p class="font-semibold">{{ $testimonial->user->name }}</p>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-8">
                        <p class="text-gray-500">No testimonials available yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-blue-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-4">Ready to Get Started?</h2>
            <p class="text-xl mb-8">Join thousands of patients who found the right doctor through MedConnect.</p>
            <div class="space-x-4">
                <a href="{{ route('register') }}?role=patient"
                   class="bg-white text-blue-600 px-6 py-3 rounded-md font-semibold hover:bg-gray-100">
                    Register as Patient
                </a>
                <a href="{{ route('register') }}?role=doctor"
                   class="border border-white text-white px-6 py-3 rounded-md font-semibold hover:bg-blue-700">
                    Register as Doctor
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-white font-semibold mb-4">MedConnect</h3>
                    <p class="text-sm">Making healthcare accessible to everyone.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white">Find Doctors</a></li>
                        <li><a href="#" class="hover:text-white">About Us</a></li>
                        <li><a href="#" class="hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">For Doctors</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white">Join as Doctor</a></li>
                        <li><a href="#" class="hover:text-white">Doctor Login</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Contact Us</h4>
                    <ul class="space-y-2 text-sm">
                        <li>Email: support@medconnect.com</li>
                        <li>Phone: (555) 123-4567</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-sm text-center">
                <p>&copy; {{ date('Y') }} MedConnect. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div>
</body>
</html>
