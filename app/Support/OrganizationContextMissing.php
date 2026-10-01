<?php

namespace App\Support;

use RuntimeException;

/**
 * Thrown when an authenticated user without an active organization attempts an
 * organization-scoped operation. Fail-closed by design: the request must not
 * fall back to "no filter" (which would leak other tenants' data) or to the
 * user's first company.
 */
class OrganizationContextMissing extends RuntimeException
{
}
