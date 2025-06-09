<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    public function __construct($details) {
        $this->details = $details;
    }

    public function build()
    {
        $pdf = PDF::loadView('pdf.ticket', $this->details);
        return $this->subject('E-Ticket Kamu')
                    ->attachData($pdf->output(), 'eticket.pdf')
                    ->markdown('emails.ticket')
                    ->with('details', $this->details);
    }
}
