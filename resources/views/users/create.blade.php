@extends('layouts.app')
@section('bodycontent')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <a href="{{ route('users.index') }}" title="back" class="inline-flex items-center px-4 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-900 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150" ><i class="fa-solid fa-arrow-left-long"></i></a><br><br>
                <h5 class="font-bold text-center text-gray-900 text-xl">New User</h5><br>                  
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div>
                        <label for="name">Full Name</label><br>
                        <input type="text" name="name" class="block w-96 appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" placeholder="full name" required>
                    </div>
                    @error('name') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br> 
                    <div>
                        <label for="email">Email</label><br>
                        <input type="text" name="email" id="email" class="block w-96 appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" placeholder="email" required>
                    </div>
                    <p id="danger_alert1" class="text-sm text-red-500 mb-2" style="display:none;"></p>
                    @error('email') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br>  
                    <div>
                        <label for="contact">Contact Number</label><br>
                        <input type="number" name="contact" id="contact" class="block w-96 appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" placeholder="contact number" required>
                    </div>
                    <p id="danger_alert2" class="text-sm text-red-500 mb-2" style="display:none;"></p>
                    @error('contact') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br>
                    <div>
                        <label for="type">Select User Type</label><br>
                        <select name="type" id="type" class="block w-96 appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" required>
                            <option value="" disabled selected>Select type from here</option>
                            <option value="1">Admin</option>
                            <option value="2">Inventory Manager</option>
                            <option value="3">Branch Manager</option>
                        </select> 
                    </div>
                    @error('type') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br>                                                                              
                    <div>
                        <label for="password">Password</label><br>
                        <div class="relative w-96">
                            <input type="password" name="password" id="addpassword" autocomplete="new-password" class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 pr-10 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" placeholder="password" required>
                            <button type="button" id="togglePassword" class="hidden absolute inset-y-0 right-0 flex items-center pr-3 text-gray-600 focus:outline-none">
                                <img src="{{ asset('assets/eye.svg') }}" id="eye" alt="Eye Icon" class="w-5 h-5">
                                <img src="{{ asset('assets/eye-slash.svg') }}" id="eye_slash" style="display:none;" alt="Eye Slash Icon" class="w-5 h-5">
                            </button>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">*The password must contain at least one uppercase letter, <br> one number, and one special character.</p>
                    @error('password') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br>
                    <div>
                        <label for="confirm_password">Confirm Password</label><br>
                        <input type="password" name="confirm_password" id="cmpassword" autocomplete="new-password" class="block w-96 appearance-none rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" placeholder="confirm password" required>        
                        <span id='passwordcheck'></span>
                    </div>
                    @error('confirm_password') <span class="text-red-500 error">{{ $message }}</span><br> @enderror
                    <br>                    
                    <button type="submit" class="passwordvalid disabled:opacity-25 inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-900 focus:bg-blue-900 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-25">Save</button>                        
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#email').change(function() {
                document.getElementById('danger_alert1').style.display = "none";
                var email = $(this).val();
                if (email.length >= 3){
                    $.ajax({
                        type: 'GET',
                        url: '/users/email/check',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            "email": email
                        },
                        success: function(response) {
                            if (response.success) {
                                $(".passwordvalid").attr('disabled', false);

                            } else {
                                document.getElementById('danger_alert1').innerHTML = "Already Registered User!"
                                document.getElementById('danger_alert1').style.display = "block";
                                $(".passwordvalid").attr('disabled', true);
                            }


                        },
                        error: function(data) {
                            console.log('Something went wrong!');
                            document.getElementById('danger_alert1').innerHTML = "Already Registered User!"
                            document.getElementById('danger_alert1').style.display = "block";
                            $(".passwordvalid").attr('disabled', true);
                        }
                    });
                }
            });
        });

        $(document).ready(function() {
            $('#contact').change(function() {
                document.getElementById('danger_alert2').style.display = "none";
                var contact = $(this).val();
                if (contact.length >= 10){
                    $.ajax({
                        type: 'GET',
                        url: '/users/contact/check',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            "contact": contact
                        },
                        success: function(response) {
                            if (response.success) {
                                $(".passwordvalid").attr('disabled', false);

                            } else {
                                document.getElementById('danger_alert2').innerHTML = "Already Registered Number!"
                                document.getElementById('danger_alert2').style.display = "block";
                                $(".passwordvalid").attr('disabled', true);
                            }


                        },
                        error: function(data) {
                            console.log('Something went wrong!');
                            document.getElementById('danger_alert2').innerHTML = "Already Registered Number!"
                            document.getElementById('danger_alert2').style.display = "block";
                            $(".passwordvalid").attr('disabled', true);
                        }
                    });
                }
            });
        });
    </script>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('addpassword');
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
        $('#addpassword, #cmpassword').on('keyup', function () { 
        var password = $('#addpassword').val();
        if (password.length >= 1){
            document.getElementById('togglePassword').style.display = "block";
        }
        else{
            document.getElementById('togglePassword').style.display = "none";
        }
        if ($('#addpassword').val() == $('#cmpassword').val()) {
            $('#passwordcheck').html('');
            $(".passwordvalid").attr('disabled', false);
        }
        else if($('#cmpassword').val() == ''){
            $('#passwordcheck').html('');
        }
        else { 
            $('#passwordcheck').html('Passwords Not Matching').css('color', 'red');
            $(".passwordvalid").attr('disabled', true);
        }
        if ($('#addpassword').val() == '' && $('#cmpassword').val() == '') {
            $('#passwordcheck').html('');
        }  
        });
    </script>
@endpush