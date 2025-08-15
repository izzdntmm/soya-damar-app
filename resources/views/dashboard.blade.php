@extends('layouts.app')

@section('content')
    <div class="relative h-screen overflow-hidden bg-gray-100">
        <div class="flex items-center justify-center h-full z-10 relative">
            <div class="text-center animate-fade-in-up">
                <h1 class="text-4xl font-bold text-blue-600 mb-4">
                    Selamat datang, {{ Auth::user()->name }}
                </h1>
                <p class="text-lg text-gray-600">
                    Kamu berhasil login ke dashboard sebagai {{ Auth::user()->role }}.
                </p>
                <div class="mt-8 flex justify-center space-x-4 text-4xl text-blue-300 animate-bounce-slow">
                    <span>📊</span>
                    <span>📦</span>
                    <span>📈</span>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 1s ease-out forwards;
        }

        @keyframes bounce-slow {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-8px);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 1s ease-out forwards;
        }

        .animate-bounce-slow {
            animation: bounce-slow 2s infinite;
        }
    </style>
@endsection
