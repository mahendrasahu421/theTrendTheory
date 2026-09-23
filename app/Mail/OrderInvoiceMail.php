<?php

namespace App\Mail;

use App\Models\Order;
use App\Services\InvoicePdfService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order->loadMissing(['items.product', 'user']);
    }

    /**
     * Build the message with PDF Tax Invoice attachment.
     */
    public function build()
    {
        $mail = $this->subject('🧾 Tax Invoice #' . $this->order->order_number . ' — THE TREND THEORY')
                     ->view('emails.order_invoice');

        // Generate and attach official PDF Tax Invoice (Flipkart/Amazon E-commerce Style)
        try {
            $pdfContent = app(InvoicePdfService::class)->generatePdf($this->order);
            $fileName = 'Tax_Invoice_' . $this->order->order_number . '.pdf';

            $mail->attachData($pdfContent, $fileName, [
                'mime' => 'application/pdf',
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to attach PDF invoice to email: ' . $e->getMessage());
        }

        return $mail;
    }
}
