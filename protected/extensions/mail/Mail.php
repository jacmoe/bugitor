<?php
/**
 * Mail class file.
 *
 * @author Jonah Turnquist <poppitypop@gmail.com>
 * @link http://www.yiiframework.com/
 */

/**
 * Mail is an application component used for sending email.
 *
 * You may configure it as so.  Check the public attributes and setter methods of this 
 * class for more options.  You need to 
 * <pre>
 * return array(
 * 	...
 * 	'import=>array(
 * 		...
 * 		'ext.mail.Message',	//Path may be different.  Message class should be set to autoload (unless you want to load it manually)
 * 	)
 * 	'components'=>array(
 * 		'mail'=>array(
 * 			'class' => 'ext.mail.Mail',	//set to the path of the extension
 * 			'transportType' => 'php',
 * 			'viewPath' => 'application.views.mail',
 * 			'debug' => false
 * 		),
 * 		...
 * 	)
 * );
 * </pre>
 * 
 * Now in your layout file you may want to include the MailDebug widget if you wish to use debug mode.
 * The MailDebug widget prints any emails sent directly onto the browser instead of actually emailing them.
 * Include the MailDebug widget like so (you should probably have it above the page content):
 * <pre><?php $this->widget('ext.mail.MailDebug'); ?></pre>
 * 
 * Example usage:
 * <pre>
 * $message = new Message;
 * $message->setBody('Message content here with HTML', 'text/html');
 * $message->subject = 'My Subject';
 * $message->addTo('johnDoe@domain.com');
 * $message->from = Yii::app()->params['adminEmail'];
 * Yii::app()->mail->send($message);
 * </pre>
 * 
 * @author Jonah Turnquist <poppitypop@gmail.com>
 */
class Mail extends CApplicationComponent
{
	/**
	* @var bool whether to send mail in debug mode.  When in debug mode, emails will not actually send but
	* will be flashed instead with CWebUser::setFlash().  Defaults to false
	*/
	public $debug = false;
	
	/**
	* @var string the delivery type.  Can be either 'php' or 'smtp'.  When using 'php', PHP's mail() function will be used
	* Defaults to 'php'
	*/
	public $transportType = 'php';
	
	/**
	* @var string the path to the location where mail views are stored.  Defaults to 'application.views.mail'
	*/
	public $viewPath = 'application.views.mail';
	
	/**
	* @var string options specific to the transport type being used.
	* To set options for STMP, set this attribute to an array where the keys are the option names and the values are their
	* values.  Possible options for SMTP are:
	* <ul>
	* 	<li>host</li>
	* 	<li>username</li>
	* 	<li>password</li>
	* 	<li>port</li>
	* 	<li>encryption</li>
	* 	<li>timeout</li>
	* 	<li>extensionHandlers</li>
	* </ul>
	* See the SwiftMailer documentaion for the option meanings.
	*/
    public $transportOptions;
    
    /**
    * @var mixed Holds the SwiftMailer transport
    */
    protected $transport;
    
    /**
    * @var mixed Holds the SwiftMailer mailer
    */
    protected $mailer;
    
    private static $registeredScripts=false;
    
    /**
    * Calls the registerScripts() method 
    */
 	public function init() {
 		$this->registerScripts();
 		parent::init();	
	}
	
	/**
	* Send the given Message like it would be sent in a mail client.
	* 
	* All recipients (with the exception of Bcc) will be able to see the other
	* recipients this message was sent to.
	* 
	* If you need to send to each recipient without disclosing details about the
	* other recipients see {@link batchSend()}.
	* 
	* Recipient/sender data will be retreived from the Message object.
	* 
	* The return value is the number of recipients who were accepted for
	* delivery.
	* 
	* @param Message $message
	* @param array &$failedRecipients, optional
	* @return int
	* @see batchSend()
	*/
	public function send(Message $message, &$failedRecipients = null) {
		if ($this->debug===true) {
			return $this->debug($message);
		}
		return $this->getMailer()->send($message->message, $failedRecipients);
	}
    
	/**
	* Send the given Message to all recipients individually.
	* 
	* This differs from {@link send()} in the way headers are presented to the
	* recipient.  The only recipient in the "To:" field will be the individual
	* recipient it was sent to.
	* 
	* If an iterator is provided, recipients will be read from the iterator
	* one-by-one, otherwise recipient data will be retreived from the Message
	* object.
	* 
	* Sender information is always read from the Message object.
	* 
	* The return value is the number of recipients who were accepted for
	* delivery.
	* 
	* @param Message $message
	* @param array &$failedRecipients, optional
	* @param Swift_Mailer_RecipientIterator $it, optional
	* @return int
	* @see send()
	*/
	public function batchSend(Message $message, &$failedRecipients = null, Swift_Mailer_RecipientIterator $it = null) {
		if ($this->debug===true) {
			return $this->debug($message);
		}
		return $this->getMailer()->batchSend($message->message, $failedRecipients, $it);
	}
	
	/**
	* Sends a message in an extremly simple but less extensive way
	* 
	* @param mixed from address.  Either string or array, where the key is the address and the value the name
	* @param mixed Either string or array, where the keys are the addresses and the values the namen
	* @param string subject
	* @param string body
	*/
    public function sendSimple($from, $to, $subject, $body) {
        $message = new Message;
        $message->setSubject($subject)
          ->setFrom($from)
          ->setTo($to)
          ->setBody($body, 'text/html');
          
 		if ($this->debug===true) {
			return $this->debug($message);
		}
        return $this->getMailer()->send($message);
    }
    
    /**
    * This method is called internally instead of sending a message when debug mode it on.
    * 
    * @param Message $message
    * @return int The number of people the message was sent to
    */
    protected function debug(Message $message) {
		$debug = Yii::app()->controller->renderPartial('ext.mail.views.debug', array('message'=>$message), true);
		Yii::app()->user->setFlash('mail', $debug);
		return count($message->to);
	}
    
	/**
	* Gets the SwiftMailer transport class instance, initializing it if it has not been created yet
	* @return mixed Swift_MailTransport or Swift_SmtpTransport
	*/
    public function getTransport() {
    	if ($this->transport===null) {
    		switch ($this->transportType) {
    			case 'php':
    				$this->transport = Swift_MailTransport::newInstance();
    				if ($this->transportOptions !== null)
    					$this->transport->setExtraParams($this->transportOptions);
    				break;
    			case 'smtp':
    				$this->transport = Swift_SmtpTransport::newInstance();
    				foreach ($this->transportOptions as $option => $value)
    					$this->transport->{'set'.ucfirst($option)}($value); //sets option with the setter method
    				break;    			
			}
		}
    	
    	return $this->transport;
	}
	
	/**
	* Gets the SwiftMailer Swift_Mailer class instance
	* @return Swift_Mailer
	*/
	public function getMailer() {
		if ($this->mailer===null)
			$this->mailer = Swift_Mailer::newInstance($this->getTransport());
			
		return $this->mailer;
	}
	
    /**
    * Registers swiftMailer autoloader and includes the required files
    */
    public function registerScripts() {
    	if (self::$registeredScripts) return;
    	self::$registeredScripts = true;
		require dirname(__FILE__).'/vendors/swiftMailer/classes/Swift.php';
		Yii::registerAutoloader(array('Swift','autoload'));
		require dirname(__FILE__).'/vendors/swiftMailer/swift_init.php';
	}
}