<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('afspraken', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patientid');
            $table->unsignedBigInteger('medewerkerid');
            $table->date('datum');
            $table->time('tijd');
            $table->enum('status', ['Bevestigd', 'Geannuleerd'])->default('Bevestigd');
            $table->boolean('isactief')->default(1);
            $table->text('opmerking')->nullable();
            $table->timestamp('datumaangemaakt')->useCurrent();
            $table->timestamp('datumgewijzigd')->useCurrent()->useCurrentOnUpdate();
            
            // Indexes
            $table->index('patientid', 'ix_afspraken_patientid');
            $table->index('medewerkerid', 'ix_afspraken_medewerkerid');
            
            // Foreign keys
            $table->foreign('patientid', 'fk_afspraken_patient')
                  ->references('id')->on('patient')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
                  

            $table->foreign('medewerkerid', 'fk_afspraken_medewerker')
                  ->references('id')->on('medewerker')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('afspraken');
    }
};
