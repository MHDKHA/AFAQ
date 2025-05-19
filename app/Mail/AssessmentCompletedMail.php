<?php
namespace App\Mail;

use App\Models\Assessment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class AssessmentCompletedMail extends Mailable

{
    use Queueable, SerializesModels;

    public $assessment;
    public $pdfPath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Assessment $assessment, $pdfPath)
{
    $this->assessment = $assessment;
    $this->pdfPath = $pdfPath;
}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
{
    return $this->subject('تقرير التقييم: ' . $this->assessment->name)
        ->view('emails.assessment-completed')
        ->attach($this->pdfPath, [
            'as' => 'تقرير-التقييم-' . $this->assessment->id . '.pdf',
            'mime' => 'application/pdf',
        ]);
}
}
