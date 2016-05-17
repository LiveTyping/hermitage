<?php

namespace livetyping\hermitage\app\signer;

/**
 * Class Signer
 *
 * @package livetyping\hermitage\app\signer
 */
class Signer
{
    /** @var string */
    protected $algorithm;

    /**
     * Signer constructor.
     *
     * @param string $algorithm
     */
    public function __construct(string $algorithm = 'sha256')
    {
        $this->algorithm = $algorithm;
    }

    /**
     * @param string $data
     * @param string $secret
     *
     * @return string
     */
    public function sign(string $data, string $secret): string
    {
        $signature = hash_hmac($this->algorithm, $data, $secret);

        return $signature;
    }

    /**
     * @param string $signature
     * @param string $data
     * @param string $secret
     *
     * @return bool
     */
    public function verify(string $signature, string $data, string $secret): bool
    {
        $compareSignature = $this->sign($data, $secret);

        return $compareSignature === $signature;
    }
}
