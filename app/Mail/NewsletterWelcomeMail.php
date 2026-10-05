<?php

namespace App\Mail;

use App\Models\NewsletterSubscriber;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public NewsletterSubscriber $subscriber;
    public string $couponCode;
    public string $siteName;
    public string $shopUrl;
    public string $siteEmail;

    /**
     * Create a new message instance.
     */
    public function __construct(NewsletterSubscriber $subscriber, string $couponCode = 'TREND10')
    {
        $this->subscriber = $subscriber;
        $this->couponCode = $couponCode;
        $this->siteName   = SiteSetting::get('site_name', 'THE TREND THEORY');
        $this->shopUrl    = url('/shop');
        $this->siteEmail  = SiteSetting::get('contact_email', config('mail.from.address', 'info.trendtheory@gmail.com'));
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('🎉 Welcome to THE TREND THEORY | Your 10% Welcome Gift Inside!')
                    ->view('emails.newsletter-welcome');
    }
}
