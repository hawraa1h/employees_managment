<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS -->
    <!-- Font-icon css -->
    <title>مولد كلمة مرور قوية</title>
    <link rel="stylesheet" type="text/css" href="{{asset('admin/css/ar.css')}}">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link href='https://fonts.googleapis.com/css?family=Cairo' rel='stylesheet'>
    <style>
        body {
            font-family: 'Cairo';
        }
        .strength-indicator {
            font-weight: bold;
            padding: 10px;
            margin-top: 10px;
            text-align: center;
        }
        .weak {
            color: red;
        }
        .medium {
            color: orange;
        }
        .strong {
            color: green;
        }
        .generated-password {
            background-color: #f9f9f9;
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }
        .login-box {
            min-width: 100% !important;
        }
        .material-half-bg .cover {
            background-color: #ffffff;
            height: 50vh;
        }
        .material-half-bg {
            height: 100vh;
            background-color: #ffffff;}
        .login-content .logo {
            margin-bottom: 16px;
            /* font-family: "Niconne"; */
            color: #fff;
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
        .btn-primary {
            color: #FFF;
            background-color: #8a2a2f;
            border-color: #8a2a2f;
        }
    </style>
</head>
<body>
<section class="material-half-bg">
    <div class="cover"></div>
</section>
<section class="login-content">

    <div class="login-box">
        <div class="logo">
            <img height="120px" src="{{asset('logo.png')}}">
        </div>
        <div class="card container shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="card-title text-center">مولد كلمة مرور قوية</h5>
                        <hr>
                        <!-- Password Length -->
                        <div class="mb-3">
                            <label for="passwordLength" class="form-label">طول كلمة المرور:</label>
                            <input type="number" class="form-control" id="passwordLength" min="8" max="32" value="12">
                        </div>

                        <!-- Options for Symbols, Numbers, Lowercase, Uppercase -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="includeSymbols" checked>
                            <label class="form-check-label" for="includeSymbols">تضمين الرموز</label>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="includeNumbers" checked>
                            <label class="form-check-label" for="includeNumbers">تضمين الأرقام</label>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="includeLowercase" checked>
                            <label class="form-check-label" for="includeLowercase">تضمين الأحرف الصغيرة</label>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="includeUppercase" checked>
                            <label class="form-check-label" for="includeUppercase">تضمين الأحرف الكبيرة</label>
                        </div>

                        <!-- Generate Button -->
                        <button id="generateBtn" class="btn btn-primary w-100">توليد كلمة مرور</button>

                        <!-- Display Generated Password -->
                        <div class="generated-password" id="generatedPassword"></div>
                        <button id="copyBtn" class="btn btn-secondary w-100 mt-3">نسخ كلمة المرور</button>

                        <!-- Password Strength Checker -->
                        <hr>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-center">تحقق من قوة كلمة المرور</h6>
                        <div class="mb-3">
                            <label for="checkPassword" class="form-label">أدخل كلمة المرور:</label>
                            <input type="text" class="form-control" id="checkPassword" placeholder="أدخل كلمة المرور">
                        </div>
                        <button id="checkBtn" class="btn btn-secondary w-100">تحقق من القوة</button>

                        <!-- Display Password Strength -->
                        <div id="passwordStrength" class="strength-indicator"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Scripts -->
<script src="{{asset('admin/js/jquery-3.2.1.min.js')}}"></script>
<script src="{{asset('admin/js/popper.min.js')}}"></script>
<script src="{{asset('admin/js/bootstrap.min.js')}}"></script>
<script src="{{asset('admin/js/main.js')}}"></script>
<script src="{{asset('admin/js/plugins/pace.min.js')}}"></script>
<script>
    const generateBtn = document.getElementById('generateBtn');
    const passwordDisplay = document.getElementById('generatedPassword');
    const checkBtn = document.getElementById('checkBtn');
    const passwordStrength = document.getElementById('passwordStrength');
    const copyBtn = document.getElementById('copyBtn');

    // Generate Password
    generateBtn.addEventListener('click', () => {
        const length = document.getElementById('passwordLength').value;
        const includeSymbols = document.getElementById('includeSymbols').checked;
        const includeNumbers = document.getElementById('includeNumbers').checked;
        const includeLowercase = document.getElementById('includeLowercase').checked;
        const includeUppercase = document.getElementById('includeUppercase').checked;

        const password = generatePassword(length, includeSymbols, includeNumbers, includeLowercase, includeUppercase);
        passwordDisplay.innerText = password;
    });

    // Function to generate password
    function generatePassword(length, symbols, numbers, lowercase, uppercase) {
        const symbolChars = "!@#$%^&*()_+~`|}{[]:;?><,./-=";
        const numberChars = "0123456789";
        const lowerChars = "abcdefghijklmnopqrstuvwxyz";
        const upperChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

        let allChars = "";
        if (symbols) allChars += symbolChars;
        if (numbers) allChars += numberChars;
        if (lowercase) allChars += lowerChars;
        if (uppercase) allChars += upperChars;

        if (allChars === "") return "الرجاء اختيار على الأقل خيار واحد!";

        let password = "";
        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * allChars.length);
            password += allChars[randomIndex];
        }

        return password;
    }

    // Check Password Strength
    checkBtn.addEventListener('click', () => {
        const password = document.getElementById('checkPassword').value;
        const strength = checkPasswordStrength(password);
        passwordStrength.innerText = strength.message;
        passwordStrength.className = 'strength-indicator ' + strength.class;
    });

    // Function to check password strength
    function checkPasswordStrength(password) {
        let strength = { message: 'ضعيف', class: 'weak' };
        const regexWeak = /^[a-zA-Z0-9]{6,}$/;
        const regexMedium = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
        const regexStrong = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{12,}$/;

        if (regexStrong.test(password)) {
            strength = { message: 'قوي', class: 'strong' };
        } else if (regexMedium.test(password)) {
            strength = { message: 'متوسط', class: 'medium' };
        } else if (regexWeak.test(password)) {
            strength = { message: 'ضعيف', class: 'weak' };
        } else {
            strength = { message: 'ضعيف جداً', class: 'weak' };
        }

        return strength;
    }

    copyBtn.addEventListener('click', () => {
        const password = passwordDisplay.innerText;
        if (password) {
            // Check if the Clipboard API is available
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(password).then(() => {
                    alert('تم نسخ كلمة المرور');
                }).catch(() => {
                    alert('فشل في نسخ كلمة المرور');
                });
            } else {
                // Fallback method for older browsers or insecure contexts
                const textarea = document.createElement('textarea');
                textarea.value = password;
                document.body.appendChild(textarea);
                textarea.select();
                try {
                    document.execCommand('copy');
                    alert('تم نسخ كلمة المرور');
                } catch (err) {
                    alert('فشل في نسخ كلمة المرور');
                }
                document.body.removeChild(textarea);
            }
        }
    });

</script>
</body>
</html>
