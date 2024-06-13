<?php

use utils\JWTService;

die(json_encode(JWTService::validateSession(true)));
