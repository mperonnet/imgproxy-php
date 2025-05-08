<?php

declare(strict_types=1);

namespace Onliner\ImgProxy\Support;

/**
 * URL encrypter for ImgProxy Pro
 * 
 * This class is used to encrypt source URLs for ImgProxy Pro.
 * It implements the AES-CBC encryption algorithm as described in the ImgProxy documentation.
 */
class UrlEncrypter
{
    private string $key;

    /**
     * @param string $key Hex-encoded encryption key. Should be either 32, 48, or 64 characters long
     *                    for AES-128-CBC, AES-192-CBC, or AES-256-CBC respectively.
     */
    public function __construct(string $key)
    {
        $keyBin = hex2bin($key);
        
        if ($keyBin === false) {
            throw new \InvalidArgumentException('Invalid encryption key');
        }
        
        $keyLength = strlen($keyBin);
        if ($keyLength !== 16 && $keyLength !== 24 && $keyLength !== 32) {
            throw new \InvalidArgumentException(
                'Invalid key length. The key should be either 32, 48, or 64 characters long in hex representation'
            );
        }
        
        $this->key = $keyBin;
    }

    /**
     * Encrypts the URL with AES-CBC algorithm.
     *
     * @param string $url The URL to encrypt
     *
     * @return string URL-safe Base64 encoded encrypted URL
     */
    public function encrypt(string $url): string
    {
        // Pad the URL with PKCS#7 padding
        $blockSize = 16;
        $padLength = $blockSize - (strlen($url) % $blockSize);
        $paddedUrl = $url . str_repeat(chr($padLength), $padLength);
        
        // Generate a 16-byte IV
        $iv = openssl_random_pseudo_bytes(16);
        
        // Encrypt the URL
        $encryptedUrl = openssl_encrypt($paddedUrl, $this->getCipherMethod(), $this->key, OPENSSL_RAW_DATA, $iv);
        
        if ($encryptedUrl === false) {
            throw new \RuntimeException('Failed to encrypt URL: ' . openssl_error_string());
        }
        
        // Combine IV and encrypted URL
        $result = $iv . $encryptedUrl;
        
        // Return URL-safe Base64 encoded result
        return rtrim(strtr(base64_encode($result), '+/', '-_'), '=');
    }

    /**
     * Returns the cipher method based on the key length.
     *
     * @return string
     */
    private function getCipherMethod(): string
    {
        switch (strlen($this->key)) {
            case 16:
                return 'AES-128-CBC';
            case 24:
                return 'AES-192-CBC';
            case 32:
                return 'AES-256-CBC';
            default:
                throw new \RuntimeException('Unexpected key length');
        }
    }
}