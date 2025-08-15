<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Template;
use App\Models\Theme;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Template::updateOrCreate(
            ['slug' => 'prestacao-servicos'],
            [
                'name' => 'Prestação de Serviços',
                'niche' => 'freelancer-ti',
                'fields_schema' => [
                    ['key' => 'contratante_nome', 'label' => 'Nome do Contratante', 'type' => 'text', 'required' => true],
                    ['key' => 'contratante_doc', 'label' => 'CPF/CNPJ Contratante', 'type' => 'text', 'required' => true],
                    ['key' => 'contratado_nome', 'label' => 'Nome do Contratado(a)', 'type' => 'text', 'required' => true],
                    ['key' => 'contratado_doc', 'label' => 'CPF/CNPJ Contratado(a)', 'type' => 'text', 'required' => true],
                    ['key' => 'objeto', 'label' => 'Objeto do Contrato', 'type' => 'textarea', 'required' => true],
                    ['key' => 'inicio', 'label' => 'Data de Início', 'type' => 'date', 'required' => true],
                    ['key' => 'termino', 'label' => 'Data de Término', 'type' => 'date', 'required' => true],
                    ['key' => 'valor', 'label' => 'Valor (R$)', 'type' => 'number', 'required' => true],
                    ['key' => 'pagamento', 'label' => 'Forma de Pagamento', 'type' => 'text', 'required' => true],
                    ['key' => 'aviso_previo_dias', 'label' => 'Aviso Prévio (dias)', 'type' => 'number', 'required' => true],
                    ['key' => 'cidade', 'label' => 'Cidade', 'type' => 'text', 'required' => true],
                    ['key' => 'data_assinatura', 'label' => 'Data da Assinatura', 'type' => 'date', 'required' => true],
                ],
                'blade_view' => 'contracts.services',
                'premium_only' => false,
            ]
        );

        Theme::updateOrCreate(
            ['slug' => 'basico'],
            ['name' => 'Básico', 'plan' => 'free', 'css' => 'body{font-family:Arial; font-size:12px; color:#222} h1{font-size:18px} h3{margin-top:16px}']
        );
        Theme::updateOrCreate(
            ['slug' => 'minimal'],
            ['name' => 'Minimal', 'plan' => 'free', 'css' => 'body{font-family:Helvetica; line-height:1.4} h1{letter-spacing:1px} ul{padding-left:16px}']
        );
        Theme::updateOrCreate(
            ['slug' => 'corporate'],
            ['name' => 'Corporate', 'plan' => 'premium', 'css' => 'body{font-family:Calibri; color:#1f2937} h1{color:#111827; border-bottom:1px solid #e5e7eb; padding-bottom:8px}']
        );
    }
}
