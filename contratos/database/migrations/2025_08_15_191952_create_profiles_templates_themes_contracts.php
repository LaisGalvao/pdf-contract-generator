<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $t) {
            $t->uuid('id')->primary();                         // supabase_user_id
            $t->string('email')->index();
            $t->string('plan')->default('free');               // free | premium
            $t->timestamps();
        });

        Schema::create('templates', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();                      // ex: prestacao-servicos
            $t->string('name');                                // "Prestação de Serviços"
            $t->string('niche')->index();                      // "freelancer-ti", "locacao", ...
            $t->jsonb('fields_schema');                        // [{ key, label, type, required }]
            $t->string('blade_view');                          // ex: contracts.services
            $t->boolean('premium_only')->default(false);
            $t->timestamps();
        });

        Schema::create('themes', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();                      // "basico", "minimal"
            $t->string('name');
            $t->enum('plan', ['free', 'premium'])->default('free');
            $t->text('css');                                   // CSS inline aplicado no Blade
            $t->timestamps();
        });

        Schema::create('contracts', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->uuid('user_id')->index();                      // supabase_user_id (profiles.id)
            $t->foreign('user_id')->references('id')->on('profiles')->cascadeOnDelete();
            $t->foreignId('template_id')->constrained()->cascadeOnDelete();
            $t->foreignId('theme_id')->nullable()->constrained()->nullOnDelete();
            $t->jsonb('data');                                 // valores preenchidos
            $t->string('pdf_path')->nullable();                // storage path
            $t->string('status')->default('generated');        // generated | signing | signed
            $t->jsonb('signature_meta')->nullable();           // ids/extras do D4Sign
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('contracts');
        Schema::dropIfExists('themes');
        Schema::dropIfExists('templates');
        Schema::dropIfExists('profiles');
    }
};
