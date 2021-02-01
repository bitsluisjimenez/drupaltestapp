<?php

namespace Drupal\user_profile\Ajax;

use Drupal\Core\Ajax\CommandInterface;

class DisplayMessageCommand implements CommandInterface {
  protected $message;
  
  // Constructs a DisplayMessageCommand object.
  public function __construct($message) {
    $this->message = $message;
  }
  
  // Implements Drupal\Core\Ajax\CommandInterface:render().
  public function render() {
    return array(
      'command' => 'displayMessage',  //javascript function
      'mid' => $this->message->mid,
      'subject' => $this->message->subject,
      'content' => $this->message->content,
    );
  }
}