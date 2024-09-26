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
        Schema::create('job_executions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('name');
            $table->string('job');
            $table->text('details')->nullable();
            $table->json('params')->nullable();
            $table->enum('status', ['pending', 'running', 'completed', 'failed'])->default('pending');
            $table->longText('response')->nullable();
            $table->string('batch_id')->nullable();
            $table->timestamps();

            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->onDelete('cascade');
        });

        Schema::create('job_executions_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_execution_id');
            $table->string('tenant_name');
            $table->boolean('success')->default(false);
            $table->longText('response')->nullable();
            $table->timestamps();

            $table->foreign('job_execution_id')
                ->references('id')
                ->on('job_executions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_executions_logs');
        Schema::dropIfExists('job_executions');
    }
};
