<?php

use App\Enums\ReservationStatusEnum;
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
        Schema::create('reservations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')->constrained('users' , 'id');
            $table->foreignUlid('room_type_id')->constrained('room_types' , 'id');
            $table->enum('status' , ReservationStatusEnum::cases())->index();
            $table->date('date_to_reserve')->index();
            $table->dateTime('expiration_time');
            $table->integer('requested_count')->default(1);
            $table->timestamps();

            $table->index(['status' , 'expiration_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
