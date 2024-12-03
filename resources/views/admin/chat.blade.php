@extends('admin.main')

@section('content')
    <main class="app-content">
        <div class="app-title">
            <h1><i class="fa fa-comments"></i> {{ __('المساعدة في التدقيق الإملائي') }}</h1>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tile">
                    <h3 class="tile-title">{{ __('أدخل النص للتحقق من التدقيق الإملائي') }}</h3>
                    <form method="POST" action="{{ route('main_admin.chat.sendMessage') }}" enctype="multipart/form-data" class="p-4">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="message" class="form-label">{{ __('الرسالة') }}</label>
                            <textarea id="message" name="message" class="form-control p-3" rows="5" placeholder="{{ __('اكتب رسالتك هنا...') }}">{{ old('message') }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="file" class="form-label">{{ __('تحميل ملف (PDF أو Word)') }}</label>
                            <input type="file" id="file" name="file" class="form-control -3" accept=".pdf,.doc,.docx">
                        </div>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm">
                                {{ __('إرسال') }} <i class="fa fa-paper-plane ml-2"></i>
                            </button>
                        </div>
                    </form>


                    <div class="row mt-3">
                    @if (session('responseMessage'))


                        <div class="col-md-6">
                            <div class="tile">
                                <h5 class="tile-title textRight3">{{ __('النص المصحح') }}</h5>
                                <div id="correctedMessage" class="tile-body alert alert-info">{{ session('responseMessage') }}</div>
                                <div class="tile-footer">
                                    <button class="btn btn-primary" onclick="copyCorrectedMessage()">
                                        {{ __('نسخ النص') }} <i class="fa fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                        @if (session('originalMessage'))
                            <div class="col-md-6">
                                <div class="tile">
                                    <h5 class="tile-title textRight3">{{ __('النص الأصلي') }}</h5>
                                    <div class="tile-body alert alert-secondary">{{ session('originalMessage') }}</div>
                                </div>
                            </div>
                    @endif
                </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('js')
    <script>
        function copyCorrectedMessage() {
            var correctedMessage = document.getElementById('correctedMessage').innerText;
            var tempInput = document.createElement('textarea');
            tempInput.value = correctedMessage;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            alert('تم نسخ النص المصحح إلى الحافظة.');
        }
    </script>
@endpush
