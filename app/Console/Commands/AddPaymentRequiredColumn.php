<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class AddPaymentRequiredColumn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-payment-required-column';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ajouter la colonne payment_required à la table citizen_requests';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!Schema::hasColumn('citizen_requests', 'payment_required')) {
            Schema::table('citizen_requests', function ($table) {
                $table->boolean('payment_required')->default(true)->after('payment_status');
            });
            $this->info('Colonne payment_required ajoutée avec succès.');
        } else {
            $this->info('La colonne payment_required existe déjà.');
        }
    }
}
