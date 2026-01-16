<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\SubscriptionPlans;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubscriptionPlansTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * TEST POST - Créer un plan d'abonnement
     */
    public function test_create_plan()
    {
        $data = [
            'name' => 'Premium',
            'description' => 'Accès illimité à tous les équipements et cours',
            'duration_months' => 6,
            'price' => 150000,
            'features' => 'Accès illimité, 2 séances coaching/mois, Vestiaire privé, Parking gratuit',
            'is_active' => true
        ];

        $response = $this->postJson('/api/plans', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Plan créé avec succès'
                 ]);

        $this->assertDatabaseHas('subscription_plans', [
            'name' => 'Premium'
        ]);
    }

    /**
     * TEST GET - Récupérer tous les plans
     */
    public function test_get_all_plans()
    {
        SubscriptionPlans::factory()->create(['is_active' => true]);

        $response = $this->getJson('/api/plans');

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);
    }

    /**
     * TEST GET - Récupérer un plan spécifique
     */
    public function test_get_single_plan()
    {
        $plan = SubscriptionPlans::factory()->create();

        $response = $this->getJson("/api/plans/{$plan->id}");

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);
    }

    /**
     * TEST PUT - Modifier un plan
     */
    public function test_update_plan()
    {
        $plan = SubscriptionPlans::factory()->create();

        $data = [
            'name' => 'Premium Plus',
            'price' => 200000
        ];

        $response = $this->putJson("/api/plans/{$plan->id}", $data);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);
    }

    /**
     * TEST DELETE - Supprimer un plan
     */
    public function test_delete_plan()
    {
        $plan = SubscriptionPlans::factory()->create();

        $response = $this->deleteJson("/api/plans/{$plan->id}");

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('subscription_plans', ['id' => $plan->id]);
    }

    /**
     * TEST - Validation error
     */
    public function test_create_plan_validation_error()
    {
        $data = [
            'name' => '',  // Requis
            'description' => 'Test',
            'duration_months' => -1,  // Min 1
            'price' => -50  // Min 0
        ];

        $response = $this->postJson('/api/plans', $data);

        $response->assertStatus(422)
                 ->assertJson(['success' => false]);
    }
}
