<?php
namespace OnSefy\Laravel\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use OnSefy\Laravel\ErrorMessages;
use OnSefy\Laravel\Exceptions\ApiRequestException;
use OnSefy\Laravel\OnSefyService;

class OnSefy implements ValidationRule
{
    protected OnSefyService $service;

    /** @var array<string> */
    protected array $parameters;

    /**
     * @param  OnSefyService  $service
     * @param  array<string>  $parameters
     */
    public function __construct(OnSefyService $service, array $parameters = [])
    {
        $this->service = $service;
        $this->parameters = $parameters;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail(ErrorMessages::get('onsefy', $attribute));
            return;
        }

        try {
            $payload = [];

            if ($attribute === 'email') {
                $payload['email'] = $value;
            } elseif ($attribute === 'ip') {
                $payload['ip'] = $value;
            } elseif ($attribute === 'phone') {
                $payload['phone'] = $value;
            } elseif ($attribute === 'name') {
                $payload['name'] = $value;
            } elseif ($attribute === 'user_agent') {
                $payload['user_agent'] = $value;
            } else {
                $payload['email'] = $value;
            }

            $result = $this->service->validateUser($payload);

            // Fallback: check summary risk or parameter-level errors
            $risk = $result['summary']['risk_level'] ?? 10;

            if ($risk > 0) {
                $fail(ErrorMessages::get('high_risk', $attribute));
                return;
            }

            if (isset($result['parameters'][$attribute]['is_valid']) && ! $result['parameters'][$attribute]['is_valid']) {
                $fail($result['parameters'][$attribute]['message'] ?? ErrorMessages::get('onsefy', $attribute));
            }
        } catch (ApiRequestException $e) {
            throw $e;
        } catch (\Exception $e) {
            $fail(ErrorMessages::get('validation_failed', $attribute) . ': ' . $e->getMessage());
        }
    }
}
