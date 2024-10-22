<?php 

namespace App\Services\Api\V1;


use LINE\Clients\MessagingApi\Api\MessagingApiApi;
use LINE\Clients\MessagingApi\Model\ReplyMessageRequest;
use LINE\Clients\MessagingApi\Model\TextMessage;
use LINE\Constants\HTTPHeader;
use LINE\Parser\EventRequestParser;
use LINE\Webhook\Model\MessageEvent;
use LINE\Parser\Exception\InvalidEventRequestException;
use LINE\Parser\Exception\InvalidSignatureException;
use LINE\Webhook\Model\TextMessageContent;
use LINE\Clients\MessagingApi\Configuration;



class LinemesService
{

     public function webhook($req){
        $channelToken = config('linemes.channel_access_token');
        $config = new Configuration();
        dd($config);
        $config->setAccessToken($channelToken);
        $bot = new MessagingApiApi(new \GuzzleHttp\Client(), $config);
        $signature = $req->header(HTTPHeader::LINE_SIGNATURE);
        if (empty($signature)) {
            return abort(400, 'Bad Request');
        }

        // Check request with signature and parse request
        try {
            $secret = config('linemes.channel_secret');
            $parsedEvents = EventRequestParser::parseEventRequest($req->getContent(), $secret, $signature);
        } catch (InvalidSignatureException $e) {
            return abort(400, 'Bad signature');
        } catch (InvalidEventRequestException $e) {
            return abort(400, "Invalid event request");
        }

        foreach ($parsedEvents->getEvents() as $event) {
            if (!($event instanceof MessageEvent)) {
                // $logger->info('Non message event has come');
                continue;
            }

            $message = $event->getMessage();
            if (!($message instanceof TextMessageContent)) {
                // $logger->info('Non text message has come');
                continue;
            }

            $replyText = $message->getText();
            $userId = $event->getSource()->getUserId();
            $bot->replyMessage(new ReplyMessageRequest([
                'replyToken' => $event->getReplyToken(),
                'messages' => [
                    (new TextMessage(['text' => $replyText]))->setType('text'),
                ],
            ]));
        }
        return response('ok');

        // $res->withStatus(200, 'OK');
        // return $res;
    }
}