<?php declare(strict_types = 1);

// osfsl-C:/xampp/htdocs/hapa-api/vendor/composer/../firebase/php-jwt/src/JWT.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Firebase\JWT\JWT
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-50c8aa58a2277319dcf69afcd46370357f02155aef11790674c4eadd94fad2a7-8.2.12-6.70.0.6',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Firebase\\JWT\\JWT',
        'filename' => 'C:/xampp/htdocs/hapa-api/vendor/composer/../firebase/php-jwt/src/JWT.php',
      ),
    ),
    'namespace' => 'Firebase\\JWT',
    'name' => 'Firebase\\JWT\\JWT',
    'shortName' => 'JWT',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * JSON Web Token implementation, based on this spec:
 * https://tools.ietf.org/html/rfc7519
 *
 * PHP version 5
 *
 * @category Authentication
 * @package  Authentication_JWT
 * @author   Neuman Vong <neuman@twilio.com>
 * @author   Anant Narayanan <anant@php.net>
 * @license  http://opensource.org/licenses/BSD-3-Clause 3-clause BSD
 * @link     https://github.com/firebase/php-jwt
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 28,
    'endLine' => 667,
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
      'ASN1_INTEGER' => 
      array (
        'declaringClassName' => 'Firebase\\JWT\\JWT',
        'implementingClassName' => 'Firebase\\JWT\\JWT',
        'name' => 'ASN1_INTEGER',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '0x2',
          'attributes' => 
          array (
            'startLine' => 30,
            'endLine' => 30,
            'startTokenPos' => 68,
            'startFilePos' => 662,
            'endTokenPos' => 68,
            'endFilePos' => 665,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 30,
        'endLine' => 30,
        'startColumn' => 5,
        'endColumn' => 38,
      ),
      'ASN1_SEQUENCE' => 
      array (
        'declaringClassName' => 'Firebase\\JWT\\JWT',
        'implementingClassName' => 'Firebase\\JWT\\JWT',
        'name' => 'ASN1_SEQUENCE',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '0x10',
          'attributes' => 
          array (
            'startLine' => 31,
            'endLine' => 31,
            'startTokenPos' => 79,
            'startFilePos' => 702,
            'endTokenPos' => 79,
            'endFilePos' => 705,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 31,
        'endLine' => 31,
        'startColumn' => 5,
        'endColumn' => 39,
      ),
      'ASN1_BIT_STRING' => 
      array (
        'declaringClassName' => 'Firebase\\JWT\\JWT',
        'implementingClassName' => 'Firebase\\JWT\\JWT',
        'name' => 'ASN1_BIT_STRING',
        'modifiers' => 4,
        'type' => NULL,
        'value' => 
        array (
          'code' => '0x3',
          'attributes' => 
          array (
            'startLine' => 32,
            'endLine' => 32,
            'startTokenPos' => 90,
            'startFilePos' => 744,
            'endTokenPos' => 90,
            'endFilePos' => 747,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 32,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 41,
      ),
    ),
    'immediateProperties' => 
    array (
      'leeway' => 
      array (
        'declaringClassName' => 'Firebase\\JWT\\JWT',
        'implementingClassName' => 'Firebase\\JWT\\JWT',
        'name' => 'leeway',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => '0',
          'attributes' => 
          array (
            'startLine' => 41,
            'endLine' => 41,
            'startTokenPos' => 103,
            'startFilePos' => 952,
            'endTokenPos' => 103,
            'endFilePos' => 952,
          ),
        ),
        'docComment' => '/**
 * When checking nbf, iat or expiration times,
 * we want to provide some extra leeway time to
 * account for clock skew.
 *
 * @var int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 41,
        'endLine' => 41,
        'startColumn' => 5,
        'endColumn' => 30,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'timestamp' => 
      array (
        'declaringClassName' => 'Firebase\\JWT\\JWT',
        'implementingClassName' => 'Firebase\\JWT\\JWT',
        'name' => 'timestamp',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => 'null',
          'attributes' => 
          array (
            'startLine' => 50,
            'endLine' => 50,
            'startTokenPos' => 116,
            'startFilePos' => 1182,
            'endTokenPos' => 116,
            'endFilePos' => 1185,
          ),
        ),
        'docComment' => '/**
 * Allow the current timestamp to be specified.
 * Useful for fixing a value within unit testing.
 * Will default to PHP time() value if null.
 *
 * @var ?int
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 50,
        'endLine' => 50,
        'startColumn' => 5,
        'endColumn' => 36,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'supported_algs' => 
      array (
        'declaringClassName' => 'Firebase\\JWT\\JWT',
        'implementingClassName' => 'Firebase\\JWT\\JWT',
        'name' => 'supported_algs',
        'modifiers' => 17,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'ES384\' => [\'openssl\', \'SHA384\'], \'ES256\' => [\'openssl\', \'SHA256\'], \'ES256K\' => [\'openssl\', \'SHA256\'], \'HS256\' => [\'hash_hmac\', \'SHA256\'], \'HS384\' => [\'hash_hmac\', \'SHA384\'], \'HS512\' => [\'hash_hmac\', \'SHA512\'], \'RS256\' => [\'openssl\', \'SHA256\'], \'RS384\' => [\'openssl\', \'SHA384\'], \'RS512\' => [\'openssl\', \'SHA512\'], \'EdDSA\' => [\'sodium_crypto\', \'EdDSA\']]',
          'attributes' => 
          array (
            'startLine' => 55,
            'endLine' => 66,
            'startTokenPos' => 129,
            'startFilePos' => 1277,
            'endTokenPos' => 251,
            'endFilePos' => 1715,
          ),
        ),
        'docComment' => '/**
 * @var array<string, string[]>
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 66,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      'decode' => 
      array (
        'name' => 'decode',
        'parameters' => 
        array (
          'jwt' => 
          array (
            'name' => 'jwt',
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
            'startLine' => 97,
            'endLine' => 97,
            'startColumn' => 9,
            'endColumn' => 19,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'keyOrKeyArray' => 
          array (
            'name' => 'keyOrKeyArray',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 98,
            'endLine' => 98,
            'startColumn' => 9,
            'endColumn' => 22,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'headers' => 
          array (
            'name' => 'headers',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 99,
                'endLine' => 99,
                'startTokenPos' => 281,
                'startFilePos' => 3862,
                'endTokenPos' => 281,
                'endFilePos' => 3865,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'stdClass',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => true,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 99,
            'endLine' => 99,
            'startColumn' => 9,
            'endColumn' => 34,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'stdClass',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Decodes a JWT string into a PHP object.
 *
 * @param string                 $jwt            The JWT
 * @param Key|ArrayAccess<string,Key>|array<string,Key> $keyOrKeyArray  The Key or associative array of key IDs
 *                                                                      (kid) to Key objects.
 *                                                                      If the algorithm used is asymmetric, this is
 *                                                                      the public key.
 *                                                                      Each Key object contains an algorithm and
 *                                                                      matching key.
 *                                                                      Supported algorithms are \'ES384\',\'ES256\',
 *                                                                      \'HS256\', \'HS384\', \'HS512\', \'RS256\', \'RS384\'
 *                                                                      and \'RS512\'.
 * @param stdClass               $headers                               Optional. Populates stdClass with headers.
 *
 * @return stdClass The JWT\'s payload as a PHP object
 *
 * @throws InvalidArgumentException     Provided key/key-array was empty or malformed
 * @throws DomainException              Provided JWT is malformed
 * @throws UnexpectedValueException     Provided JWT was invalid
 * @throws SignatureInvalidException    Provided JWT was invalid because the signature verification failed
 * @throws BeforeValidException         Provided JWT is trying to be used before it\'s eligible as defined by \'nbf\'
 * @throws BeforeValidException         Provided JWT is trying to be used before it\'s been created as defined by \'iat\'
 * @throws ExpiredException             Provided JWT has since expired, as defined by the \'exp\' claim
 *
 * @uses jsonDecode
 * @uses urlsafeB64Decode
 */',
        'startLine' => 96,
        'endLine' => 182,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Firebase\\JWT',
        'declaringClassName' => 'Firebase\\JWT\\JWT',
        'implementingClassName' => 'Firebase\\JWT\\JWT',
        'currentClassName' => 'Firebase\\JWT\\JWT',
        'aliasName' => NULL,
      ),
      'encode' => 
      array (
        'name' => 'encode',
        'parameters' => 
        array (
          'payload' => 
          array (
            'name' => 'payload',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 200,
            'endLine' => 200,
            'startColumn' => 9,
            'endColumn' => 22,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 201,
            'endLine' => 201,
            'startColumn' => 9,
            'endColumn' => 12,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'alg' => 
          array (
            'name' => 'alg',
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
            'startLine' => 202,
            'endLine' => 202,
            'startColumn' => 9,
            'endColumn' => 19,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'keyId' => 
          array (
            'name' => 'keyId',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 203,
                'endLine' => 203,
                'startTokenPos' => 1070,
                'startFilePos' => 8277,
                'endTokenPos' => 1070,
                'endFilePos' => 8280,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 203,
            'endLine' => 203,
            'startColumn' => 9,
            'endColumn' => 29,
            'parameterIndex' => 3,
            'isOptional' => true,
          ),
          'head' => 
          array (
            'name' => 'head',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 204,
                'endLine' => 204,
                'startTokenPos' => 1080,
                'startFilePos' => 8306,
                'endTokenPos' => 1080,
                'endFilePos' => 8309,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'array',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 204,
            'endLine' => 204,
            'startColumn' => 9,
            'endColumn' => 27,
            'parameterIndex' => 4,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Converts and signs a PHP array into a JWT string.
 *
 * @param array<mixed>          $payload PHP array
 * @param string|resource|OpenSSLAsymmetricKey|OpenSSLCertificate $key The secret key.
 * @param string                $alg     Supported algorithms are \'ES384\',\'ES256\', \'ES256K\', \'HS256\',
 *                                       \'HS384\', \'HS512\', \'RS256\', \'RS384\', and \'RS512\'
 * @param string                $keyId
 * @param array<string, string> $head    An array with header elements to attach
 *
 * @return string A signed JWT
 *
 * @uses jsonEncode
 * @uses urlsafeB64Encode
 */',
        'startLine' => 199,
        'endLine' => 223,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Firebase\\JWT',
        'declaringClassName' => 'Firebase\\JWT\\JWT',
        'implementingClassName' => 'Firebase\\JWT\\JWT',
        'currentClassName' => 'Firebase\\JWT\\JWT',
        'aliasName' => NULL,
      ),
      'sign' => 
      array (
        'name' => 'sign',
        'parameters' => 
        array (
          'msg' => 
          array (
            'name' => 'msg',
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
            'startLine' => 238,
            'endLine' => 238,
            'startColumn' => 9,
            'endColumn' => 19,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'key' => 
          array (
            'name' => 'key',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 239,
            'endLine' => 239,
            'startColumn' => 9,
            'endColumn' => 12,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'alg' => 
          array (
            'name' => 'alg',
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
            'startLine' => 240,
            'endLine' => 240,
            'startColumn' => 9,
            'endColumn' => 19,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Sign a string with a given key and algorithm.
 *
 * @param string $msg  The message to sign
 * @param string|resource|OpenSSLAsymmetricKey|OpenSSLCertificate  $key  The secret key.
 * @param string $alg  Supported algorithms are \'EdDSA\', \'ES384\', \'ES256\', \'ES256K\', \'HS256\',
 *                    \'HS384\', \'HS512\', \'RS256\', \'RS384\', and \'RS512\'
 *
 * @return string An encrypted message
 *
 * @throws DomainException Unsupported algorithm or bad key was specified
 */',
        'startLine' => 237,
        'endLine' => 288,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Firebase\\JWT',
        'declaringClassName' => 'Firebase\\JWT\\JWT',
        'implementingClassName' => 'Firebase\\JWT\\JWT',
        'currentClassName' => 'Firebase\\JWT\\JWT',
        'aliasName' => NULL,
      ),
      'verify' => 
      array (
        'name' => 'verify',
        'parameters' => 
        array (
          'msg' => 
          array (
            'name' => 'msg',
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
            'startLine' => 304,
            'endLine' => 304,
            'startColumn' => 9,
            'endColumn' => 19,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'signature' => 
          array (
            'name' => 'signature',
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
            'startLine' => 305,
            'endLine' => 305,
            'startColumn' => 9,
            'endColumn' => 25,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
          'keyMaterial' => 
          array (
            'name' => 'keyMaterial',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 306,
            'endLine' => 306,
            'startColumn' => 9,
            'endColumn' => 20,
            'parameterIndex' => 2,
            'isOptional' => false,
          ),
          'alg' => 
          array (
            'name' => 'alg',
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
            'startLine' => 307,
            'endLine' => 307,
            'startColumn' => 9,
            'endColumn' => 19,
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
        'docComment' => '/**
 * Verify a signature with the message, key and method. Not all methods
 * are symmetric, so we must have a separate verify and sign method.
 *
 * @param string $msg         The original message (header and body)
 * @param string $signature   The original signature
 * @param string|resource|OpenSSLAsymmetricKey|OpenSSLCertificate  $keyMaterial For Ed*, ES*, HS*, a string key works. for RS*, must be an instance of OpenSSLAsymmetricKey
 * @param string $alg         The algorithm
 *
 * @return bool
 *
 * @throws DomainException Invalid Algorithm, bad key, or OpenSSL failure
 */',
        'startLine' => 303,
        'endLine' => 356,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Firebase\\JWT',
        'declaringClassName' => 'Firebase\\JWT\\JWT',
        'implementingClassName' => 'Firebase\\JWT\\JWT',
        'currentClassName' => 'Firebase\\JWT\\JWT',
        'aliasName' => NULL,
      ),
      'jsonDecode' => 
      array (
        'name' => 'jsonDecode',
        'parameters' => 
        array (
          'input' => 
          array (
            'name' => 'input',
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
            'startLine' => 367,
            'endLine' => 367,
            'startColumn' => 39,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Decode a JSON string into a PHP object.
 *
 * @param string $input JSON string
 *
 * @return mixed The decoded JSON string
 *
 * @throws DomainException Provided string was invalid JSON
 */',
        'startLine' => 367,
        'endLine' => 377,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Firebase\\JWT',
        'declaringClassName' => 'Firebase\\JWT\\JWT',
        'implementingClassName' => 'Firebase\\JWT\\JWT',
        'currentClassName' => 'Firebase\\JWT\\JWT',
        'aliasName' => NULL,
      ),
      'jsonEncode' => 
      array (
        'name' => 'jsonEncode',
        'parameters' => 
        array (
          'input' => 
          array (
            'name' => 'input',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'array',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 388,
            'endLine' => 388,
            'startColumn' => 39,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Encode a PHP array into a JSON string.
 *
 * @param array<mixed> $input A PHP array
 *
 * @return string JSON representation of the PHP array
 *
 * @throws DomainException Provided object could not be encoded to valid JSON
 */',
        'startLine' => 388,
        'endLine' => 400,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Firebase\\JWT',
        'declaringClassName' => 'Firebase\\JWT\\JWT',
        'implementingClassName' => 'Firebase\\JWT\\JWT',
        'currentClassName' => 'Firebase\\JWT\\JWT',
        'aliasName' => NULL,
      ),
      'urlsafeB64Decode' => 
      array (
        'name' => 'urlsafeB64Decode',
        'parameters' => 
        array (
          'input' => 
          array (
            'name' => 'input',
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
            'startLine' => 411,
            'endLine' => 411,
            'startColumn' => 45,
            'endColumn' => 57,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Decode a string with URL-safe Base64.
 *
 * @param string $input A Base64 encoded string
 *
 * @return string A decoded string
 *
 * @throws InvalidArgumentException invalid base64 characters
 */',
        'startLine' => 411,
        'endLine' => 414,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Firebase\\JWT',
        'declaringClassName' => 'Firebase\\JWT\\JWT',
        'implementingClassName' => 'Firebase\\JWT\\JWT',
        'currentClassName' => 'Firebase\\JWT\\JWT',
        'aliasName' => NULL,
      ),
      'convertBase64UrlToBase64' => 
      array (
        'name' => 'convertBase64UrlToBase64',
        'parameters' => 
        array (
          'input' => 
          array (
            'name' => 'input',
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
            'startLine' => 426,
            'endLine' => 426,
            'startColumn' => 53,
            'endColumn' => 65,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Convert a string in the base64url (URL-safe Base64) encoding to standard base64.
 *
 * @param string $input A Base64 encoded string with URL-safe characters (-_ and no padding)
 *
 * @return string A Base64 encoded string with standard characters (+/) and padding (=), when
 * needed.
 *
 * @see https://www.rfc-editor.org/rfc/rfc4648
 */',
        'startLine' => 426,
        'endLine' => 434,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Firebase\\JWT',
        'declaringClassName' => 'Firebase\\JWT\\JWT',
        'implementingClassName' => 'Firebase\\JWT\\JWT',
        'currentClassName' => 'Firebase\\JWT\\JWT',
        'aliasName' => NULL,
      ),
      'urlsafeB64Encode' => 
      array (
        'name' => 'urlsafeB64Encode',
        'parameters' => 
        array (
          'input' => 
          array (
            'name' => 'input',
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
            'startLine' => 443,
            'endLine' => 443,
            'startColumn' => 45,
            'endColumn' => 57,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Encode a string with URL-safe Base64.
 *
 * @param string $input The string you want encoded
 *
 * @return string The base64 encode of what you passed in
 */',
        'startLine' => 443,
        'endLine' => 446,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Firebase\\JWT',
        'declaringClassName' => 'Firebase\\JWT\\JWT',
        'implementingClassName' => 'Firebase\\JWT\\JWT',
        'currentClassName' => 'Firebase\\JWT\\JWT',
        'aliasName' => NULL,
      ),
      'getKey' => 
      array (
        'name' => 'getKey',
        'parameters' => 
        array (
          'keyOrKeyArray' => 
          array (
            'name' => 'keyOrKeyArray',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 460,
            'endLine' => 460,
            'startColumn' => 9,
            'endColumn' => 22,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'kid' => 
          array (
            'name' => 'kid',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'string',
                      'isIdentifier' => true,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 461,
            'endLine' => 461,
            'startColumn' => 9,
            'endColumn' => 20,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Firebase\\JWT\\Key',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Determine if an algorithm has been provided for each Key
 *
 * @param Key|ArrayAccess<string,Key>|array<string,Key> $keyOrKeyArray
 * @param string|null            $kid
 *
 * @throws UnexpectedValueException
 *
 * @return Key
 */',
        'startLine' => 459,
        'endLine' => 481,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Firebase\\JWT',
        'declaringClassName' => 'Firebase\\JWT\\JWT',
        'implementingClassName' => 'Firebase\\JWT\\JWT',
        'currentClassName' => 'Firebase\\JWT\\JWT',
        'aliasName' => NULL,
      ),
      'constantTimeEquals' => 
      array (
        'name' => 'constantTimeEquals',
        'parameters' => 
        array (
          'left' => 
          array (
            'name' => 'left',
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
            'startLine' => 488,
            'endLine' => 488,
            'startColumn' => 47,
            'endColumn' => 58,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'right' => 
          array (
            'name' => 'right',
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
            'startLine' => 488,
            'endLine' => 488,
            'startColumn' => 61,
            'endColumn' => 73,
            'parameterIndex' => 1,
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
        'docComment' => '/**
 * @param string $left  The string of known length to compare against
 * @param string $right The user-supplied string
 * @return bool
 */',
        'startLine' => 488,
        'endLine' => 502,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Firebase\\JWT',
        'declaringClassName' => 'Firebase\\JWT\\JWT',
        'implementingClassName' => 'Firebase\\JWT\\JWT',
        'currentClassName' => 'Firebase\\JWT\\JWT',
        'aliasName' => NULL,
      ),
      'handleJsonError' => 
      array (
        'name' => 'handleJsonError',
        'parameters' => 
        array (
          'errno' => 
          array (
            'name' => 'errno',
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
            'startLine' => 513,
            'endLine' => 513,
            'startColumn' => 45,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'void',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Helper method to create a JSON error.
 *
 * @param int $errno An error number from json_last_error()
 *
 * @throws DomainException
 *
 * @return void
 */',
        'startLine' => 513,
        'endLine' => 527,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Firebase\\JWT',
        'declaringClassName' => 'Firebase\\JWT\\JWT',
        'implementingClassName' => 'Firebase\\JWT\\JWT',
        'currentClassName' => 'Firebase\\JWT\\JWT',
        'aliasName' => NULL,
      ),
      'safeStrlen' => 
      array (
        'name' => 'safeStrlen',
        'parameters' => 
        array (
          'str' => 
          array (
            'name' => 'str',
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
            'startLine' => 536,
            'endLine' => 536,
            'startColumn' => 40,
            'endColumn' => 50,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get the number of bytes in cryptographic strings.
 *
 * @param string $str
 *
 * @return int
 */',
        'startLine' => 536,
        'endLine' => 542,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Firebase\\JWT',
        'declaringClassName' => 'Firebase\\JWT\\JWT',
        'implementingClassName' => 'Firebase\\JWT\\JWT',
        'currentClassName' => 'Firebase\\JWT\\JWT',
        'aliasName' => NULL,
      ),
      'signatureToDER' => 
      array (
        'name' => 'signatureToDER',
        'parameters' => 
        array (
          'sig' => 
          array (
            'name' => 'sig',
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
            'startLine' => 550,
            'endLine' => 550,
            'startColumn' => 44,
            'endColumn' => 54,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Convert an ECDSA signature to an ASN.1 DER sequence
 *
 * @param   string $sig The ECDSA signature to convert
 * @return  string The encoded DER object
 */',
        'startLine' => 550,
        'endLine' => 574,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Firebase\\JWT',
        'declaringClassName' => 'Firebase\\JWT\\JWT',
        'implementingClassName' => 'Firebase\\JWT\\JWT',
        'currentClassName' => 'Firebase\\JWT\\JWT',
        'aliasName' => NULL,
      ),
      'encodeDER' => 
      array (
        'name' => 'encodeDER',
        'parameters' => 
        array (
          'type' => 
          array (
            'name' => 'type',
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
            'startLine' => 584,
            'endLine' => 584,
            'startColumn' => 39,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'value' => 
          array (
            'name' => 'value',
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
            'startLine' => 584,
            'endLine' => 584,
            'startColumn' => 50,
            'endColumn' => 62,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Encodes a value into a DER object.
 *
 * @param   int     $type DER tag
 * @param   string  $value the value to encode
 *
 * @return  string  the encoded object
 */',
        'startLine' => 584,
        'endLine' => 598,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Firebase\\JWT',
        'declaringClassName' => 'Firebase\\JWT\\JWT',
        'implementingClassName' => 'Firebase\\JWT\\JWT',
        'currentClassName' => 'Firebase\\JWT\\JWT',
        'aliasName' => NULL,
      ),
      'signatureFromDER' => 
      array (
        'name' => 'signatureFromDER',
        'parameters' => 
        array (
          'der' => 
          array (
            'name' => 'der',
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
            'startLine' => 608,
            'endLine' => 608,
            'startColumn' => 46,
            'endColumn' => 56,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'keySize' => 
          array (
            'name' => 'keySize',
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
            'startLine' => 608,
            'endLine' => 608,
            'startColumn' => 59,
            'endColumn' => 70,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Encodes signature from a DER object.
 *
 * @param   string  $der binary signature in DER format
 * @param   int     $keySize the number of bits in the key
 *
 * @return  string  the signature
 */',
        'startLine' => 608,
        'endLine' => 625,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Firebase\\JWT',
        'declaringClassName' => 'Firebase\\JWT\\JWT',
        'implementingClassName' => 'Firebase\\JWT\\JWT',
        'currentClassName' => 'Firebase\\JWT\\JWT',
        'aliasName' => NULL,
      ),
      'readDER' => 
      array (
        'name' => 'readDER',
        'parameters' => 
        array (
          'der' => 
          array (
            'name' => 'der',
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
            'startLine' => 636,
            'endLine' => 636,
            'startColumn' => 37,
            'endColumn' => 47,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'offset' => 
          array (
            'name' => 'offset',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 636,
                'endLine' => 636,
                'startTokenPos' => 3496,
                'startFilePos' => 23382,
                'endTokenPos' => 3496,
                'endFilePos' => 23382,
              ),
            ),
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
            'startLine' => 636,
            'endLine' => 636,
            'startColumn' => 50,
            'endColumn' => 64,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'array',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Reads binary DER-encoded data and decodes into a single object
 *
 * @param string $der the binary data in DER format
 * @param int $offset the offset of the data stream containing the object
 * to decode
 *
 * @return array{int, string|null} the new offset and the decoded object
 */',
        'startLine' => 636,
        'endLine' => 666,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Firebase\\JWT',
        'declaringClassName' => 'Firebase\\JWT\\JWT',
        'implementingClassName' => 'Firebase\\JWT\\JWT',
        'currentClassName' => 'Firebase\\JWT\\JWT',
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