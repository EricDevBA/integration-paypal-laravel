<?php

namespace App\Http\Controllers;

use Faker\Provider\sk_SK\Payment;
use Illuminate\Http\Request;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PhpParser\Node\Stmt\Foreach_;

class PagamentoController extends Controller
{

    public function __construct()
    {
        $paypal_conf = \Config::get('paypal');


        $this->_api_context = new ApiContext(new OAuthTokenCredential(

            $paypal_conf['client_id'],
            $paypal_conf['secret'])


        );

        $this->_api_context->setConfig($paypal_conf['settings']);

    }


    public function pagarComPaypal(Request $request)
    {

        $pagador = new Payer();

        $pagador->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName('Item_1')->setCurrency('BRL')->setQuantity(1)->setPrice($request->get('amount'));
        $lista_itens = new ItemList();
        $lista_itens->setItems(arraty($item_1));
        $valor = new Amount();
        $valor->setCurrency('BRL')->setTotal($request->get('amount'));


        $transacao = new Transaction();

        $transacao->setAmount($valor)->setItemList($lista_itens)->setDescription('Your transaction description');

        $urls_redirecionamento = new RedirectUrls();

        $urls_redirecionamento->setReturnUrl(URL::route('status'))->setCancelUrl(URL::route('status'));

        $pagamento = new Payment();

        $pagamento->setIntent('Sale')->setPayer($pagador)->setRedirectUrls($urls_redirecionamento)->setTransactions(array($transacao));

            try{

                $pagamento->create($this->_api_context);

            } catch(\Paypal\Exception\PayPalConnectionException $e) {

                if (\Config::get('app_debug')){\

                    Session::put('error','Tempo Limite de Conexão Excedido');
                        return RedirectUrls::route('home');


                }else{\
                    Session::put('error', 'Serviço fora do ar');
                        return RedirectUrls::route('home');

                }

    }

    foreach($pagamento->getLinks() as $link) {
            if($link->getRel() == 'approval_url') {
                $url_redirecionar = $link->getHref();
                break;
            }

    }

    Session::put('pagamento_paypal_id', $pagamento->getId());

    if(isset($url_redirecionar));


}

}
