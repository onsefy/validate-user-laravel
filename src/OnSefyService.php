<?php
namespace OnSefy\Laravel;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use OnSefy\Laravel\Exceptions\ApiRequestException;

class OnSefyService
{
    protected string $planType;
    protected string $apiKey;
    protected string $serviceId;

    public function __construct()
    {
        $apiKey = config('onsefy.api_key');
        $serviceId = config('onsefy.service_id');
        $planType = config('onsefy.plan_type');

        if (! is_string($apiKey)) {
            throw new InvalidArgumentException('OnSefy API key is not set.');
        }

        if (! is_string($serviceId)) {
            throw new InvalidArgumentException('OnSefy service ID is not set.');
        }

        $this->apiKey = $apiKey;
        $this->serviceId = $serviceId;
        $this->planType = $planType ?? 'free';
    }

    /**
     * Validate user identity and get fraud signals
     *
     * @param  array{
     *     email?: string,
     *     phone?: string,
     *     ip?: string,
     *     name?: string,
     *     user_agent?: string
     * } $payload
     * @return array<string, mixed>
     */
    public function validateUser(array $payload): array
    {
        if($this->planType !== 'free'){
            $endpoint= 'https://api.onsefy.com/v1/validate/user';
        } else {
            $endpoint= 'https://free-api.onsefy.com/v1/validate/user';
        }
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
            'X-Service-Id' => $this->serviceId,
            'User-Agent' => 'OnSefy-Laravel/1.0.0',
            'Content-Type' => 'application/json',
        ])->post($endpoint, $payload);

        if (! $response->successful()) {
            throw new ApiRequestException("OnSefy API request failed: " . $response->body());
        }

        $data = $response->json();

        if (! is_array($data)) {
            throw new ApiRequestException('Invalid response from OnSefy API');
        }

        return $data;
    }
}
