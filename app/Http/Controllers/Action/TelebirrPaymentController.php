<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentTransaction;
use App\Models\FeePayment;
use Carbon\Carbon;

class TelebirrPaymentController extends Controller
{
    private $privateKey;

    public function __construct()
    {
        $this->privateKey = env('TELEBIRR_PRIVATE_KEY');
    }

    public function handleNotification(string $notificationJson): void
    {
        $notificationData = json_decode($notificationJson, true);

        if ($notificationData === null) {
            Log::error("Invalid JSON format: $notificationJson");
            throw new \Exception('Invalid JSON format');
        }

        $encryptedData = $notificationData['encrypted_data'] ?? null;
        if (!$encryptedData) {
            Log::error('Encrypted data not found in the notification');
            throw new \Exception('Encrypted data not found');
        }

        try {
            $decryptedPaymentData = $this->decryptPaymentData($encryptedData);
        } catch (\Exception $e) {
            Log::error('Decryption failed: ' . $e->getMessage());
            throw $e;
        }

        $this->processPaymentData($decryptedPaymentData);
    }

    private function decryptPaymentData(string $encryptedData): string
    {
        $publicKeyResource = $this->getPrivateKeyResource();
        $decryptedData = '';
        $dataChunks = str_split(base64_decode($encryptedData), 256);

        foreach ($dataChunks as $chunk) {
            $partialDecrypted = '';
            $decryptionSuccess = openssl_public_decrypt($chunk, $partialDecrypted, $publicKeyResource, OPENSSL_PKCS1_PADDING);
            if (!$decryptionSuccess) {
                throw new \Exception('Decryption failed for chunk');
            }
            $decryptedData .= $partialDecrypted;
        }

        return $decryptedData;
    }

    private function getPrivateKeyResource()
    {
        $privateKeyPem = chunk_split($this->privateKey, 64, "\n");
        $privateKeyPem = "-----BEGIN PRIVATE KEY-----\n" . $privateKeyPem . "-----END PRIVATE KEY-----\n";
        
        // Load the private key resource for decryption
        $privateKeyResource = openssl_pkey_get_private($privateKeyPem);
        
        if (!$privateKeyResource) {
            throw new \Exception('Invalid private key');
        }
    
        return $privateKeyResource;
    }

    private function processPaymentData(string $paymentData)
    {
        if (!isset($paymentData['merch_order_id']) || !isset($paymentData['trade_status'])) {
            Log::error('Invalid payment data structure: ' . json_encode($paymentData));
            return response()->json(['message' => 'Invalid payment data'], 400);
        }
    
        // Find the pending transaction with the session ID
        $transaction = PaymentTransaction::pending()
            ->telebirr()->where('session_id', $paymentData['merch_order_id'])->first();

        if (!$transaction) {
            Log::error('Transaction not found for Order Id: ' . $paymentData['merch_order_id']);
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        DB::beginTransaction();

        try {
            switch ($paymentData['trade_status']) {
                case 'Completed':
                    $transaction->status = 'success';
                    $paymentData = json_decode($transaction->payment_data, true);

                    $feePayment = new FeePayment();
                    $feePayment->company_id = $transaction->assignFeeMaster->company_id; 
                    $feePayment->student_id = $transaction->assignFeeMaster->student_id;
                    $transaction->student_history_id = $transaction->assignFeeMaster->student->latestStudentHistoryId();
                    $feePayment->assign_fee_master_id = $transaction->assignFeeMaster->id;
                    $feePayment->payment_mode = 'Telebirr';
                    $transaction->fee_discount_id = $paymentData['fee_discount_id'] ?? null;
                    $transaction->payment_date = Carbon::parse($paymentData['payment_date']);
                    $transaction->amount = $paymentData['amount'];
                    $transaction->fine_amount = $paymentData['fine_amount'] ?? 0;
                    $transaction->discount_amount = $paymentData['discount_amount'] ?? 0;
                    $transaction->commission_amount = $paymentData['commission_amount'] ?? 0;
                    $transaction->discount_month = (isset($paymentData['discount_amount']) && $paymentData['discount_amount'] > 0) || isset($paymentData['fee_discount_id']) ? Carbon::now() : null;

                    break;
                default:
                    return response()->json(['message' => 'Transaction status is unknown'], 400);
            }

            $transaction->save();
            DB::commit();
        } 
        catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating payment transaction or fee payment: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing payment'], 500);
        }

        // Return success response after processing the webhook
        return response()->json(['message' => 'Webhook processed successfully'], 200);
    }

    public function handleRedirect($routeId)
    {
        return redirect()->route('collect-fees.show', $routeId)->with('successMessage', 'The transaction was made successfully.');
    }
}
