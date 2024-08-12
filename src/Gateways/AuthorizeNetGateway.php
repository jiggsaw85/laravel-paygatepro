<?php

namespace JiggsawPhp\PayGatePro\Gateways;

use JiggsawPhp\PayGatePro\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Facades\Log;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class AuthorizeNetGateway implements PaymentGatewayInterface
{
    protected string $apiLoginId;
    protected string $transactionKey;

    public function __construct()
    {
        $this->apiLoginId = config('payment.authorize_net.api_login_id');
        $this->transactionKey = config('payment.authorize_net.transaction_key');
    }

    /**
     * Charge the amount using Authorize.Net.
     *
     * @param float $amount
     * @param string $currency
     * @param array $options
     * @return AnetAPI\TransactionResponseType|null
     */
    public function charge(float $amount, string $currency, array $options = []): ?AnetAPI\TransactionResponseType
    {
        // Setup and execute transaction (same as before)
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($this->apiLoginId);
        $merchantAuthentication->setTransactionKey($this->transactionKey);

        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($options['card_number']);
        $creditCard->setExpirationDate($options['expiration_date']);
        $creditCard->setCardCode($options['cvv']);

        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);

        $transactionRequest = new AnetAPI\TransactionRequestType();
        $transactionRequest->setTransactionType("authCaptureTransaction");
        $transactionRequest->setAmount($amount);
        $transactionRequest->setPayment($payment);

        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setTransactionRequest($transactionRequest);

        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if ($response != null && $response->getMessages()->getResultCode() == "Ok") {
            $tresponse = $response->getTransactionResponse();
            if ($tresponse != null && $tresponse->getResponseCode() == "1") {
                return $tresponse;
            } else {
                Log::error("Charge Error: " . $tresponse->getErrors()[0]->getErrorText());
            }
        } else {
            Log::error("Charge Failed: " . $response->getMessages()->getMessage()[0]->getText());
        }

        return null;
    }

    /**
     * Refund a transaction using Authorize.Net.
     *
     * @param string $transactionId
     * @param float $amount
     * @param array $options
     * @return AnetAPI\TransactionResponseType|null
     */
    public function refund(string $transactionId, float $amount, array $options = []): ?AnetAPI\TransactionResponseType
    {
        // Setup and execute refund transaction (same as before)
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($this->apiLoginId);
        $merchantAuthentication->setTransactionKey($this->transactionKey);

        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($options['card_number']);
        $creditCard->setExpirationDate("XXXX");

        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);

        $transactionRequest = new AnetAPI\TransactionRequestType();
        $transactionRequest->setTransactionType("refundTransaction");
        $transactionRequest->setAmount($amount);
        $transactionRequest->setPayment($payment);
        $transactionRequest->setRefTransId($transactionId);

        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setTransactionRequest($transactionRequest);

        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if ($response != null && $response->getMessages()->getResultCode() == "Ok") {
            $tresponse = $response->getTransactionResponse();
            if ($tresponse != null && $tresponse->getResponseCode() == "1") {
                return $tresponse;
            } else {
                Log::error("Refund Error: " . $tresponse->getErrors()[0]->getErrorText());
            }
        } else {
            Log::error("Refund Failed: " . $response->getMessages()->getMessage()[0]->getText());
        }

        return null;
    }
}
