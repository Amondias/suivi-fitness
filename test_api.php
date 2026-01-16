<?php
/**
 * TESTS DE L'API SUBSCRIPTION PLANS
 * Exécutez ce fichier avec: php test_api.php
 */

// URL de base de l'API
$baseUrl = 'http://localhost/examenPhp/suivi-fitness/public';

// Fonction pour faire des requêtes HTTP
function makeRequest($method, $endpoint, $data = null, $auth = false) {
    $url = "http://localhost/examenPhp/suivi-fitness/public" . $endpoint;
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'code' => $httpCode,
        'data' => json_decode($response, true),
        'raw' => $response
    ];
}

echo "========================================\n";
echo "🧪 TEST API SUBSCRIPTION PLANS\n";
echo "========================================\n\n";

// TEST 1: GET tous les plans
echo "✅ TEST 1: Récupérer tous les plans (GET /api/plans)\n";
$result = makeRequest('GET', '/api/plans');
echo "Code HTTP: " . $result['code'] . "\n";
echo "Réponse:\n";
echo json_encode($result['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

// TEST 2: GET un plan spécifique
echo "✅ TEST 2: Récupérer un plan avec ID=1 (GET /api/plans/1)\n";
$result = makeRequest('GET', '/api/plans/1');
echo "Code HTTP: " . $result['code'] . "\n";
echo "Réponse:\n";
echo json_encode($result['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

// TEST 3: GET un plan inexistant
echo "✅ TEST 3: Essayer de récupérer un plan inexistant (GET /api/plans/99999)\n";
$result = makeRequest('GET', '/api/plans/99999');
echo "Code HTTP: " . $result['code'] . "\n";
echo "Réponse:\n";
echo json_encode($result['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

// TEST 4: POST - Créer un plan (nécessite authentification)
echo "⚠️ TEST 4: Créer un nouveau plan (POST /api/plans) - NÉCESSITE AUTH\n";
$newPlan = [
    'name' => 'Plan Test ' . time(),
    'description' => 'Plan de test pour démonstration',
    'duration_months' => 3,
    'price' => 29.99,
    'features' => 'Feature 1, Feature 2, Feature 3',
    'is_active' => true
];
$result = makeRequest('POST', '/api/plans', $newPlan);
echo "Code HTTP: " . $result['code'] . "\n";
echo "Réponse:\n";
echo json_encode($result['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

echo "========================================\n";
echo "📝 NOTES\n";
echo "========================================\n";
echo "✓ Les tests GET fonctionnent sans authentification\n";
echo "✗ Les tests POST/PUT/DELETE nécessitent une authentification\n";
echo "✓ Vérifiez que XAMPP est actif et la base de données existe\n";
?>
