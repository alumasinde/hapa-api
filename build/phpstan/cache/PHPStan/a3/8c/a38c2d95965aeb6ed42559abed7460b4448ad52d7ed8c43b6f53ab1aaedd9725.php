<?php declare(strict_types = 1);

// odsl-C:\xampp\htdocs\hapa-api\app\Controllers\AdminAuthController.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Controllers\AdminAuthController
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.70.0.6-8.2.12-953195902ed1efd8c633aea516692e28fc787836a08c20194cfc6dbe63abd7bc',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Controllers\\AdminAuthController',
        'filename' => 'C:/xampp/htdocs/hapa-api/app/Controllers/AdminAuthController.php',
      ),
    ),
    'namespace' => 'App\\Controllers',
    'name' => 'App\\Controllers\\AdminAuthController',
    'shortName' => 'AdminAuthController',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 32,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 15,
    'endLine' => 46,
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
      'admins' => 
      array (
        'declaringClassName' => 'App\\Controllers\\AdminAuthController',
        'implementingClassName' => 'App\\Controllers\\AdminAuthController',
        'name' => 'admins',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Repository\\AdminRepository',
            'isIdentifier' => false,
          ),
        ),
        'default' => 
        array (
          'code' => 'new \\App\\Repository\\AdminRepository()',
          'attributes' => 
          array (
            'startLine' => 17,
            'endLine' => 17,
            'startTokenPos' => 74,
            'startFilePos' => 413,
            'endTokenPos' => 78,
            'endFilePos' => 433,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 33,
        'endColumn' => 96,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'hasher' => 
      array (
        'declaringClassName' => 'App\\Controllers\\AdminAuthController',
        'implementingClassName' => 'App\\Controllers\\AdminAuthController',
        'name' => 'hasher',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Security\\PasswordHasher',
            'isIdentifier' => false,
          ),
        ),
        'default' => 
        array (
          'code' => 'new \\App\\Security\\PasswordHasher()',
          'attributes' => 
          array (
            'startLine' => 17,
            'endLine' => 17,
            'startTokenPos' => 91,
            'startFilePos' => 478,
            'endTokenPos' => 95,
            'endFilePos' => 497,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 99,
        'endColumn' => 160,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'jwt' => 
      array (
        'declaringClassName' => 'App\\Controllers\\AdminAuthController',
        'implementingClassName' => 'App\\Controllers\\AdminAuthController',
        'name' => 'jwt',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Security\\AdminJwtService',
            'isIdentifier' => false,
          ),
        ),
        'default' => 
        array (
          'code' => 'new \\App\\Security\\AdminJwtService()',
          'attributes' => 
          array (
            'startLine' => 17,
            'endLine' => 17,
            'startTokenPos' => 108,
            'startFilePos' => 540,
            'endTokenPos' => 112,
            'endFilePos' => 560,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 163,
        'endColumn' => 223,
        'isPromoted' => true,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'sessions' => 
      array (
        'declaringClassName' => 'App\\Controllers\\AdminAuthController',
        'implementingClassName' => 'App\\Controllers\\AdminAuthController',
        'name' => 'sessions',
        'modifiers' => 132,
        'type' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'App\\Repository\\AdminSessionRepository',
            'isIdentifier' => false,
          ),
        ),
        'default' => 
        array (
          'code' => 'new \\App\\Repository\\AdminSessionRepository()',
          'attributes' => 
          array (
            'startLine' => 17,
            'endLine' => 17,
            'startTokenPos' => 125,
            'startFilePos' => 615,
            'endTokenPos' => 129,
            'endFilePos' => 642,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 17,
        'endLine' => 17,
        'startColumn' => 226,
        'endColumn' => 305,
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
          'admins' => 
          array (
            'name' => 'admins',
            'default' => 
            array (
              'code' => 'new \\App\\Repository\\AdminRepository()',
              'attributes' => 
              array (
                'startLine' => 17,
                'endLine' => 17,
                'startTokenPos' => 74,
                'startFilePos' => 413,
                'endTokenPos' => 78,
                'endFilePos' => 433,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Repository\\AdminRepository',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 17,
            'endLine' => 17,
            'startColumn' => 33,
            'endColumn' => 96,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'hasher' => 
          array (
            'name' => 'hasher',
            'default' => 
            array (
              'code' => 'new \\App\\Security\\PasswordHasher()',
              'attributes' => 
              array (
                'startLine' => 17,
                'endLine' => 17,
                'startTokenPos' => 91,
                'startFilePos' => 478,
                'endTokenPos' => 95,
                'endFilePos' => 497,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Security\\PasswordHasher',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 17,
            'endLine' => 17,
            'startColumn' => 99,
            'endColumn' => 160,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'jwt' => 
          array (
            'name' => 'jwt',
            'default' => 
            array (
              'code' => 'new \\App\\Security\\AdminJwtService()',
              'attributes' => 
              array (
                'startLine' => 17,
                'endLine' => 17,
                'startTokenPos' => 108,
                'startFilePos' => 540,
                'endTokenPos' => 112,
                'endFilePos' => 560,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Security\\AdminJwtService',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 17,
            'endLine' => 17,
            'startColumn' => 163,
            'endColumn' => 223,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
          'sessions' => 
          array (
            'name' => 'sessions',
            'default' => 
            array (
              'code' => 'new \\App\\Repository\\AdminSessionRepository()',
              'attributes' => 
              array (
                'startLine' => 17,
                'endLine' => 17,
                'startTokenPos' => 125,
                'startFilePos' => 615,
                'endTokenPos' => 129,
                'endFilePos' => 642,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'App\\Repository\\AdminSessionRepository',
                'isIdentifier' => false,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => true,
            'attributes' => 
            array (
            ),
            'startLine' => 17,
            'endLine' => 17,
            'startColumn' => 226,
            'endColumn' => 305,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 17,
        'endLine' => 19,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Controllers',
        'declaringClassName' => 'App\\Controllers\\AdminAuthController',
        'implementingClassName' => 'App\\Controllers\\AdminAuthController',
        'currentClassName' => 'App\\Controllers\\AdminAuthController',
        'aliasName' => NULL,
      ),
      'login' => 
      array (
        'name' => 'login',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'never',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 21,
        'endLine' => 35,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Controllers',
        'declaringClassName' => 'App\\Controllers\\AdminAuthController',
        'implementingClassName' => 'App\\Controllers\\AdminAuthController',
        'currentClassName' => 'App\\Controllers\\AdminAuthController',
        'aliasName' => NULL,
      ),
      'logout' => 
      array (
        'name' => 'logout',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'never',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 37,
        'endLine' => 45,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Controllers',
        'declaringClassName' => 'App\\Controllers\\AdminAuthController',
        'implementingClassName' => 'App\\Controllers\\AdminAuthController',
        'currentClassName' => 'App\\Controllers\\AdminAuthController',
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