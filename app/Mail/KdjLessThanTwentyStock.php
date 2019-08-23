<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class KdjLessThanTwentyStock extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $stocks;

    public function __construct($stocks)
    {
        $this->stocks = $stocks;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.nice.stock')
            ->subject("KDJ小于20")
            ->with(['stocks' => $this->stocks]);
    }
}
