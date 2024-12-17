<?php
class ErrorHandler {
    private $logFile = "error_logs/error_log.txt";
    private $debugMode = true; // Set to false in production

    public function __construct() {
        // Create logs directory if it doesn't exist
        if (!is_dir('error_logs')) {
            mkdir('error_logs', 0777, true);
        }

        // Set PHP error handling settings
        error_reporting(E_ALL);
        ini_set('display_errors', $this->debugMode ? '1' : '0');
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
    }

    // Handle regular PHP errors
    public function handleError($errno, $errstr, $errfile, $errline) {
        $errorMessage = date('[Y-m-d H:i:s] ') . "ERROR: [$errno] $errstr\n";
        $errorMessage .= "File: $errfile, Line: $errline\n";
        $errorMessage .= "--------------------\n";

        // Log error
        error_log($errorMessage, 3, $this->logFile);

        // Display error if in debug mode
        if ($this->debugMode) {
            echo json_encode([
                'success' => false,
                'error' => [
                    'message' => $errstr,
                    'file' => $errfile,
                    'line' => $errline
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ]);
        }

        return true;
    }

    // Handle uncaught exceptions
    public function handleException($exception) {
        $errorMessage = date('[Y-m-d H:i:s] ') . "EXCEPTION: " . $exception->getMessage() . "\n";
        $errorMessage .= "File: " . $exception->getFile() . ", Line: " . $exception->getLine() . "\n";
        $errorMessage .= "Stack Trace:\n" . $exception->getTraceAsString() . "\n";
        $errorMessage .= "--------------------\n";

        // Log error
        error_log($errorMessage, 3, $this->logFile);

        // Display error if in debug mode
        if ($this->debugMode) {
            echo json_encode([
                'success' => false,
                'error' => [
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => $exception->getTraceAsString()
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ]);
        }
    }

    // Custom method for logging specific messages
    public function logCustomError($message, $context = []) {
        $errorMessage = date('[Y-m-d H:i:s] ') . "CUSTOM ERROR: $message\n";
        if (!empty($context)) {
            $errorMessage .= "Context: " . print_r($context, true) . "\n";
        }
        $errorMessage .= "--------------------\n";

        error_log($errorMessage, 3, $this->logFile);
    }

    // Method to check if there are any errors
    public function checkErrors() {
        return error_get_last();
    }

    // Method to get the contents of the error log
    public function getErrorLog() {
        if (file_exists($this->logFile)) {
            return file_get_contents($this->logFile);
        }
        return "No errors logged.";
    }

    // Method to clear the error log
    public function clearErrorLog() {
        if (file_exists($this->logFile)) {
            file_put_contents($this->logFile, '');
            return true;
        }
        return false;
    }
}

// Initialize the error handler
$errorHandler = new ErrorHandler();
?> 