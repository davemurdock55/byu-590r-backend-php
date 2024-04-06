<?php

namespace App\Console\Commands;

use App\Mail\BooksReportMail;
use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class BooksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:books {--email=}';
    // protected $signature = 'auto:books-report {--email=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends an email report of all books to the app owner.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sendToEmail = $this->option('email');
        error_log($sendToEmail);

        if (!$sendToEmail) {
            error_log('Please provide an email to send the report to.');
            return Command::FAILURE;
        }

        $books = Book::all(); // could replace "all()" with "get()"

        error_log($books);

        if ($books->count() > 0) {
            Mail::to($sendToEmail)->send(new BooksReportMail($books));
        }

        //         $overDueCheckouts = Checkout::whereNull('checkin_date')
        //             ->where('due_date', '<=', date('Y-m-d'))
        //             > with(['users', 'books']) > get();
        //
        //         if ($overDueCheckouts->count() > 0) {
        //
        //             //Send one main list of all overdue books email to management
        //             Mail::to($sendToEmail)->send(new OverdueBooksMasterList($overDueCheckouts));
        //         }

        return Command::SUCCESS;
    }
}
