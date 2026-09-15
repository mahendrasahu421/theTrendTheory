<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class InvoicePdfService
{
    /**
     * Generate raw PDF binary output for an order.
     */
    public function generatePdf(Order $order): string
    {
        $order->loadMissing(['items.product', 'user']);

        $pdf = Pdf::loadView('invoices.pdf_invoice', compact('order'))
                  ->setPaper('a4', 'portrait')
                  ->setOption([
                      'isRemoteEnabled'      => true,
                      'isHtml5ParserEnabled' => true,
                      'dpi'                  => 150,
                      'defaultFont'          => 'sans-serif',
                  ]);

        return $pdf->output();
    }

    /**
     * Return downloadable PDF response for an order.
     */
    public function downloadPdf(Order $order)
    {
        $order->loadMissing(['items.product', 'user']);

        $pdf = Pdf::loadView('invoices.pdf_invoice', compact('order'))
                  ->setPaper('a4', 'portrait');

        $fileName = 'Tax_Invoice_' . $order->order_number . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Stream PDF directly in browser (for print/preview).
     */
    public function streamPdf(Order $order)
    {
        $order->loadMissing(['items.product', 'user']);

        $pdf = Pdf::loadView('invoices.pdf_invoice', compact('order'))
                  ->setPaper('a4', 'portrait');

        $fileName = 'Tax_Invoice_' . $order->order_number . '.pdf';

        return $pdf->stream($fileName);
    }

    /**
     * Convert number to Indian currency words format.
     */
    public static function numberToWords(int $num): string
    {
        $ones = [
            0 => '', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four',
            5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve', 13 => 'thirteen',
            14 => 'fourteen', 15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen',
            18 => 'eighteen', 19 => 'nineteen'
        ];
        $tens = [
            2 => 'twenty', 3 => 'thirty', 4 => 'forty', 5 => 'fifty',
            6 => 'sixty', 7 => 'seventy', 8 => 'eighty', 9 => 'ninety'
        ];

        if ($num === 0) return 'zero';

        $words = [];

        if ($num >= 10000000) {
            $crores = intval($num / 10000000);
            $words[] = self::numberToWords($crores) . ' crore';
            $num %= 10000000;
        }

        if ($num >= 100000) {
            $lakhs = intval($num / 100000);
            $words[] = self::numberToWords($lakhs) . ' lakh';
            $num %= 100000;
        }

        if ($num >= 1000) {
            $thousands = intval($num / 1000);
            $words[] = self::numberToWords($thousands) . ' thousand';
            $num %= 1000;
        }

        if ($num >= 100) {
            $hundreds = intval($num / 100);
            $words[] = $ones[$hundreds] . ' hundred';
            $num %= 100;
        }

        if ($num > 0) {
            if ($num < 20) {
                $words[] = $ones[$num];
            } else {
                $t = intval($num / 10);
                $u = $num % 10;
                $words[] = $tens[$t] . ($u > 0 ? ' ' . $ones[$u] : '');
            }
        }

        return implode(' ', array_filter($words));
    }
}
