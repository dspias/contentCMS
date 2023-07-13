<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');

            $table->foreignId('work_provider_id')->constrained()->onDelete('cascade');

            $table->foreignId('writer_id')->nullable()->constrained()->onDelete('cascade');

            $table->string('unit_name')->nullable();
            $table->string('unit_code')->nullable();
            
            $table->integer('word_count')->nullable();
            $table->double('price', 8, 2);
            $table->double('paid', 8, 2)->nullable();

            $table->text('context')->nullable();
            $table->text('comment')->nullable();

            $table->date('delivery_date')->nullable();
            $table->date('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contents');
    }
}
