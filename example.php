<?php
namespace AIOX;
try {
	require "vendor/autoload.php";
	require "api.php";
	
	$ngcs = new cloudBuilder(/* API TOKEN HERE */);
	
	/*
		* Dump the current list of servers
		* Returns JSON | Array
	*/
	var_dump($ngcs->servers());
	
} catch (\Exception $e) {
    if (!headers_sent()) {
        header('HTTP/1.1 503 Service Temporarily Unavailable');
        header('Status: 503 Service Temporarily Unavailable');
    }
    $errorCode = substr(sha1(uniqid(mt_rand(), true)), 0, 5);
    $errorMessage = $errorCode . date(' r ') . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();
	function getExceptionTraceAsString($exception) {
		$rtn = "";
		$count = 0;
		foreach ($exception->getTrace() as $frame) {
			$args = "";
			if (isset($frame['args'])) {
				$args = array();
				foreach ($frame['args'] as $arg) {
					if (is_string($arg)) {
						$args[] = "'" . $arg . "'";
					} elseif (is_array($arg)) {
						$args[] = "Array";
					} elseif (is_null($arg)) {
						$args[] = 'NULL';
					} elseif (is_bool($arg)) {
						$args[] = ($arg) ? "true" : "false";
					} elseif (is_object($arg)) {
						$args[] = get_class($arg);
					} elseif (is_resource($arg)) {
						$args[] = get_resource_type($arg);
					} else {
						$args[] = $arg;
					}   
				}   
				$args = join(", ", $args);
			}
			$rtn .= sprintf( "#%s %s(%s): %s(%s)\n",
									 $count,
									 isset($frame['file']) ? $frame['file'] : 'unknown file',
									 isset($frame['line']) ? $frame['line'] : 'unknown line',
									 (isset($frame['class']))  ? $frame['class'].$frame['type'].$frame['function'] : $frame['function'],
									 $args );
			$count++;
		}
		return $rtn;
	}
	
    file_put_contents(__DIR__ . '/exceptions.log', "\n" . $errorMessage . "\n" . getExceptionTraceAsString($e) . "\n",
        FILE_APPEND);
    exit('Exception: ' . $errorCode . '<br><br><small>The issue has been logged. Please contact the website administrator.</small><br>'.  "\n" . $errorMessage . "\n" . getExceptionTraceAsString($e) . $errorMessage);
}
?>
