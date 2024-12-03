<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Gemini;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Http;
use Spatie\PdfToText\Pdf;
use PhpOffice\PhpWord\IOFactory;
class ChatController extends Controller
{
    // Display chat view
    public function index()
    {
        return view('admin.chat');
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx',
        ]);
        $text = '';
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            if ($extension === 'pdf') {
                $pdfText = (new Pdf())->setPdf($file->getPathname())->text();
                $text = $pdfText;
            } elseif (in_array($extension, ['doc', 'docx'])) {
                $phpWord = IOFactory::load($file->getPathname());
                $wordText = '';
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getText')) {
                            $wordText .= $element->getText() . "\n";
                        }
                    }
                }
                $text = $wordText;
            }
        } else {
            $text = $request->message;
        }
        if (empty($text)) {
            return back()->withErrors(__('يرجى إدخال رسالة أو تحميل ملف.'));
        }
        try {
            $yourApiKey = getenv('GEMINI_API_KEY');
            $client = Gemini::client($yourApiKey);
            $helperText = "يرجى تصحيح أي أخطاء إملائية أو نحوية في النص العربي التالي: \n";
            $fullMessage = $helperText . $text;
            $result = $client->geminiPro()->generateContent($fullMessage);
            $correctedText = $result->text() ?? __('حدث خطأ أثناء التدقيق الإملائي');
        } catch (\Exception $e) {
            $correctedText = __('حدث خطأ أثناء التدقيق الإملائي: ') . $e->getMessage();
        }
        return back()->with([
            'originalMessage' => $text,
            'responseMessage' => $correctedText,
        ]);

    }
}
