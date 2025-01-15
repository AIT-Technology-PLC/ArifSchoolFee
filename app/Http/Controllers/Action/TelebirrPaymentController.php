<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;

class TelebirrPaymentController extends Controller
{
    private $publicKey;

    public function __construct(string $publicKey)
    {
        $this->publicKey = $publicKey;
    }

    public function handleNotification(string $notificationJson): void
    {
        $notificationData = json_decode($notificationJson, true);
        if ($notificationData === null) {
            throw new \Exception('Invalid JSON format');
        }

        // Extract encrypted data from the notification
        $encryptedData = $notificationData['encrypted_data'] ?? null;
        if ($encryptedData === null) {
            throw new \Exception('Encrypted data not found in the notification');
        }

        // Decrypt the payment data
        $decryptedPaymentData = $this->decryptPaymentData($encryptedData);

        // Process the decrypted payment data
        $this->processPaymentData($decryptedPaymentData);
    }

    private function decryptPaymentData(string $encryptedData): string
    {
        $publicKeyResource = $this->getPublicKeyResource();

        $decryptedData = '';
        $dataChunks = str_split(base64_decode($encryptedData), 256);
        foreach ($dataChunks as $chunk) {
            $partialDecrypted = '';
            $decryptionSuccess = openssl_public_decrypt($chunk, $partialDecrypted, $publicKeyResource, OPENSSL_PKCS1_PADDING);
            if (!$decryptionSuccess) {
                throw new \Exception('Decryption failed');
            }
            $decryptedData .= $partialDecrypted;
        }

        return $decryptedData;
    }

    private function processPaymentData(string $paymentData): void
    {
        // Implement your logic to process the payment data
    }

    private function getPublicKeyResource()
    {
        $pubPem = chunk_split($this->publicKey, 64, "\n");
        $pubPem = "-----BEGIN PUBLIC KEY-----\n" . $pubPem . "-----END PUBLIC KEY-----\n";
        $publicKeyResource = openssl_pkey_get_public($pubPem);
        if (!$publicKeyResource) {
            throw new \Exception('Invalid public key');
        }
        return $publicKeyResource;
    }

    public function handleRedirect($routeId)
    {
        return redirect()->route('collect-fees.show', $routeId)->with('successMessage', 'The transaction was made successfully.');
    }
}
