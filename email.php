<?php
Class Email {
	public $mail;

	private $emailLib = array();

	public function __construct(){
		$this->mail = new PHPMailer();
		$this->mail->isSMTP();                                      // Set mailer to use SMTP
		$this->mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
		//$this->mail->Host = "smtp.gmail.com"; 
		$this->mail->Port = 26;
		$this->mail->SMTPAuth = true;                               // Enable SMTP authentication
		$this->mail->Username = 'noresponde@email.com';                 // SMTP username
		$this->mail->Password = 'passwordforemail';                           // SMTP password
		$this->mail->SMTPSecure = 'ssl';                        // Enable encryption, 'ssl' also accepted
		$this->mail->From = 'noresponde@email.com';
		$this->mail->FromName = 'Our Group';
		$this->mail->IsHTML(true);
	}


	/**
	 * @abstract send taken from SendEmail.php code, this method sets the email parameters and send it to its recep
	 * 
	 */
	public function sendMail($email, $subject, $template, $params = array(), $alt = "[BRENDIS BELLA] te informa que eres el Santa Secreto de:..."){
		$this->mail->addAddress($email);     // Add a recipient
		$this->mail->Subject = $subject;
		$path = dirname(__FILE__)."/img/bells.png";
		$this->mail->AddEmbeddedImage($path, 'logo');
		$this->getEmailBodyFromTemplate($template, $params);
		$this->mail->AltBody = $alt;
		if($this->mail->send()) {
		    return true;
		} else {
		    return $this->mail->ErrorInfo;
		}
	}


	/**
	 * getEmailBodyFromTemplate changes the way We construct an email content.
	 *	It takes an html file from the templates folder and change the params we set on it.
	 * @param templateKey is the name of the template We want to use.
	 * @param arParams is an array of several parameters We want to use in the template
	 * @uses The vars declared in the template MUST coincide with the vars declared in $arParams
	 *
	 */
	public function getEmailBodyFromTemplate($templateKey, $arParams = array()) {
		
		//get the email body Content from getEmailContent method
		$strReturn = $this->getEmailContent($templateKey);

		/* $strReturn = preg_replace('/\{(\w+)\}/', '<?php $this->getValueFromKey(\'$1\') ?>', $strReturn); */

		preg_match_all('/\{(\w+)\}/', $strReturn, $matches);

		$arTemplateVars = $matches[0];
		$arIndexes = $matches[1];
		$arValues = array();

		foreach ($arIndexes as $index) {
			$arValues[] = $this->getValueFromKey($index, $arParams);
		}

		unset($arIndexes);

		$strReturn = str_replace($arTemplateVars, $arValues, $strReturn);

		$this->mail->Body = $strReturn;
		return true;
		
	}

	/**
	 * getEmailContent just save/get the content of a visited email template
	 * @param templateKey is the name of the template We want to get.
	 * @return the template content as a string.
	 */
	private function getEmailContent($templateKey) {
		try {
			if (!array_key_exists($templateKey, $this->emailLib)) {
				$path = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR;
				$content = $path . 'templates' . DIRECTORY_SEPARATOR . $templateKey . '.html';
				$this->emailLib[$templateKey] = file_get_contents($content);
			}

			return $this->emailLib[$templateKey];
		} catch (Exception $e) {
			//throw new Exception("Error Processing Request", 1);
			
		}
			
	}

	/**
	 * getValueFromKey look for a key and look for it in a parameters Array.
	 * If the index is found in the params Array it return its value, if it's not found then
	 * it returns NULL
	 * @param paramKey is the name of the param (index) we want to look for in the parameters Array.
	 * @param arParam is the parameters Array to look into.
	 * @return the paramKey value or NULL if not found
	 */
	private function getValueFromKey($paramKey, $arParam = array()) {
		//initializing valReturn as null
		$valReturn = null;
		if (array_key_exists($paramKey, $arParam)) {
			$valReturn = $arParam[$paramKey];
		}
		return $valReturn;
	}

}
