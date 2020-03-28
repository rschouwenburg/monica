<?php

namespace App\Jobs;

use App\Models\Account\ImportJob;
use App\Services\VCard\ImportVCard;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddContactFromVCard implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $importJob;
    protected $behaviour;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ImportJob $importJob, $behaviour = ImportVCard::BEHAVIOUR_ADD)
    {
        $this->importJob = $importJob;
        $this->behaviour = $behaviour;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->importJob->process($this->behaviour);
    }
}
