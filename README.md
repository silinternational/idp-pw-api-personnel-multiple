# idp-pw-api-personnel-multiple
Personnel backend component for IdP PW API for using multiple backends.

## Example Usage

    use Sil\IdpPw\Common\Personnel\IdBroker;
    use Sil\IdpPw\Common\Personnel\Insite;
    use Sil\IdpPw\Common\Personnel\Multiple\Multiple;
    
    // ...
    
    $multiple = new Multiple([
        'personnelBackendConfig' => [
            [
                'class'                 => IdBroker::class,
                'baseUrl'               => 'https://broker.url',
                'accessToken'           => 'abc123',
                'assertValidBrokerIp'   => true,
                'validIpRanges'         => ['10.0.20.0/16'],
            ],
            [
                'class' => Insite::class,
                'insitePeopleSearchBaseUrl'     => 'https://search.url',
                'insitePeopleSearchApiKey'      => 'abc123',
                'insitePeopleSearchApiSecret'   => 'abc123',
            ],
        ],
    ]);

## It Matters Which order you define Personnel Backends

When iterating through personnel backends, each subsequent backend will override the PersonnelUser attributes
found from previous backends.

If a user is not found in any one of the backends a NotFoundException is thrown.

If a user is found in multiple backends but the employeeId does not match for them an Exception is thrown. 

