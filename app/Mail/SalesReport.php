<?php

namespace App\Mail;

use App\Reports\SaleReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SalesReport extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public $period;

    public $formattedPeriod;

    private $fileName;

    public function __construct($user, $period)
    {
        $this->user = $user;

        $this->period = $period;

        $this->formattedPeriod = $this->period[0]->isSameDay($this->period[1])
        ? $this->period[0]->toFormattedDateString()
        : $this->period[0]->format('F Y');

        $this->fileName = sprintf('Sales Report (%s).pdf', $this->formattedPeriod);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('analytics@onrica.com', 'Onrica Analytics'),
            subject: str($this->fileName)->remove('.pdf')->toString(),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.reports.sale',
            with: [
                'hasSales' => (new SaleReport(['period' => $this->period], $this->user->employee->company))->getSalesCount,
            ]
        );
    }

    public function attachments(): array
    {
        $pdf = $this->generatePdf();

        if (!$pdf) {
            return [];
        }

        return [
            Attachment::fromData(fn() => $pdf, $this->fileName)->withMime('application/pdf'),
        ];
    }

    protected function generatePdf()
    {
        $company = $this->user->employee->company;

        $saleReport = new SaleReport(['period' => $this->period], $this->user->employee->company);

        $period = $this->period;

        if (!$saleReport->getSalesCount) {
            return false;
        }

        return Pdf::loadView('reports.sale-print', compact('saleReport', 'period', 'company'))->output();
    }
}
