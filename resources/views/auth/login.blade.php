<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Inventory Management System</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/logo.png') }}">        
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <!-- font-awesome icons -->
        <script src="https://kit.fontawesome.com/2d49de291b.js" crossorigin="anonymous"></script>

    </head>
    <body>
            <div class="relative min-w-screen min-h-screen bg-cover bg-center flex items-center justify-center px-5 py-5" style="background-image: url('{{ asset('assets/ELRAH EXCLUSIVE.png') }}');">
                <div class="absolute inset-0 bg-black opacity-40 z-0"></div> <!-- Overlay -->
            
             <div class="relative z-10 flex justify-center items-center min-h-screen">
            <div class="bg-gray-100 text-gray-500 rounded-3xl shadow-xl w-full overflow-hidden" style="max-width:1000px">
                <div class="md:flex w-full">
                    <div class="hidden md:flex w-1/2 bg-white py-10 px-10 justify-center">                        
                        <div class="w-full mt-8">                         
                            <img src="{{ asset('assets\LOGOhaprima.png') }}" alt="form">
                        </div>
                    </div>
                    <div class="w-full md:w-1/2 py-10 px-5 md:px-10">  
                        <div class="text-center mb-10 md:mt-16">                            
                            <h1 class="font-bold text-3xl text-gray-900">SIGN IN</h1>
                        </div>
                        @if (session('status'))
                            <div class="text-black m-2 p-4 bg-green-200">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="text-black m-2 p-4 bg-yellow-200">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('delete'))
                            <div class="text-black m-2 p-4 bg-red-200">
                                {{ session('delete') }}
                            </div>
                        @endif
                        <form action="{{ route('login.post') }}" method="POST" id="loginForm">
                        @csrf
                            <div>
                                <div class="flex -mx-3">
                                    <div class="w-full px-3 mb-2">
                                        <label for="" class="text-xs font-semibold px-1">Email</label>
                                        <div class="flex">
                                            <div class="w-10 z-10 pl-1 text-center pointer-events-none flex items-center justify-center"><i class="mdi mdi-email-outline text-gray-400 text-lg"></i></div>
                                            <input type="email" name="email" id="email" class="w-full -ml-10 pl-10 pr-3 py-2 rounded-lg border-2 border-gray-200 outline-none focus:border-indigo-500" placeholder="email" required>
                                        </div>
                                    </div>
                                </div>
                                @error('email') <span class="text-red-500 error mb-2">{{ $message }}</span><br> @enderror
                                <div class="flex -mx-3">
                                    <div class="w-full px-3 mb-2">
                                        <label for="password" class="text-xs font-semibold px-1">Password</label>
                                        <div class="relative flex">
                                            <div class="w-10 z-10 pl-1 text-center pointer-events-none flex items-center justify-center">
                                                <i class="mdi mdi-lock-outline text-gray-400 text-lg"></i>
                                            </div>
                                            <input type="password" name="password" id="password" class="w-full -ml-10 pl-10 pr-10 py-2 rounded-lg border-2 border-gray-200 outline-none focus:border-indigo-500" placeholder="password" required>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                <button type="button" id="togglePassword" class="hidden text-gray-600 focus:outline-none">
                                                    <img src="{{ asset('assets/eye.svg') }}" id="eye" alt="Eye Icon" class="w-5 h-5">
                                                    <img src="{{ asset('assets/eye-slash.svg') }}" id="eye_slash" style="display:none;" alt="Eye Slash Icon" class="w-5 h-5">
                                                </button>
                                            </div>
                                        </div>                                    
                                    </div>
                                </div>                            
                                @error('password') <span class="text-red-500 error mb-2">{{ $message }}</span><br> @enderror
                                
                                <div class="flex -mx-3 mt-4">
                                    <div class="w-full px-3 mb-5">
                                        <button type="submit" onclick="handleSignIn()" id="signInButton" class="disabled:opacity-25 block w-full max-w-xs mx-auto bg-indigo-800 hover:bg-indigo-900 focus:bg-indigo-800 text-white rounded-lg px-3 py-3 font-semibold">SIGN IN</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div> <!-- End of relative z-10 -->
                    </div> <!-- End of background image container -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
        <script>
            document.getElementById('togglePassword').addEventListener('click', function () {
                const passwordField = document.getElementById('password');
                const passwordIcon = document.getElementById('eye');
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    $('#eye').hide();
                    $('#eye_slash').show();
                } else {
                    passwordField.type = 'password';
                    $('#eye').show();
                    $('#eye_slash').hide();
                }
            });
        </script>
        <script>
            $('#password').on('keyup', function () { 
                var password = $('#password').val();
                if (password.length >= 1){
                    document.getElementById('togglePassword').style.display = "block";
                }
                else{
                    document.getElementById('togglePassword').style.display = "none";
                }
            });
        </script>
        <script>
            function handleSignIn() {
                var signInButton = document.getElementById('signInButton');
                signInButton.disabled = true;
                deleteAllCookies();
                window.localStorage.clear();
                window.location.href = "{{ route('login') }}";

                // Submit the form
                $('#loginForm').submit();
            }

            function deleteAllCookies() {
                var cookies = document.cookie.split(";");
                for (var i = 0; i < cookies.length; i++) {
                    var cookie = cookies[i];
                    var eqPos = cookie.indexOf("=");
                    var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
                    document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
                }
                    

            }
        </script>
    </body>
</html>