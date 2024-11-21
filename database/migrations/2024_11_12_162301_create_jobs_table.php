<?php

use App\Models\Employer;
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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Employer::class);
            $table->string('title');
            $table->string('salary');
            $table->string('location');
            $table->string('schedule')->default('Full Time');
            $table->string('url')->default('http://example.com');
            $table->boolean('featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    if (Schema::hasTable('job_applications') && Schema::hasColumn('job_applications', 'job_id')) {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
        });
    }

    Schema::dropIfExists('job_applications');

    Schema::dropIfExists('jobs');
}

};
