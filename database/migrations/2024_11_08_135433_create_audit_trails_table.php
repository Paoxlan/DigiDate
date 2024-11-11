<?php

use App\Models\User;
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
        Schema::create('audit_trails', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('class', 100);
            $table->enum('method', ['Create', 'Update', 'Delete']);
            $table->json('old_model')
                ->nullable();
            $table->json('model');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_trails');
    }
};
