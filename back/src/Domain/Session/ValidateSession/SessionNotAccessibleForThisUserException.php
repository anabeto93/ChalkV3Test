<?php

/*
 * This file is part of the Chalkboard Education Application project.
 *
 * Copyright (C) Chalkboard Education
 *
 * @author Elao <contact@elao.com>
 */

namespace App\Domain\Session\ValidateSession;

use App\Domain\Session\SessionException;

class SessionNotAccessibleForThisUserException extends SessionException
{
}
