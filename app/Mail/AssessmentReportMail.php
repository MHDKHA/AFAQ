<?php

namespace App\Mail;

use App\Models\Assessment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AssessmentReportMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $assessment;
    protected $customMessage;
    protected $pdfPath;

    public $locale;

    /**
     * Create a new message instance.
     *
     * @param  Assessment  $assessment
     * @param  string  $subject
     * @param  string|null  $customMessage
     * @param  string  $pdfPath
     * @param  string  $locale
     * @return void
     */
    public function __construct(Assessment $assessment, string $subject, ?string $customMessage, string $pdfPath, string $locale)
    {
        $this->assessment = $assessment;
        $this->subject = $subject;
        $this->customMessage = $customMessage;
        $this->pdfPath = $pdfPath;
        $this->locale = $locale;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $emailView = $this->locale === 'ar' ? 'emails.assessment-report-ar' : 'emails.assessment-report';

        return $this->subject($this->subject)
            ->view($emailView, [
                'assessment' => $this->assessment,
                'customMessage' => $this->customMessage,
            ])
            ->attach($this->pdfPath, [
                'as' => ($this->locale === 'ar' ? 'تقرير_التقييم_' : 'assessment_report_') . $this->assessment->id . '.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
