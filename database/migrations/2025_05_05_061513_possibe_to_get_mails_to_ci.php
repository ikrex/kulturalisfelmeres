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
        Schema::table('cultural_institutions', function (Blueprint $table) {
            $table->boolean('can_receive_emails')->default(true)->after('last_email_sent_at');
            $table->text('admin_notes')->nullable()->after('can_receive_emails');
            $table->text('email_opt_out_reason')->nullable()->after('admin_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cultural_institutions', function (Blueprint $table) {
            $table->dropColumn(['can_receive_emails', 'admin_notes', 'email_opt_out_reason']);
        });
    }
};
