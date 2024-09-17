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
        Schema::create('liked_users', function (Blueprint $table) {
            $table->foreignIdFor(User::class, 'from_id')->constrained('users');
            $table->foreignIdFor(User::class, 'to_id')->constrained('users');
            $table->boolean('is_liked')->default(false);

            $table->primary(['from_id', 'to_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liked_users');
    }
};
