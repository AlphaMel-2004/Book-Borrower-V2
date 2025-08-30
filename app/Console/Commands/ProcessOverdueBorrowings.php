<?php

namespace App\Console\Commands;

use App\Models\Borrowing;
use App\Models\Fine;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessOverdueBorrowings extends Command
{
    protected $signature = 'process:overdues';
    protected $description = 'Process overdue borrowings and create fines';

    public function handle(): void
    {
        $this->info('Processing overdue borrowings...');

        DB::transaction(function () {
            // Get all borrowings that are overdue but not marked as overdue yet
            $overdueBorrowings = Borrowing::where('status', 'borrowed')
                ->where('due_at', '<', now())
                ->whereNull('returned_at')
                ->get();

            foreach ($overdueBorrowings as $borrowing) {
                // Mark as overdue
                $borrowing->status = 'overdue';
                $borrowing->save();

                // Calculate fine amount
                $daysOverdue = Carbon::now()->diffInDays($borrowing->due_at);
                $fineAmount = $daysOverdue * config('bookborrower.fine_amount_per_day', 1.00);

                // Create fine record
                Fine::create([
                    'borrowing_id' => $borrowing->id,
                    'amount' => $fineAmount,
                    'paid' => false,
                ]);

                $this->info("Processed borrowing #{$borrowing->id}: {$daysOverdue} days overdue, fine amount: ${$fineAmount}");
            }
        });

        $this->info('Completed processing overdue borrowings.');
    }
}
