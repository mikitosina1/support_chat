<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('chat_rooms', function (Blueprint $table) {
			$table->id();
			$table->string('name')->nullable();
			$table->enum('status', ['open', 'closed'])->default('open');
			$table->timestamps();
		});

		Schema::create('chat_messages', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('chat_room_id');
			$table->unsignedBigInteger('user_id');
			$table->text('message');
			$table->enum('status', ['sent', 'delivered', 'read'])->default('sent');
			$table->timestamps();

			$table->foreign('chat_room_id')->references('id')->on('chat_rooms')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});

		Schema::create('chat_room_users', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('chat_room_id');
			$table->unsignedBigInteger('user_id');
			$table->timestamps();

			$table->foreign('chat_room_id')->references('id')->on('chat_rooms')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('chat_room_users');
		Schema::dropIfExists('chat_messages');
		Schema::dropIfExists('chat_rooms');
	}
};
