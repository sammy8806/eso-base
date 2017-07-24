<?php
/**
 * @author Steven Tappert
 * @link   admin@steven-tappert.de
 **/

import('core::logging', 'Logger');

class logger_st extends Logger {

	protected $logFileName = NULL;

	public function setLogFileName( $file ) {
		$this->logFileName = $file;
	}

	/**
	 * @public
	 *         Create a log entry.
	 *
	 * @param string $logFileName Name of the log file to log to
	 * @param string $message     Log message
	 * @param string $type        Desired type of the message
	 *
	 * @author Christian Achatz, Steven Tappert
	 * @version
	 *         Version 0.1, 29.03.2007<br />
	 *         Version 0.2, 02.05.2011 (Flushes the log buffer implicitly after a configured number of entries)<br />
	 *         Version 0.3, 26.03.2012 (Moved $logFileName [=NULL] because it can be set on instance)<br />
	 */
	public function logEntry( $message, $type = 'INFO', $logFileName = NULL ) {
		if ( $logFileName == NULL ) {
			if ( $this->logFileName == NULL ) {
				throw new LoggerException( 'LogFileName not set!' );
			} else {
				$logFileName = $this->logFileName;
			}
		}

		$this->logEntries[$logFileName][] = new LogEntry( $message, $type );

		// flush the log buffer in case the maximum number of entries is reached.
		$this->logEntryCount++;

		if ( $this->logEntryCount > $this->maxBufferLength ) {
			$this->flushLogBuffer();
		}
	}

}

?>