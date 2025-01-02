<?php 

namespace App\Utils;

use Aws\Ses\SesClient;
use Aws\Exception\AwsException;

class AmazonSES {
  private $key;
  private $secret;

  public $charset = 'UTF-8';

  public $from = '';
  public $replyTo = [];
  public $to = [];
  public $cc = [];
  public $bcc= [];
  public $subject;
  public $body = '';
  public $htmlBody = '';
  public $mail;

  public function __construct() {
    if (!isset($_ENV['SES_ACCESS_KEY']) || !isset($_ENV['SES_SECRET_ACCESS_KEY'])) {
      throw new Exception('SES_ACCESS_KEY and SES_SECRET_ACCESS_KEY must be set in .env file'); 
    }

    $this->key = $_ENV['SES_ACCESS_KEY'];
    $this->secret = $_ENV['SES_SECRET_ACCESS_KEY'];
  }

  public function setFrom($from) {
    $this->from = $from;
  }

  public function addAddress($to) {
    if (is_array($to)) {
      $this->to = $to;
    } else {
      $this->to[] = $to;
    }
  }

  public function addCC($cc) {
    if (is_array($cc)) {
      $this->cc = $cc;
    } else {
      $this->cc[] = $cc;
    } 
  }

  public function addBCC($bcc) {
    if (is_array($bcc)) {
      $this->bcc = $bcc;
    } else {
      $this->bcc[] = $bcc;
    } 
  }

  public function setSubject($subject) {
    $this->subject = $subject;
  }

  public function setBody($body) {
    $this->body = $body;
  }

  public function addReplyTo($replyTo) {
    if (is_array($replyTo)) {
      $this->replyTo = $replyTo;
    } else {
      $this->replyTo[] = $replyTo;
    }
  }

  public function isHtml($html) {
    return $this->htmlBody = $html;
  }

  public function send() {
    $sesClient = new SesClient([
      'version' => 'latest',
      'region'  => 'us-west-2',
      'credentials' => array(
        'key' => $this->key, 
        'secret' => $this->secret,
      )
    ]);
  
    $result = $sesClient->sendEmail([
      'Destination' => [
        'ToAddresses' => $this->to,
        'CcAddresses' => $this->cc,
        'BccAddresses' => $this->bcc
      ],
      'ReplyToAddresses' => $this->replyTo,
      'Source' => $this->from,
      'Message' => [
        'Body' => [
          'Html' => [
            'Charset' => $this->charset,
            'Data' => $this->htmlBody,
          ],
          'Text' => [
            'Charset' => $this->charset,
            'Data' => $this->body,
          ],
        ],
        'Subject' => [
          'Charset' => $this->charset,
          'Data' => $this->subject,
        ],
      ],
    ]);
    return $result;
  }
}