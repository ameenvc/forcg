<?php

namespace App\Tenancy;

use RuntimeException;

// Only sanitized, operator-facing messages belong in this exception.
class DnsException extends RuntimeException {}
