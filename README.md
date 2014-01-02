#li3_mandrill

[Mandrill](https://mandrillapp.com/) Wrapper for LI3

##Installation

Get the library code:

    $ cd /path/to/app/libraries
    $ git clone https://github.com/Knodes/li3_mandrill.git

Make sure it's added on `app/config/bootstrap/libraries.php` with the path included:

    Libraries::add('li3_mandrill', array(
      'includePath' => true,
    ));


##Configuration
Make sure your environments have the Mandrill apikey setup as:
- mandrill.apikey

##Usage
li3\_mandrill supplies you with a static class (li3_mandrill\core\Li3Mandrill) that's a basic wrapper to all methods the [Mandrill PHP Client](https://bitbucket.org/mailchimp/mandrill-api-php/) accepts.

On top of that it manages the instance of the Mandrill object automatically, so there's no need to create it manually.


##Example

    use li3_mandrill\core\Li3Mandrill;

    ...
    //instead of Mandrill->messages->send->( $message, $async ... ) use:
    $res = Li3Mandrill::messages( 'send', $message, $async ... );





