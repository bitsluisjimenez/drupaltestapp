(function ($, Drupal, drupalSettings) {

  /**
   * Add new command for reading a message.
   */
  Drupal.AjaxCommands.prototype.displayMessage = function (ajax, response, status) {
    //M.toast({ html: 'I am a toast!' })
    console.log(response);
    //var message = response.subject;
    var $toastContent = $('<span>' + response.content + '</span>').add($('<button class="btn-flat toast-action">Undo</button>'));
    //Materialize.toast($toastContent, 5000);
    console.log(response.content);
  }
})(jQuery, Drupal, drupalSettings);