<?php

/**
* Class for parsing raw emails
*
* This class is used to parse raw emails into
* logical easy to use parts.
*
* @author Joshua Gilman
* @package Parser
*/
class MimeParser
{
    /**
    * Determines how a newline is parsed
    *
    * @var String
    */
    var $P_NEWLINE = "\r\n";


    /**
    * Contains the raw header of the email
    *
    * @var String
    */
    var $header = NULL;

    /**
    * Contains the boundary used to parse the raw email
    *
    * @var String
    */
    var $boundary = NULL;

    /**
    * Contains everything below the header of the raw email
    *
    * @var String
    */
    var $content = NULL;


    /**
    * Contains who the email is addressed to
    *
    * @var String
    */
    var $to = NULL;

    /**
    * Contains who the email is from
    *
    * @var String
    */
    var $from = NULL;

    /**
    * Contains the subject of the email
    *
    * @var String
    */
    var $subject = NULL;

    /**
    * Contains all the types of messages sent
    *
    * @example
    * <code>
    * $parser->message['plain'] // Returns the plain text message
    * $parser->message['htmk'] // Returns the html formatted message
    * </code>
    *
    * @var Mixed
    */
    var $message = array();


    /**
    * Contains all the parsed attachments in the raw email
    *
    * @var Mixed
    */
    var $files = array();


    /**
    * The constructor of the class
    *
    * This function loads and parses the raw email given ($mail)
    * and prepares it for usage
    *
    * @param String $mail
    * @return void
    */
    function __construct($mail)
    {
        if (empty($mail))
        {
            throw new Exception("Invalid email argument; Email cannot be empty");
        }

        // Load everything up //
        $this->load_parts($mail);
        $this->load_contents();
        $this->load_message();
        $this->load_files();
    }

    /**
    * Sets up the class for other functions
    *
    * This function parses the boundary of the raw email
    * then preceeds to parse the header and content of
    * the raw email for other functions to use
    *
    * @param String $content
    * @return void
    */
    function load_parts($content)
    {
        if ($istart = strpos($content, "Content-Type:"))
        {
            if ($istart = strpos($content, "boundary=\"", $istart))
            {
                $istart += strlen("boundary=\"");
                $iend = strpos($content, "\"", $istart);
                $this->boundary = substr($content, $istart, $iend - $istart);
            }
        }

        if (!$this->boundary)
        {
            $this->boundary = $this->P_NEWLINE;
        }

        $parts = explode($this->boundary, $content);

        $header1 = array_shift($parts);
        $header2 = array_shift($parts);

        $this->header = $header1 . $header2;
        $this->content = implode($parts, $this->boundary);
    }

    /**
    * Parses the basic content of the email
    *
    * This function parses the to, from, and subject
    * from the raw email's header
    *
    * @return void
    */
    function load_contents()
    {
        if (preg_match("/To: (.*)/", $this->header, $match))
        {
            $this->to = $match[1];
            if (preg_match("/.*<(.*)>/", $this->to, $match))
            {
                $this->to = $match[1];
            }
        }

        if (preg_match("/From: (.*)/", $this->header, $match))
        {
            $this->from = $match[1];
        }

        if (preg_match("/Subject: (.*)/", $this->header, $match))
        {
            $this->subject = $match[1];
        }
    }

    /**
    * Parses the message from the email
    *
    * This function parses the two common formats of
    * a raw message, plain text, and html formatted.
    * It loads both (if either one exists) into an
    * associative array based on their names
    *
    * @return void
    */
    function load_message()
    {
        if ($istart = strpos($this->content, "Content-Type: text/plain;"))
        {
            $istart = strpos($this->content, $this->P_NEWLINE . $this->P_NEWLINE, $istart);
            $istart += strlen($this->P_NEWLINE . $this->P_NEWLINE);
            $iend = strpos($this->content, $this->boundary);
            $this->message['plain'] = substr($this->content, $istart, $iend - $istart);
        }

        if ($istart = strpos($this->content, "Content-Type: text/html;"))
        {
            $istart = strpos($this->content, $this->P_NEWLINE . $this->P_NEWLINE, $istart);
            $istart += strlen($this->P_NEWLINE . $this->P_NEWLINE);
            $iend = strpos($this->content, $this->boundary);
            $this->message['html'] = substr($this->content, $istart, $iend - $istart);
        }
    }

    /**
    * Parses any attachments in the raw email
    *
    * This function parses ALL attachments in
    * the email into an array of associative arrays
    * containing the common information of each
    * file including the name, base name, extension,
    * and content. The files contents are decoded
    * when they are parsed.
    *
    * @return void
    */
    function load_files()
    {
        $istart = 0;
        while ($istart = strpos($this->content, "Content-Disposition: attachment; filename=\"", $istart))
        {
            $cur_index = sizeof($this->files);

            $istart += strlen("Content-Disposition: attachment; filename=\"");
            $iend = strpos($this->content, "\"", $istart);
            $this->files[$cur_index]['name'] = substr($this->content, $istart, $iend - $istart);

            $parts = explode(".", $this->files[$cur_index]['name']);
            $this->files[$cur_index]['base_name'] = array_shift($parts);
            $this->files[$cur_index]['ext'] = implode(".", $parts);

            $istart = strpos($this->content, $this->P_NEWLINE . $this->P_NEWLINE, $istart);
            $istart += strlen($this->P_NEWLINE . $this->P_NEWLINE);
            $iend = strpos($this->content, $this->boundary, $istart);
            $this->files[$cur_index]['content'] = base64_decode(substr($this->content, $istart, $iend - $istart));
        }
    }
}

?>