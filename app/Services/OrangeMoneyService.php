<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrangeMoneyService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $secretKey;
    protected string $merchantId;
    protected ?string $webhookSecret;

    public function __construct()
    {
        $this->baseUrl = config('services.orange.base_url') ?? env('ORANGE_MONEY_BASE_URL');
        $this->apiKey = config('services.orange.api_key') ?? env('ORANGE_MONEY_API_KEY');
        $this->secretKey = config('services.orange.secret_key') ?? env('ORANGE_MONEY_SECRET_KEY');
        $this->merchantId = config('services.orange.merchant_id') ?? env('ORANGE_MONEY_MERCHANT_ID');
        $this->webhookSecret = env('ORANGE_MONEY_WEBHOOK_SECRET');
    }

    // Génère token OAuth
    public function generateToken(): string
    {
        $resp = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($this->apiKey . ':' . $this->secretKey),
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->asForm()->post("{$this->baseUrl}/token", [
            'grant_type' => 'client_credentials',
        ]);

        if ($resp->failed()) {
            Log::error('Orange token error', ['body' => $resp->body()]);
            throw new \Exception('Impossible d’obtenir le token Orange Money: ' . $resp->body());
        }

        $json = $resp->json();
        return $json['access_token'] ?? throw new \Exception('Token absent dans la réponse');
    }

    // Créer un paiement
    public function createPayment(float $montant, string $phone, string $reference): array
    {
        $token = $this->generateToken();

        $payload = [
            'merchantId' => $this->merchantId,
            'amount' => $montant,
            'currency' => 'XOF',
            'externalId' => $reference,
            'payer' => [
                'partyIdType' => 'MSISDN',
                'partyId' => $phone,
            ],
            'payerMessage' => 'Paiement formation',
            'payeeNote' => 'Paiement ISI SUPTECH',
            'callbackUrl' => route('orange.callback'),
        ];

        $endpoint = "{$this->baseUrl}/payment"; // adapter selon doc

        $resp = Http::withToken($token)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($endpoint, $payload);

        if ($resp->failed()) {
            Log::error('Orange createPayment failed', ['body' => $resp->body()]);
            throw new \Exception('Erreur création paiement Orange Money: ' . $resp->body());
        }

        return $resp->json();
    }

    // Vérifier signature du callback
    public function verifyCallbackSignature(string $rawBody, ?string $signatureHeader): bool
    {
        if (!$this->webhookSecret || !$signatureHeader) return false;

        $expected = hash_hmac('sha256', $rawBody, $this->webhookSecret);

        return hash_equals($expected, $signatureHeader);
    }
}
