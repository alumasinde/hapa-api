<?php declare(strict_types = 1);

// odsl-C:\xampp\htdocs\hapa-api\app\Security\RateLimiter.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Security\RateLimiter
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.2.12-005757634210231190fe2e95b42cba4609677e6583b900eb524be09389cf5c77',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Security\\RateLimiter',
        'filename' => 'C:/xampp/htdocs/hapa-api/app/Security/RateLimiter.php',
      ),
    ),
    'namespace' => 'App\\Security',
    'name' => 'App\\Security\\RateLimiter',
    'shortName' => 'RateLimiter',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 5,
    'endLine' => 11,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'limits' => 
      array (
        'declaringClassName' => 'App\\Security\\RateLimiter',
        'implementingClassName' => 'App\\Security\\RateLimiter',
        'name' => 'limits',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Repository\\RateLimitRepository',
            'isIdentifier' => false,
          ),
        ),
        'default' => 
        array (
          'code' => 'new \\App\\Repository\\RateLimitRepository()',
          'attributes' => 
          array (
            'startLine' => 6,
            'endLine' => 6,
            'startTokenPos' => 43,
            'startFilePos' => 205,
            'endTokenPos' => 47,
            'endFilePos' => 229,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 6,
        'endLine' => 6,
        'startColumn' => 33,
        'endColumn' => 104,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'limits' => 
          array (
            'name' => 'limits',
            'default' => 
            array (
              'code' => 'new \\App\\Repository\\RateLimitRepository()',
              'attributes' => 
              array (
                'startLine' => 6,
                'endLine' => 6,
                'startTokenPos' => 43,
                'startFilePos' => 205,
                'endTokenPos' => 47,
                'endFilePos' => 229,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Repository\\RateLimitRepository',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 6,
            'endLine' => 6,
            'startColumn' => 33,
            'endColumn' => 104,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 6,
        'endLine' => 6,
        'startColumn' => 5,
        'endColumn' => 108,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Security',
        'declaringClassName' => 'App\\Security\\RateLimiter',
        'implementingClassName' => 'App\\Security\\RateLimiter',
        'currentClassName' => 'App\\Security\\RateLimiter',
        'aliasName' => NULL,
      ),
      'allow' => 
      array (
        'name' => 'allow',
        'parameters' => 
        array (
          'scope' => 
          array (
            'name' => 'scope',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 7,
            'endLine' => 7,
            'startColumn' => 27,
            'endColumn' => 39,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'identifier' => 
          array (
            'name' => 'identifier',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 7,
            'endLine' => 7,
            'startColumn' => 42,
            'endColumn' => 59,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'maxAttempts' => 
          array (
            'name' => 'maxAttempts',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 7,
            'endLine' => 7,
            'startColumn' => 62,
            'endColumn' => 77,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'windowSeconds' => 
          array (
            'name' => 'windowSeconds',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 7,
            'endLine' => 7,
            'startColumn' => 80,
            'endColumn' => 97,
            'parameterIndex' => 3,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'bool',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 7,
        'endLine' => 10,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Security',
        'declaringClassName' => 'App\\Security\\RateLimiter',
        'implementingClassName' => 'App\\Security\\RateLimiter',
        'currentClassName' => 'App\\Security\\RateLimiter',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
      ),
    ),
  ),
));