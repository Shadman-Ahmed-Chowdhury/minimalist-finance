<?php

   namespace App\Mail;

   use App\Models\User;
   use Illuminate\Bus\Queueable;
   use Illuminate\Mail\Mailable;
   use Illuminate\Queue\SerializesModels;
   use Illuminate\Support\Carbon;
   use Illuminate\Support\Facades\Storage;

   class MonthlyReportsMail extends Mailable
   {
       use Queueable, SerializesModels;

       public $user;
       public $filePaths;
       public $month;

       public function __construct(User $user, array $filePaths, Carbon $month)
       {
           $this->user = $user;
           $this->filePaths = $filePaths;
           $this->month = $month;
       }

       public function build()
       {
           $email = $this
               ->subject('Your Monthly Financial Reports - ' . $this->month->format('F Y'))
               ->view('emails.monthly-reports');

           foreach ($this->filePaths as $type => $filePath) {
               $email->attach(Storage::path($filePath), [
                   'as' => "{$type}_report_" . $this->month->format('Y_m') . '.xlsx',
                   'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
               ]);
           }

           // Delete files after queuing
           foreach ($this->filePaths as $filePath) {
               Storage::delete($filePath);
           }

           return $email;
       }
   }
