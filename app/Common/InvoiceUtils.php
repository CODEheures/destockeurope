<?php

namespace App\Common;


use App\Advert;
use App\Invoice;
use App\Notifications\ReportAppError;
use App\User;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;

trait InvoiceUtils
{
    public static function createInvoiceByInvoice(Invoice $invoice) {
        $user = $invoice->user;
        LocaleUtils::switchToUserLocale($user);
        $fileName = null;
        if($invoice){
            //Invoice TTC Cost
            $tva = 0;
            if($invoice->tvaSubject){
                foreach ($invoice->options as $option){
                    $tva = $tva + $option['tvaVal'];
                }
            }

            //Invoice & Address
            $invoice->load('user');
            $address = json_decode($invoice->user->geoloc)[0]->formatted_address;

            //PDF FileName
            $fileName = $invoice->filePath;
            Storage::makeDirectory($invoice->storagePath);

            //Create PDF
            $content = view('pdf.invoice.index', compact('invoice', 'address', 'tva'))->__toString();
            $header = view('pdf.header.view', compact('invoice'))->__toString();
            $footer = view('pdf.footer.view')->__toString();

            $advert = null;
            try {$advert = $invoice->advert; } catch (\Exception $e) {}
            self::createPdf($content, $header, $footer, $fileName, $advert);
        }
        LocaleUtils::switchToRuntimeLocale();
        return $fileName;
    }

    private static function createPdf($content, $header, $footer, $fileName, $advert=null) {
        try {
            $css = file_get_contents(asset(mix('css/pdf.css')->toHtml()),false,stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false))));

            $mpdf = new mPDF();
            $mpdf->SetHTMLHeader($header);
            $mpdf->SetHTMLFooter($footer);
            $mpdf->AddPageByArray([
                'margin-left' => 10,
                'margin-right' => 10,
                'margin-top' => 30,
                'margin-bottom' => 30,
                'margin-header' => 10,
                'margin-footer' => 10
            ]);
            $mpdf->WriteHTML($css,1);
            $mpdf->WriteHTML($content,2);
            $mpdf->Output($fileName, 'F');
            return true;
        } catch (\Exception $e) {
            //Mail to admin
            $recipients = User::where('role', '=', 'admin')->get();
            $senderMail = env('SERVICE_MAIL_FROM');
            $senderName = ucfirst(config('app.name'));
            $message = trans('strings.mail_apperror_pdfinvoice_line', ['advertNumber' => is_null($advert) ? '0' : $advert->id, 'mailClient' => $advert->user->email]);
            foreach ($recipients as $recipient){
                $recipient->notify(new ReportAppError($message, $senderName, $senderMail));
            }
            Throw new \Exception($e);
        }
    }

    public static function getInvoiceFile(Invoice $invoice) {
        $file = null;

        if(!is_null($invoice->filePath) && file_exists($invoice->filePath)){
            $file = $invoice->filePath;
        } else {
            try {
                $file = self::createInvoiceByInvoice($invoice);
            } catch (\Exception $e) {

            }
        }

        return $file;
    }
}