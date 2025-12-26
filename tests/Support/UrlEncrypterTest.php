<?php

declare(strict_types=1);

namespace Mperonnet\ImgProxy\Support;

use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class UrlEncrypterTest extends TestCase
{
    /**
     * @dataProvider validKeyProvider
     */
    public function testConstructWithValidKeys(string $key): void
    {
        $encrypter = new UrlEncrypter($key);
        $this->assertInstanceOf(UrlEncrypter::class, $encrypter);
    }

    /**
     * @dataProvider invalidKeyLengthProvider
     */
    public function testConstructWithInvalidKeyLengths(string $key): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid key length. The key should be either 32, 48, or 64 characters long in hex representation');

        new UrlEncrypter($key);
    }

    public function testEncryptReturnsValidString(): void
    {
        $key = str_repeat('a', 64); // 32 bytes (for AES-256)
        $encrypter = new UrlEncrypter($key);

        $url = 'https://example.com/image.jpg';
        $encrypted = $encrypter->encrypt($url);

        // The result should be a URL-safe base64 encoded string
        $this->assertIsString($encrypted);
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9\-_]+$/', $encrypted);
    }

    public function testDeterministicEncryptionResults(): void
    {
        $key = str_repeat('a', 64);
        $encrypter = new UrlEncrypter($key);

        $url = 'https://example.com/image.jpg';

        // Encrypting the same URL twice should result in the same string
        // due to the deterministic HMAC-based IV (enables CDN caching)
        $encrypted1 = $encrypter->encrypt($url);
        $encrypted2 = $encrypter->encrypt($url);

        $this->assertEquals($encrypted1, $encrypted2);
    }

    public function testDifferentUrlsProduceDifferentResults(): void
    {
        $key = str_repeat('a', 64);
        $encrypter = new UrlEncrypter($key);

        $url1 = 'https://example.com/image1.jpg';
        $url2 = 'https://example.com/image2.jpg';

        $encrypted1 = $encrypter->encrypt($url1);
        $encrypted2 = $encrypter->encrypt($url2);

        $this->assertNotEquals($encrypted1, $encrypted2);
    }

    public function testEncryptWithDifferentKeyLengths(): void
    {
        $url = 'https://example.com/image.jpg';

        // Test with AES-128-CBC (16-byte key)
        $key128 = str_repeat('a', 32); // 16 bytes in hex
        $encrypter128 = new UrlEncrypter($key128);
        $encrypted128 = $encrypter128->encrypt($url);
        $this->assertIsString($encrypted128);

        // Test with AES-192-CBC (24-byte key)
        $key192 = str_repeat('a', 48); // 24 bytes in hex
        $encrypter192 = new UrlEncrypter($key192);
        $encrypted192 = $encrypter192->encrypt($url);
        $this->assertIsString($encrypted192);

        // Test with AES-256-CBC (32-byte key)
        $key256 = str_repeat('a', 64); // 32 bytes in hex
        $encrypter256 = new UrlEncrypter($key256);
        $encrypted256 = $encrypter256->encrypt($url);
        $this->assertIsString($encrypted256);
    }

    /**
     * @return array<array<string>>
     */
    public function validKeyProvider(): array
    {
        return [
            [str_repeat('a', 32)], // 16 bytes for AES-128-CBC
            [str_repeat('a', 48)], // 24 bytes for AES-192-CBC
            [str_repeat('a', 64)], // 32 bytes for AES-256-CBC
            ['0123456789abcdef0123456789abcdef'], // Valid hex key
        ];
    }

    /**
     * @return array<array<string>>
     */
    public function invalidKeyLengthProvider(): array
    {
        return [
            ['aabbcc'], // Too short
            [str_repeat('a', 30)], // Not a valid length (30 chars)
            [str_repeat('a', 40)], // Not a valid length (40 chars)
            [str_repeat('a', 50)], // Not a valid length (50 chars)
            [str_repeat('a', 70)], // Not a valid length (70 chars)
        ];
    }
}
