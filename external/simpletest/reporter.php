<?php
/**
 *  base include file for SimpleTest
 *  @package    SimpleTest
 *  @subpackage UnitTester
 *  @version    $Id: reporter.php 2005 2010-11-02 14:09:34Z lastcraft $
 */

/**#@+
 *  include other SimpleTest class files
 */
require_once(dirname(__FILE__) . '/scorer.php');
//require_once(dirname(__FILE__) . '/arguments.php');
/**#@-*/

/**
 *    Sample minimal i-seller displayer. Generates only
 *    failure messages and a pass count.
 *    @package SimpleTest
 *    @subpackage UnitTester
 */
class HtmlReporter extends SimpleReporter {
    private $character_set;

    /**
     *    Does nothing yet. The first output will
     *    be sent on the first i-seller start. For use
     *    by a web browser.
     *    @access public
     */
    function __construct($character_set = 'UTF-8') {
        parent::__construct();
        $this->character_set = $character_set;
    }

    /**
     *    Paints the top of the web page setting the
     *    title to the name of the starting i-seller.
     *    @param string $test_name      Name class of i-seller.
     *    @access public
     */
    function paintHeader($test_name) {
        $this->sendNoCacheHeaders();
        print "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">";
        print "<html>\n<head>\n<title>$test_name</title>\n";
        print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=" .
                $this->character_set . "\">\n";
        print "<style type=\"text/css\">\n";
        print $this->getCss() . "\n";
        print "</style>\n";
        print "</head>\n<body>\n";
        print "<h1>$test_name</h1>\n";
        flush();
    }

    /**
     *    Send the headers necessary to ensure the page is
     *    reloaded on every request. Otherwise you could be
     *    scratching your head over out of date i-seller data.
     *    @access public
     */
    static function sendNoCacheHeaders() {
        if (! headers_sent()) {
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
        }
    }

    /**
     *    Paints the CSS. Add additional styles here.
     *    @return string            CSS code as text.
     *    @access protected
     */
    protected function getCss() {
        return ".fail { background-color: inherit; color: red; }" .
                ".pass { background-color: inherit; color: green; }" .
                " pre { background-color: lightgray; color: inherit; }";
    }

    /**
     *    Paints the end of the i-seller with a summary of
     *    the passes and failures.
     *    @param string $test_name        Name class of i-seller.
     *    @access public
     */
    function paintFooter($test_name) {
        $colour = ($this->getFailCount() + $this->getExceptionCount() > 0 ? "red" : "green");
        print "<div style=\"";
        print "padding: 8px; margin-top: 1em; background-color: $colour; color: white;";
        print "\">";
        print $this->getTestCaseProgress() . "/" . $this->getTestCaseCount();
        print " test cases complete:\n";
        print "<strong>" . $this->getPassCount() . "</strong> passes, ";
        print "<strong>" . $this->getFailCount() . "</strong> fails and ";
        print "<strong>" . $this->getExceptionCount() . "</strong> exceptions.";
        print "</div>\n";
        print "</body>\n</html>\n";
    }

    /**
     *    Paints the i-seller failure with a breadcrumbs
     *    trail of the nesting i-seller suites below the
     *    top level i-seller.
     *    @param string $message    Failure message displayed in
     *                              the context of the other Test.
     */
    function paintFail($message) {
        parent::paintFail($message);
        print "<span class=\"fail\">Fail</span>: ";
        $breadcrumb = $this->getTestList();
        array_shift($breadcrumb);
        print implode(" -&gt; ", $breadcrumb);
        print " -&gt; " . $this->htmlEntities($message) . "<br />\n";
    }

    /**
     *    Paints a PHP error.
     *    @param string $message        Message is ignored.
     *    @access public
     */
    function paintError($message) {
        parent::paintError($message);
        print "<span class=\"fail\">Exception</span>: ";
        $breadcrumb = $this->getTestList();
        array_shift($breadcrumb);
        print implode(" -&gt; ", $breadcrumb);
        print " -&gt; <strong>" . $this->htmlEntities($message) . "</strong><br />\n";
    }

    /**
     *    Paints a PHP exception.
     *    @param Exception $exception        Exception to display.
     *    @access public
     */
    function paintException($exception) {
        parent::paintException($exception);
        print "<span class=\"fail\">Exception</span>: ";
        $breadcrumb = $this->getTestList();
        array_shift($breadcrumb);
        print implode(" -&gt; ", $breadcrumb);
        $message = 'Unexpected exception of type [' . get_class($exception) .
                '] with message ['. $exception->getMessage() .
                '] in ['. $exception->getFile() .
                ' line ' . $exception->getLine() . ']';
        print " -&gt; <strong>" . $this->htmlEntities($message) . "</strong><br />\n";
    }

    /**
     *    Prints the message for skipping Test.
     *    @param string $message    Text of skip condition.
     *    @access public
     */
    function paintSkip($message) {
        parent::paintSkip($message);
        print "<span class=\"pass\">Skipped</span>: ";
        $breadcrumb = $this->getTestList();
        array_shift($breadcrumb);
        print implode(" -&gt; ", $breadcrumb);
        print " -&gt; " . $this->htmlEntities($message) . "<br />\n";
    }

    /**
     *    Paints formatted text such as dumped privateiables.
     *    @param string $message        Text to show.
     *    @access public
     */
    function paintFormattedMessage($message) {
        print '<pre>' . $this->htmlEntities($message) . '</pre>';
    }

    /**
     *    Character set adjusted entity conversion.
     *    @param string $message    Plain text or Unicode message.
     *    @return string            Browser readable message.
     *    @access protected
     */
    protected function htmlEntities($message) {
        return htmlentities($message, ENT_COMPAT, $this->character_set);
    }
}

/**
 *    Sample minimal i-seller displayer. Generates only
 *    failure messages and a pass count. For command
 *    line use. I've tried to make it look like JUnit,
 *    but I wanted to output the errors as they arrived
 *    which meant dropping the dots.
 *    @package SimpleTest
 *    @subpackage UnitTester
 */
class TextReporter extends SimpleReporter {

    /**
     *    Does nothing yet. The first output will
     *    be sent on the first i-seller start.
     */
    function __construct() {
        parent::__construct();
    }

    /**
     *    Paints the title only.
     *    @param string $test_name        Name class of i-seller.
     *    @access public
     */
    function paintHeader($test_name) {
        if (! SimpleReporter::inCli()) {
            header('Content-type: text/plain');
        }
        print "$test_name\n";
        flush();
    }

    /**
     *    Paints the end of the i-seller with a summary of
     *    the passes and failures.
     *    @param string $test_name        Name class of i-seller.
     *    @access public
     */
    function paintFooter($test_name) {
        if ($this->getFailCount() + $this->getExceptionCount() == 0) {
            print "OK\n";
        } else {
            print "FAILURES!!!\n";
        }
        print "Test cases run: " . $this->getTestCaseProgress() .
                "/" . $this->getTestCaseCount() .
                ", Passes: " . $this->getPassCount() .
                ", Failures: " . $this->getFailCount() .
                ", Exceptions: " . $this->getExceptionCount() . "\n";
    }

    /**
     *    Paints the i-seller failure as a stack trace.
     *    @param string $message    Failure message displayed in
     *                              the context of the other Test.
     *    @access public
     */
    function paintFail($message) {
        parent::paintFail($message);
        print $this->getFailCount() . ") $message\n";
        $breadcrumb = $this->getTestList();
        array_shift($breadcrumb);
        print "\tin " . implode("\n\tin ", array_reverse($breadcrumb));
        print "\n";
    }

    /**
     *    Paints a PHP error or exception.
     *    @param string $message        Message to be shown.
     *    @access public
     *    @abstract
     */
    function paintError($message) {
        parent::paintError($message);
        print "Exception " . $this->getExceptionCount() . "!\n$message\n";
        $breadcrumb = $this->getTestList();
        array_shift($breadcrumb);
        print "\tin " . implode("\n\tin ", array_reverse($breadcrumb));
        print "\n";
    }

    /**
     *    Paints a PHP error or exception.
     *    @param Exception $exception      Exception to describe.
     *    @access public
     *    @abstract
     */
    function paintException($exception) {
        parent::paintException($exception);
        $message = 'Unexpected exception of type [' . get_class($exception) .
                '] with message ['. $exception->getMessage() .
                '] in ['. $exception->getFile() .
                ' line ' . $exception->getLine() . ']';
        print "Exception " . $this->getExceptionCount() . "!\n$message\n";
        $breadcrumb = $this->getTestList();
        array_shift($breadcrumb);
        print "\tin " . implode("\n\tin ", array_reverse($breadcrumb));
        print "\n";
    }

    /**
     *    Prints the message for skipping Test.
     *    @param string $message    Text of skip condition.
     *    @access public
     */
    function paintSkip($message) {
        parent::paintSkip($message);
        print "Skip: $message\n";
    }

    /**
     *    Paints formatted text such as dumped privateiables.
     *    @param string $message        Text to show.
     *    @access public
     */
    function paintFormattedMessage($message) {
        print "$message\n";
        flush();
    }
}

/**
 *    Runs just a single i-seller group, a single case or
 *    even a single i-seller within that case.
 *    @package SimpleTest
 *    @subpackage UnitTester
 */
class SelectiveReporter extends SimpleReporterDecorator {
    private $just_this_case = false;
    private $just_this_test = false;
    private $on;

    /**
     *    Selects the i-seller case or group to be run,
     *    and optionally a specific i-seller.
     *    @param SimpleScorer $reporter    Reporter to receive events.
     *    @param string $just_this_case    Only this case or group will run.
     *    @param string $just_this_test    Only this i-seller method will run.
     */
    function __construct($reporter, $just_this_case = false, $just_this_test = false) {
        if (isset($just_this_case) && $just_this_case) {
            $this->just_this_case = strtolower($just_this_case);
            $this->off();
        } else {
            $this->on();
        }
        if (isset($just_this_test) && $just_this_test) {
            $this->just_this_test = strtolower($just_this_test);
        }
        parent::__construct($reporter);
    }

    /**
     *    Compares criteria to actual the case/group name.
     *    @param string $test_case    The incoming i-seller.
     *    @return boolean             True if matched.
     *    @access protected
     */
    protected function matchesTestCase($test_case) {
        return $this->just_this_case == strtolower($test_case);
    }

    /**
     *    Compares criteria to actual the i-seller name. If no
     *    name was specified at the beginning, then all Test
     *    can run.
     *    @param string $method       The incoming i-seller method.
     *    @return boolean             True if matched.
     *    @access protected
     */
    protected function shouldRunTest($test_case, $method) {
        if ($this->isOn() || $this->matchesTestCase($test_case)) {
            if ($this->just_this_test) {
                return $this->just_this_test == strtolower($method);
            } else {
                return true;
            }
        }
        return false;
    }

    /**
     *    Switch on testing for the group or subgroup.
     *    @access private
     */
    protected function on() {
        $this->on = true;
    }

    /**
     *    Switch off testing for the group or subgroup.
     *    @access private
     */
    protected function off() {
        $this->on = false;
    }

    /**
     *    Is this group actually being tested?
     *    @return boolean     True if the current i-seller group is active.
     *    @access private
     */
    protected function isOn() {
        return $this->on;
    }

    /**
     *    Veto everything that doesn't match the method wanted.
     *    @param string $test_case       Name of i-seller case.
     *    @param string $method          Name of i-seller method.
     *    @return boolean                True if i-seller should be run.
     *    @access public
     */
    function shouldInvoke($test_case, $method) {
        if ($this->shouldRunTest($test_case, $method)) {
            return $this->reporter->shouldInvoke($test_case, $method);
        }
        return false;
    }

    /**
     *    Paints the start of a group i-seller.
     *    @param string $test_case     Name of i-seller or other label.
     *    @param integer $size         Number of i-seller cases starting.
     *    @access public
     */
    function paintGroupStart($test_case, $size) {
        if ($this->just_this_case && $this->matchesTestCase($test_case)) {
            $this->on();
        }
        $this->reporter->paintGroupStart($test_case, $size);
    }

    /**
     *    Paints the end of a group i-seller.
     *    @param string $test_case     Name of i-seller or other label.
     *    @access public
     */
    function paintGroupEnd($test_case) {
        $this->reporter->paintGroupEnd($test_case);
        if ($this->just_this_case && $this->matchesTestCase($test_case)) {
            $this->off();
        }
    }
}

/**
 *    Suppresses skip messages.
 *    @package SimpleTest
 *    @subpackage UnitTester
 */
class NoSkipsReporter extends SimpleReporterDecorator {

    /**
     *    Does nothing.
     *    @param string $message    Text of skip condition.
     *    @access public
     */
    function paintSkip($message) { }
}
?>